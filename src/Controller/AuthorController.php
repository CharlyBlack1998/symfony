<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author/create", name="author_create")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function createAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
            $this->addFlash('success', 'Author success created');

            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/author/update/{id}", name="author_update")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $author = $entityManager->find(Author::class, $id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
            $this->addFlash('success', 'Author success updated');

            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/author/delete/{id}", name="author_delete")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $author = $entityManager->find(Author::class, $id);
        $entityManager->remove($author);
        $entityManager->flush();
        $this->addFlash('success', 'Author success deleted');

        return $this->redirectToRoute('author_list');
    }

    /**
     * @Route("/author/show/{id}", name="author_show")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $entityManager->find(Author::class, $id),
        ]);
    }

    /**
     * @Route("/author/list", name="author_list")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var AuthorRepository $repository */
        $repository = $entityManager->getRepository(Author::class);
        $authors = ($name = $request->query->get('name'))
            ? $repository->findByName($name)
            : $repository->findAll()
        ;

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }
}
