<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use App\Repository\SloganRepository;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/dago')]
class SitebaseController extends AbstractController
{


    #[Route('/', name: 'sitebase')]
    public function index(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/index.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'acceuil' => 'acceuil'
        ]);
    }


    #[Route('/relation-client', name: 'relation-client')]
    public function rlClient(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/relationClient.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'rlClient' => 'rlClient'
        ]);
    }

    #[Route('/devWeb', name: 'devWeb')]
    public function devWeb(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/devWeb.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'devWeb' => 'devWeb'
        ]);
    }

    #[Route('/redactionWeb', name: 'redactionWeb')]
    public function redactionWeb(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/redactionWeb.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'redactionWeb' => 'redactionWeb'
        ]);
    }

    #[Route('/multimedia', name: 'multimedia')]
    public function multimedia(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/multimedia.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'multimedia' => 'multimedia'
        ]);
    }


    #[Route('/operation-de-Saisie', name: 'opSaisie')]
    public function opSaisie(InformationRepository $informationRepository, SloganRepository $sloganRepository): Response
    {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        return $this->render('site/opSaisie.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'opSaisie' => 'opSaisie'
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(
        Request $request,
        InformationRepository $informationRepository,
        SloganRepository $sloganRepository,
        MailerInterface $mailer
    ): Response {
        $information = $informationRepository->find(1);
        $slogan = $sloganRepository->find(1);


        if ($request->get('email') && $request->get('name') && $request->get('subject') && $request->get('message')) {

            $email = (new Email())
                ->from($request->get('email'))
                ->to('ralaitsiravasoloherimandranto@gmail.com')
                ->subject($request->get('subject'))
                ->text($request->get('message'));

            $mailer->send($email);

            $this->addFlash('success', 'votre message a bien été envoyé');
            return  $this->redirectToRoute('contact', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('site/contact.html.twig', [
            'information' => $information,
            'slogan' => $slogan,
            'contact' => 'contact'
        ]);
    }


    #[Route('/service/detail', name: 'detail')]
    public function detail(Request $request, InformationRepository $informationRepository): Response
    {
        $information = $informationRepository->find(1);


        // if($request->Request->get('name') && $request->Request->get('email') 
        // && $request->Request->get('subject') && $request->Request->get('message')){

        //     $email = new Email

        // }

        return $this->render('site/detail.html.twig', [
            'information' => $information,
            'contact' => 'contact'
        ]);
    }
}
