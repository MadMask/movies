<?php
/**
 * Created by PhpStorm.
 * User: mrouze2016
 * Date: 25/09/2017
 * Time: 14:25
 */

namespace AppBundle\Controller;

use AppBundle\Form\MovieType;
use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminMovieController extends Controller
{
    public function createAction(Request $request)
    {

        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success", "Le film a bien été ajouté");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);
        }

        return $this->render('AppBundle:AdminMovie:create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    public function editAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success", "Le film a bien été modifié");

            return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

        }

        return $this->render('AppBundle:AdminMovie:edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();

        $this->addFlash("success", "Le film a bien été supprimé");
        return $this->redirectToRoute("app_homepage");

    }

    public function listAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findMoviesListAdmin(50);

        return $this->render('AppBundle:AdminMovie:list.html.twig', [
            "movies" => $movies
        ]);
    }
}