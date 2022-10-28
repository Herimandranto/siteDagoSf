<?php

namespace App\Controller;

use App\Entity\Information;
use App\Form\InformationType;
use App\Repository\InformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/information')]
class InformationController extends AbstractController
{
    #[Route('/', name: 'app_information_index', methods: ['GET'])]
    public function index(InformationRepository $informationRepository): Response
    {
        return $this->render('information/index.html.twig', [
            'information' => $informationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_information_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InformationRepository $informationRepository): Response
    {
        $information = new Information();
        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $informationRepository->add($information, true);

            return $this->redirectToRoute('app_information_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('information/new.html.twig', [
            'information' => $information,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_information_show', methods: ['GET'])]
    public function show(Information $information): Response
    {
        return $this->render('information/show.html.twig', [
            'information' => $information,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_information_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Information $information, InformationRepository $informationRepository): Response
    {
        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $informationRepository->add($information, true);

            return $this->redirectToRoute('app_information_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('information/edit.html.twig', [
            'information' => $information,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_information_delete', methods: ['POST'])]
    public function delete(Request $request, Information $information, InformationRepository $informationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$information->getId(), $request->request->get('_token'))) {
            $informationRepository->remove($information, true);
        }

        return $this->redirectToRoute('app_information_index', [], Response::HTTP_SEE_OTHER);
    }
}
