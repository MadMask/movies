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
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findUpcomingMovies(50);

        return $this->render('AppBundle:Default:index.html.twig', [
            "movies" => $movies
        ]);
    }

    public function detailAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

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
