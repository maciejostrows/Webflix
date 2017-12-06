<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Comments;
use MainBundle\Entity\Favourites;
use MainBundle\Entity\Movies;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/allMovies/")
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
        $user = $this->getUser();

        $formComments = $this->createFormBuilder($comments)
            ->add('text', TextType::class)
            ->add('Zapisz', SubmitType::class)
            ->getForm();

        $formComments->handleRequest($request);
        if($formComments->isSubmitted()){
            $comments = $formComments->getData();
            $comments->setAuthor($user);
            $comments->setMovies($result);
            $em = $this->getDoctrine()->getManager();

            $em->persist($comments);
            $em->flush();
        }




        return $this->render("MainBundle::movie_show.html.twig", ['form'=>$formComments->createView(), 'result'=>$result,]);
    }

    /**
     * @Route("/addToFavourites/{id}")
     */

    public function addToFavouritesAction($id){

        //dodawanie filmu do ulubionych. trzeba pobrac usera, wybrac z bazy caly obiekt filmu,
        //i uzyc funkcji addMovie podajac mu zmienna z obiektem filmu.

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository("MainBundle:Movies");

        $movie = $repository->findOneById($id);
        $user->addMovie($movie);



        $em = $this->getDoctrine()->getManager();


        $em->flush();

        return new Response("Film dodano do ulubionych!");


    }

    /**
     * @Route("/")
     */

    public function userPageAction(){
        //Wybieranie ulubionych filmow, wybranie encji Movies, na user uzycie funkcji getMovies
        //podajac obiekt movie jako atrybut.
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository("MainBundle:Movies");
        $movie = $repository->findAll();

        $favourites = $user->getMovies($movie);

        //Wyswietlenie 3 najnowszych filmow

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT movies FROM MainBundle:Movies movies ORDER BY movies.id DESC');
        $newMovies = $query->setMaxResults(3)->getResult();


        return $this->render("MainBundle::index.html.twig",
            ['favourites'=>$favourites,
                'newMovies'=>$newMovies]);


    }




    }

