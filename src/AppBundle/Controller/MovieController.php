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
}
