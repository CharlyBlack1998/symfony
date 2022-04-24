<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Form\ShopType;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop/create", name="shop_create")
     */
    public function createAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $shop = new Shop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shop);
            $entityManager->flush();
            $this->addFlash('success', 'Shop success created');

            return $this->redirectToRoute('shop_list');
        }

        return $this->render('shop/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/shop/update/{id}", name="shop_update")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $shop = $entityManager->find(Shop::class, $id);
        $form = $this->createForm(ShopType::class, $shop)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shop);
            $entityManager->flush();
            $this->addFlash('success', 'Shop success updated');

            return $this->redirectToRoute('shop_list');
        }

        return $this->render('shop/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/shop/delete/{id}", name="shop_delete")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $shop = $entityManager->find(Shop::class, $id);
        $entityManager->remove($shop);
        $entityManager->flush();
        $this->addFlash('success', 'Shop success deleted');

        return $this->redirectToRoute('shop_list');
    }

    /**
     * @Route("/shop/list", name="shop_list")
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var ShopRepository $repository */
        $repository = $entityManager->getRepository(Shop::class);
        $shops = ($title = $request->query->get('title'))
            ? $repository->findByTitle($title)
            : $repository->findAll()
        ;

        return $this->render('shop/list.html.twig', [
            'shops' => $shops,
        ]);
    }

    /**
     * @Route("/shop/show/{id}", name="shop_show")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('shop/show.html.twig', [
            'shop' => $entityManager->getRepository(Shop::class)->find($id),
        ]);
    }
}
