<?php

namespace App\Controller\Api;

use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\Normalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function listAction(Request $request, EntityManagerInterface $entityManager, Normalizer $normalizer): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);

        /** @var BookRepository $repository */
        $repository = $entityManager->getRepository(Book::class);
        $books = $repository->findByPagination($page, $limit);

        return $this->json([
           'books' => $normalizer->normalizeArray($books, [
               'id',
               'title',
               'authors' => [
                   'id',
                   'name',
               ],
           ]),
        ]);
    }

    /**
     * @Route("/show/{id}", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function showAction(int $id, Normalizer $normalizer, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->find(Book::class, $id);

        return $this->json([
            $normalizer->normalizeObject($book, [
                'id',
                'title',
                'authors' => [
                    'id',
                    'name',
                ],
            ]),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Book successful created',
                ]);
            } else {
                return $this->json([
                    'message' => 'Bad request',
                    'errors' => $form->getErrors(true),
                ]);
            }
        } else {
            return $this->json([
                'message' => 'data is not send',
            ]);
        }
    }

    /**
     * @Route("/delete/{id}", methods={"POST"})
     */
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $book = $entityManager->find(Book::class, $id);
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->json([
            'message' => 'Book successful deleted',
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"POST"})
     */
    public function updateAction(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $book = $entityManager->find(Book::class, $id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Book successful updated',
                ]);
            } else {
                return $this->json([
                    'message' => 'Bad request',
                    'errors' => $form->getErrors(true),
                ]);
            }
        } else {
            return $this->json([
                'message' => 'Data is not send',
            ]);
        }
    }
}
