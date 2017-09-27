<?php
/**
 * Created by PhpStorm.
 * User: mrouze2016
 * Date: 26/09/2017
 * Time: 16:28
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        //a-t-on un utilisateur connecté ?
        $connectedUser = $this->getUser();
        if($connectedUser){
            $this->addFlash('warning', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('app_homepage');
        }

        $user = new User();
        $user->setRoles("ROLE_USER");
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //crée un message qui s'affichera sur la prochaine page
            $this->addFlash("success", "Le compte a été créé avec succès");
            return $this->redirectToRoute("app_homepage");
        }

        return $this->render('AppBundle:User:register.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function loginAction(Request $request)
    {
        // get the login error if there is one
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('AppBundle:User:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    public function watchedListAction()
    {
        return $this->render('AppBundle:User:watchedMovies.html.twig');
    }

    public function addWatchedListAction($id)
    {
        $user = $this->getUser();

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        $user->addWatchedMovies($movie);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success", "Le film a bien été ajouté aux films à regarder");

        return $this->redirectToRoute('user_watched_movies');
    }

    public function removeWatchedListAction($id)
    {
        $user = $this->getUser();

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        $user->removeWatchedMovies($movie);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success", "Le film a bien été retiré des films à regarder");

        return $this->redirectToRoute('user_watched_movies');
    }
}