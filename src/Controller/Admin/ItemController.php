<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Form\ItemType; 
use App\Entity\Category;
use App\Repository\ItemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItemRepository $itemRepository, ManagerRegistry $doctrine): Response
    {
        $item = new Item();
        $em = $doctrine->getManager();
        $category = $em->getRepository(Category::class)->findAll();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $em->getRepository(Category::class)->findOneBy(['id' => $request->get('category')]);
            $item->setCategory($category);
            $itemRepository->add($item, true);
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, ItemRepository $itemRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $category = $em->getRepository(Category::class)->findAll();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $em->getRepository(Category::class)->findOneBy(['id' => $request->get('category')]);
            $item->setCategory($category);
            $itemRepository->add($item, true);

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $itemRepository->remove($item, true);
        }
        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
