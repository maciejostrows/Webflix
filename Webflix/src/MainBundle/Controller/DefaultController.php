<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function allMoviesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Movies");
        $result = $repository->findAll();

        return $this->render("MainBundle::allMovies.html.twig", ['result'=>$result]);
    }

    /**
     * @Route("/movie_show/{id}")
     */

    public function movieShowAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Movies");
        $result = $repository->findOneById($id);

        $comments = new Comments();
        $form = $this->createFormBuilder($comments)
            ->add('author', TextType::class)
            ->add('text', TextType::class)
            ->add('Zapisz', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $comments = $form->getData();
            $comments->setMovies($result);
            $em = $this->getDoctrine()->getManager();

            $em->persist($comments);
            $em->flush();
        }





        return $this->render("MainBundle::movie_show.html.twig", ['form'=>$form->createView(), 'result'=>$result]);
    }




    }

