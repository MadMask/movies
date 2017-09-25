<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function detailAction($id)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        if($movie === null)
        {
            throw $this->createNotFoundException("Ce film n'existe pas.");
        }

        return $this->render('AppBundle:Movie:detail.html.twig', [
            "movie" => $movie
        ]);
    }
}
