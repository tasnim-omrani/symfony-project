<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/author')] 
#route pour le controlleur 

class AuthorController extends AbstractController
{
    private $authors = array(
        array('id' => 1, 'picture' => '/images/me.jpg','username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/me.jpg','username' => ' William Shakespeare', 'email' =>
        ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/me.jpg','username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 300),
        );


    #[Route('/index', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}')]
    function showAuthor($name) {
        return $this->render('author/show.html.twig', ['n'=>$name]);
    }

    #[Route('/list')]
    function list(){
        $authors = array(
            array('id' => 1, 'picture' => '/images/me.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/me.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/me.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );

        return $this->render('author/list.html.twig', ['auth'=>$authors]);
    }

    #[Route('/AuthorDetails/{ii}', name:'AD')]
    function AuthorDetails($ii){

        return $this->render('author/showAuthor.html.twig',
         ['i'=>$ii, 'auth'=>$this->authors]);

    }

    #[Route('/AfficheAuthor', name:'Aff')]
    function AfficheAuthor(AuthorRepository $repo){
        $obj=$repo->findAll();
        return $this->render('author/affiche.html.twig', ['obj'=>$obj]);

    }

    #[Route('/DetailAuthor/{id}', name:'Detail')]
    function DetailAuthor($id, AuthorRepository $repo){
        $obj=$repo->find($id);
        return $this->render('author/detail.html.twig', ['obj'=>$obj]);

    }

    #[Route('/DeleteAuthor/{id}', name:'Delete')]
    function DeleteAuthor($id, AuthorRepository $repo, ManagerRegistry $manager){
        $obj=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($obj);
        $em->flush();
        return $this->redirectToRoute('Aff');
    }

    #[Route('/AddAuthor')]
    function AddAuthor(ManagerRegistry $manager){
        $obj=new Author();
        $obj->setUsername('Tasnim');
        $obj->setEmail('tasnim@gmail.com');
        $em=$manager->getManager();
        $em->persist($obj);
        $em->flush();
        //return $this->redirectToRoute('Aff');
         return new Response('ajouter avec succés!');
    }

    #[Route('/AddForm')]
    function AddForm(ManagerRegistry $manager, Request $Request){
        //3
        $author=new Author();
        //2
        $form=$this->createForm(AuthorType::class, $author)
        ->add('Ajout', SubmitType::class);
        //4
        $form->handleRequest($Request);
       //5
        if( $form->isSubmitted() && $form->isValid() ){
           //6
            $em=$manager->getManager();
           //7
            $em->persist($author);
            $em->flush();
           //8
            return $this->redirectToRoute('Aff');
        }
        //1
        return $this->renderForm('author/Add.html.twig', ['ff'=>$form]);
       // return $this->render('author/Add.html.twig', ['ff'=>$form]);
        
    }

}