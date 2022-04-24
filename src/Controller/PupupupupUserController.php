<?php

namespace App\Controller;

use App\Entity\PupupupupUser;
use App\Form\PupupupupUserType;
use App\Repository\PupupupupUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PupupupupUserController extends AbstractController
{
    /**
     * @Route("/pupupupupuser/create", name="pupupupupuser_create")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function createAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $pupupupupUser = new PupupupupUser();
        $form = $this->createForm(PupupupupUserType::class, $pupupupupUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pupupupupUser);
            $entityManager->flush();

            return $this->redirectToRoute('pupupupupuser_list');
        }

        return $this->render('pupupupupuser/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pupupupupuser/list", name="pupupupupuser_list")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var PupupupupUserRepository $repository */
        $repository = $entityManager->getRepository(PupupupupUser::class);
        $pupupupupUsers = ($name = $request->query->get('name'))
            ? $repository->findByName($name)
            : $repository->findAll()
        ;

        return $this->render('pupupupupuser/list.html.twig', [
            'pupupupupusers' => $pupupupupUsers,
        ]);
    }

    /**
     * @Route("/pupupupupuser/show/{id}", name="pupupupupuser_show")
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('pupupupupuser/show.html.twig', [
            'pupupupupuser' => $entityManager->getRepository(PupupupupUser::class)->find($id),
        ]);
    }
}
