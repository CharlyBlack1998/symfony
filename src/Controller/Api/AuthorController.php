<?php

namespace App\Controller\Api;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\Normalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/api/v1/author")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function listAction(EntityManagerInterface $entityManager, Normalizer $normalizer): Response
    {
        $authors = $entityManager->getRepository(Author::class)->findAll();

        return $this->json([
            'authors' => $normalizer->normalizeArray($authors, [
                'id',
                'name',
                'books' => [
                    'id',
                    'title',
                    'pageQuantity',
                ]
            ]),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function createAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($author);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Author successful created',
                ]);
            } else {
                return $this->json([
                    'message' => 'Bad request',
                    'errors' => $form->getErrors(true),
                ]);
            }
        } else {
            return $this->json([
                'message' => 'Data is not send'
            ]);
        }
    }
}
