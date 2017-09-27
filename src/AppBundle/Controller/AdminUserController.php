<?php
/**
 * Created by PhpStorm.
 * User: mrouze2016
 * Date: 27/09/2017
 * Time: 14:47
 */

namespace AppBundle\Controller;


use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends Controller
{
    public function editAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:User");
        $user = $repo->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "L'utilisateur a bien été modifié");

            return $this->redirectToRoute("admin_user_list");

        }

        return $this->render('AppBundle:AdminUser:edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:User");
        $user = $repo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "L'utilisateur a bien été supprimé");
        return $this->redirectToRoute("app_homepage");

    }

    public function listAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:User");
        $users = $repo->findAll();

        return $this->render('AppBundle:AdminUser:list.html.twig', [
            "users" => $users
        ]);
    }
}