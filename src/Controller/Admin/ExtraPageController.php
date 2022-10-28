<?php

namespace App\Controller\Admin;

use App\Entity\Text1;
use App\Entity\Client;
use App\Form\Text1Type;
use App\Form\ClientType;
use App\Entity\Illustration;
use App\Form\IllustrationType;
use App\Repository\Text1Repository;
use App\Repository\ClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\IllustrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExtraPageController extends AbstractController
{ 

    #[Route('/admin_/extraPage', name: 'admin_extra_page')]
    public function extra(
        Request $request,
        IllustrationRepository $illustrationRepository,
        Text1Repository $text1Repository,
        ClientRepository $clientRepository,
        ManagerRegistry $doctrine
    ): Response {

        $user = $this->getUser();
        $em = $doctrine->getManager();
    
        if(!$user){
            $session = $request->getSession();
            $session->clear();
            return $this->redirectToRoute('app_login');
        }


        if ($illustrationRepository->find(1)) {
            $illustration = $illustrationRepository->find(1);            
        }
        else {
            $illustration = new Illustration();
        }

        if ($text1Repository->find(1)) {
        $text1 = $text1Repository->find(1);
        }
        else {
        $text1 = new Text1();   
        }

        $form1 = $this->createForm(Text1Type::class, $text1);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $text1Repository->add($text1, true);
            return $this->redirectToRoute('admin_extra_page', [], Response::HTTP_SEE_OTHER);
        }


        $form2 = $this->createForm(IllustrationType::class, $illustration);
        
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $illustrationRepository->add($illustration, true);
            return $this->redirectToRoute('admin_extra_page', [], Response::HTTP_SEE_OTHER);
        }

        $client = new Client();
        $form3 = $this->createForm(ClientType::class,$client);
        $form3->handleRequest($request);

        if ($form3->isSubmitted() && $form3->isValid()) {
            $clientRepository->add($client, true);
            return $this->redirectToRoute('admin_extra_page', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('admin/extraPage/index.html.twig', [
            'controller_name' => 'ExtraPageController',
            'extra' => 'extra',
            'form' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'illustration' => $illustrationRepository->find(1),
            'user' => $user,
            'clients' => $clientRepository->findAll(),
        ]);
    }


    #[Route('/client/{id}/delete', name: 'delete_client', methods: ['POST'])]
    public function delete_client(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }
        $this->addFlash('success', 'client supprimé avec succès!');
        return $this->redirectToRoute('admin_extra_page', [], Response::HTTP_SEE_OTHER);
    }


}
