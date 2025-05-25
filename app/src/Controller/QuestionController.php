<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('', name: 'question_list', methods: ['GET'])]
    public function list(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $pagination = $paginator->paginate(
            $questionRepository->queryAll(), // uses alias 'q'
            $request->query->getInt('page', 1),
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['q.id', 'q.createdAt', 'q.updatedAt', 'q.title'],
                'defaultSortFieldName' => 'q.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render('question/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setAuthor($this->getUser()); // or custom logic

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question created successfully.');

            return $this->redirectToRoute('question_list');
        }

        return $this->render('question/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Question updated successfully.');

            return $this->redirectToRoute('question_list');
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form,
            'question' => $question,
        ]);
    }

    #[Route('/{id}', name: 'question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question deleted successfully.');
        }

        return $this->redirectToRoute('question_list');
    }
}
