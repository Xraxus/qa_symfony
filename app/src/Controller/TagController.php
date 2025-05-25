<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagForm;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag')]
final class TagController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagRepository $tagRepository,
    ) {}

    #[Route(name: 'app_tag_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Optional: paginate tags, if you expect many tags
        $queryBuilder = $this->tagRepository->createQueryBuilder('t')->orderBy('t.name', 'ASC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            20 // items per page, adjust as needed
        );

        return $this->render('tag/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagForm::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Tag created successfully.');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tag_show', methods: ['GET'])]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagForm::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Tag updated successfully.');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $submittedToken)) {
            $this->entityManager->remove($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Tag deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_tag_index');
    }
}
