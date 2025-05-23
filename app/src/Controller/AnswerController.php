<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerForm;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/answer')]
final class AnswerController extends AbstractController
{
    #[Route(name: 'app_answer_index', methods: ['GET'])]
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', [
            'answers' => $answerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_answer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerForm::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answer_show', methods: ['GET'])]
    public function show(Answer $answer): Response
    {
        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_answer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnswerForm::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answer_delete', methods: ['POST'])]
    public function delete(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    }
}
