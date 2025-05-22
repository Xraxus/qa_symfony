<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'question_list', methods: ['GET'])]
    public function list(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $pagination = $paginator->paginate(
            $questionRepository->queryAll(), // zakładamy alias 'q' w repozytorium
            $page,
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

    #[Route('/question/{id}', name: 'question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }
}
