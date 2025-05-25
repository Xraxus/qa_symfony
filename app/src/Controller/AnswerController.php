<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerForm;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/answer')]
final class AnswerController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnswerRepository $answerRepository,
    ) {}

    #[Route(name: 'app_answer_index', methods: ['GET'])]
    public function index(): Response
    {
        $answers = $this->answerRepository->findAll();

        return $this->render('answer/index.html.twig', [
            'answers' => $answers,
        ]);
    }

    #[Route('/new', name: 'app_answer_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerForm::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_answer_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
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
    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerForm::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_answer_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_answer_delete', methods: ['POST'])]
    public function delete(Request $request, Answer $answer): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $token)) {
            $this->entityManager->remove($answer);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_answer_index', status: Response::HTTP_SEE_OTHER);
    }
}
