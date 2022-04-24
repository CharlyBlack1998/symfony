<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/create", name="book_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="book_update")
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function updateAction(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->find(Book::class, $id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();
            $this->addFlash('success', 'Book successful updated');

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="book_list")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var BookRepository $repository */
        $repository = $entityManager->getRepository(Book::class);
        $books = ($title = $request->query->get('title'))
            ? $repository->findByTitle($title)
            : $repository->findAll()
        ;

        return $this->render('book/list.html.twig', [
           'books' => $books,
        ]);
    }

    /**
     * @Route("/show/{id}", name="book_show")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('book/show.html.twig', [
           'book' => $entityManager->getRepository(Book::class)->find($id),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="book_delete")
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function deleteAction(int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->find(Book::class, $id);
        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash('success', 'Book successful deleted');

        return $this->redirectToRoute('book_list');
    }
}
