<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/program')]
class AdminProgramController extends AbstractController
{
    #[Route('/', name: 'app_admin_program_index', methods: ['GET'])]
    public function index(
        ProgramRepository $programRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $form = $this->createFormBuilder(null, [
            'method' => 'get'
        ])
            ->add('search', SearchType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('search')->getData();
            $category = $form->get('categories')->getData();
            $query = $programRepository->findLikeTitle($search, $category);
        } else {
            $query = $programRepository->queryFindAll();
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('Admin/admin_program/index.html.twig', [
            'programs' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_admin_program_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/admin_program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_program_show', methods: ['GET'])]
    public function show(Program $program): Response
    {
        return $this->render('Admin/admin_program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/admin_program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_program_delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_program_index', [], Response::HTTP_SEE_OTHER);
    }
}
