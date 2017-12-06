<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Comments;
use MainBundle\Entity\Favourites;
use MainBundle\Entity\Movies;
use MainBundle\Entity\Ratings;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }

        //wyswietlanie wszystkich filmow

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Movies");
        $result = $repository->findAll();

        return $this->render("MainBundle::allMovies.html.twig", ['result' => $result]);
    }

    /**
     * @Route("/movie_show/{id}")
     */

    public function movieShowAction(Request $request, $id)
    {
        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }



        //pobieranie ocen z bazy

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Movies");
        $result = $repository->findOneById($id);

        $query = $em->createQuery('SELECT ratings.rating FROM MainBundle:Ratings ratings WHERE ratings.movies = :id')->setParameter('id', $id);
        $queryResult = $query->getResult();

        //wyciaganie ocen z tablicy tablic i obliczanie sredniej

        $sum = 0;
        $average = 0;

        foreach ($queryResult as $result1){
            foreach ($result1 as $key=>$result2){

                $sum +=$result2;

            }

        }

        $rows = count($queryResult);
        if($average>0){
        $average = $sum/$rows;
        }

        //dodawanie komentarzy

        $comments = new Comments();


        $formComments = $this->createFormBuilder($comments)
            ->add('text', TextType::class)
            ->add('Zapisz', SubmitType::class)
            ->getForm();

        $formComments->handleRequest($request);
        if ($formComments->isSubmitted()) {
            $comments = $formComments->getData();
            $comments->setAuthor($user);
            $comments->setMovies($result);
            $comments->setBadComment(0);
            $em = $this->getDoctrine()->getManager();

            $em->persist($comments);
            $em->flush();
        }


        return $this->render("MainBundle::movie_show.html.twig",
            ['form' => $formComments->createView(),
            'result' => $result,
                'average'=>$average,
                'rows'=>$rows]);
    }

    /**
     * @Route("/addToFavourites/{id}")
     */

    public function addToFavouritesAction(Request $request, $id)
    {

        //dodawanie filmu do ulubionych. trzeba pobrac usera, wybrac z bazy caly obiekt filmu,
        //i uzyc funkcji addMovie podajac mu zmienna z obiektem filmu.

        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $referer = $request->headers->get('referer');


        $repository = $this->getDoctrine()->getRepository("MainBundle:Movies");

        $movie = $repository->findOneById($id);
        $user->addMovie($movie);


        $em = $this->getDoctrine()->getManager();


        $em->flush();

        return $this->render("MainBundle::addedToFavourites.html.twig", ['referer'=>$referer]);


    }

    /**
     * @Route("/addRating/{id}")
     */

    public function addRatingAction(Request $request, $id)
    {

        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        //budowanie formularza z lista do wyboru

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Movies");
        $result = $repository->findOneById($id);


        $ratings = new Ratings();

        $formRatings = $this->createFormBuilder($ratings)
            ->add('rating', ChoiceType::class, array(
                'choices' => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ),
            ))
            ->add('Wyślij', SubmitType::class)
            ->getForm();

        $formRatings->handleRequest($request);
        if ($formRatings->isSubmitted()) {
            $ratings = $formRatings->getData();
            $ratings->setMovies($result);


            $em = $this->getDoctrine()->getManager();
            $em->persist($ratings);
            $em->flush();



        }
        return $this->render("MainBundle::rating.html.twig", ['formRatings'=>$formRatings->createView()]);
    }




    /**
     * @Route("/")
     */

    public function userPageAction()
    {
        //Wybieranie ulubionych filmow, wybranie encji Movies, na user uzycie funkcji getMovies
        //podajac obiekt movie jako atrybut.

        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }


        $repository = $this->getDoctrine()->getRepository("MainBundle:Movies");
        $movie = $repository->findAll();

        $favourites = $user->getMovies($movie);

        //Wyswietlenie 3 najnowszych filmow

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT movies FROM MainBundle:Movies movies ORDER BY movies.id DESC');
        $newMovies = $query->setMaxResults(3)->getResult();


        return $this->render("MainBundle::index.html.twig",
            ['favourites' => $favourites,
                'newMovies' => $newMovies]);


    }

    /**
     * @Route("/search/")
     */

    public function searchAction(Request $request)
    {

        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }

        //budowanie formularza do szukania

        $formSearch = $this->createFormBuilder()
            ->add('text', TextType::class)
            ->add('Szukaj', SubmitType::class)
            ->getForm();

        $result = "";

        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $searchWord = $formSearch->getData();

            //dql do pobierania wynikow

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT movies FROM MainBundle:Movies movies WHERE movies.title LIKE :searchWord');
            $result = $query->execute(['searchWord' => '%' . $searchWord['text'] . '%']);

        }

        return $this->render("MainBundle::search.html.twig", ['result' => $result, 'form' => $formSearch->createView()]);

    }

    /**
     * @Route("/addBadComment/{id}")
     */

    public function addBadCommentAction(Request $request, $id){

        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $referer = $request->headers->get('referer');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("MainBundle:Comments");
        $result = $repository->findOneById($id);

        $result->setBadComment(1);

        $em->persist($result);
        $em->flush();



        return $this->render("MainBundle::addedBadComment.html.twig", ['referer'=>$referer]);

    }
}

