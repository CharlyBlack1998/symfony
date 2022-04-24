<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/create", name="user_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/update/{id}", name="user_update")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $user = $entityManager->find(User::class, $id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'User success updated');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->find(User::class, $id);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'User success deleted');

        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/user/show/{id}", name="user_show")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $entityManager->getRepository(User::class)->find($id),
        ]);
    }

    /**
     * @Route("/user/list", name="user_list")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository(User::class);
        $users = ($name = $request->query->get('name'))
            ? $repository->findByName($name)
            : $repository->findAll()
        ;

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
