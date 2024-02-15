<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{id}', name: 'new_comment')]
    public function create(Burger $burger,Request $request, EntityManagerInterface $manager): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setBurger($burger);

            $manager->persist($comment);
            $manager->flush();
        }

        return $this->redirectToRoute('burger', ["id"=>$burger->getId()]);
    }
}
