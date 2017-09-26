<?php
/**
 * Created by PhpStorm.
 * User: mrouze2016
 * Date: 26/09/2017
 * Time: 10:04
 */

namespace AppBundle\Controller;

use AppBundle\Form\ReviewType;
use AppBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminReviewController extends Controller
{
    public function editAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Review");
        $review = $repo->find($id);

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("success", "La critique a bien été modifiée");

            return $this->redirectToRoute("app_homepage");

        }

        return $this->render('AppBundle:AdminReview:edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Review");
        $review = $repo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        $this->addFlash("success", "La critique a bien été supprimée");
        return $this->redirectToRoute("app_homepage");

    }

    public function listAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Review");
        $review = $repo->findAll();

        return $this->render('AppBundle:AdminReview:list.html.twig', [
            "reviews" => $review
        ]);
    }
}