<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function createAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/list", name="product_list")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function listAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var ProductRepository $repository */
        $repository = $entityManager->getRepository(Product::class);
        $products = ($title = $request->query->get('title'))
            ? $repository->findByTitle($title)
            : $repository->findAll()
        ;

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/show/{id}", name="product_show")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function showAction(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $entityManager->getRepository(Product::class)->find($id),
        ]);
    }

    /**
     * @Route("/product/update/{id}", name="product_update")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->find(Product::class, $id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product success updated');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->find(Product::class, $id);
        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash('success', 'Product success deleted');

        return $this->redirectToRoute('product_list');
    }
}
