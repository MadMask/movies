<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use AppBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\MovieType;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    public function indexAction($page)
    {
        $moviesByPage = 20;
        $offset = $page - 1;
        $offset = $offset * $moviesByPage;

        $minMovieId = $offset;
        $maxMovieId = $offset + $moviesByPage;

        $nextPage = $page + 1;
        $previousPage = $page - 1;

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        //$movies = $repo->findUpcomingMovies(50);
        $movies = $repo->findBy([],
            array('year' => 'DESC'),
            $moviesByPage,
            $offset);


        $em = $this->getDoctrine()->getManager();
        $moviesCount = $repo->countAll($em);

        return $this->render('AppBundle:Default:index.html.twig', [
            "movies" => $movies,
            "nextPage" => $nextPage,
            "previousPage" => $previousPage,
            "minMovieId" => $minMovieId,
            "maxMovieId" => $maxMovieId,
            "movieCount" => $moviesCount
        ]);
    }

    public function detailAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        $user = $this->getUser();
        dump($user);

        if($movie === null)
        {
            throw $this->createNotFoundException("Ce film n'existe pas.");
        }

        $review = new Review();
        $review->setMovie($movie);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("success", "Votre critique a bien été enregistrée");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

        }

        return $this->render('AppBundle:Movie:detail.html.twig', [
            "movie" => $movie,
            "form" => $form->createView()
        ]);
    }
}
