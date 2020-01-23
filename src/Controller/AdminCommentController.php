<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     * @param CommentRepository $repository
     * @return Response
     */
    public function index(CommentRepository $repository): Response
    {

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repository->findAll(),
        ]);
    }


    /**
     * Permet de modifier un Commentaire
     *
     * @Route("/admin/comment/{id}/edit",name="admin_comment_edit")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $form =$this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le commentaire a bien été modifié '
                );
        }


        return $this->render('admin/comment/edit.html.twig', [
            'comment'=>$comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Comment $comment, EntityManagerInterface $manager): RedirectResponse
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimé"
        );

       return $this->redirectToRoute('admin_comment_index');
    }
}
