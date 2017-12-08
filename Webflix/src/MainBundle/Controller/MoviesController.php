<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Movie controller.
 *
 * @Route("movies")
 */
class MoviesController extends Controller
{
    /**
     * Lists all movie entities.
     *
     * @Route("/", name="movies_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movies = $em->getRepository('MainBundle:Movies')->findAll();

        return $this->render('movies/index.html.twig', array(
            'movies' => $movies,
        ));
    }

    /**
     * Creates a new movie entity.
     *
     * @Route("/new", name="movies_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movie = new Movies();
        $form = $this->createForm('MainBundle\Form\MoviesType', $movie);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $movie->getPath();
            $stringPath = substr($movie,68,11);
            $movie->setThumbnail($stringPath);
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('movies_show', array('id' => $movie->getId()));
        }

        return $this->render('movies/new.html.twig', array(
            'movie' => $movie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a movie entity.
     *
     * @Route("/{id}", name="movies_show")
     * @Method("GET")
     */
    public function showAction(Movies $movie)
    {
        $deleteForm = $this->createDeleteForm($movie);

        return $this->render('movies/show.html.twig', array(
            'movie' => $movie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movie entity.
     *
     * @Route("/{id}/edit", name="movies_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Movies $movie)
    {
        $deleteForm = $this->createDeleteForm($movie);
        $editForm = $this->createForm('MainBundle\Form\MoviesType', $movie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movies_edit', array('id' => $movie->getId()));
        }

        return $this->render('movies/edit.html.twig', array(
            'movie' => $movie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movie entity.
     *
     * @Route("/{id}", name="movies_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Movies $movie)
    {
        $form = $this->createDeleteForm($movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();
        }

        return $this->redirectToRoute('movies_index');
    }

    /**
     * Creates a form to delete a movie entity.
     *
     * @param Movies $movie The movie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Movies $movie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movies_delete', array('id' => $movie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
