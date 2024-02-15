<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\BurgerType;
use App\Form\CommentType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BurgerController extends AbstractController
{
    #[Route('/burgers', name: 'burgers')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('burger/index.html.twig', [
            "burgers"=>$burgerRepository->findAll(),
        ]);
    }

    #[Route('/burger/new', name:"burger_new")]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class,$burger );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($burger);
            $manager->flush();

            return $this->redirectToRoute('burgers');
        }

       return $this->render("burger/new.html.twig", [
            'formulaireBurger'=>$form->createView()
       ]) ;
    }

    #[Route('burger/{id}', name:"burger")]
    public function show(Burger $burger):Response
    {

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);

        return $this->render("burger/show.html.twig",[
            "burger"=>$burger,
            "formComment"=>$formComment->createView()
        ]);
    }
}
