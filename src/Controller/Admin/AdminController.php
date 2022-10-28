<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Text1;
use App\Form\ItemType;
use App\Form\UserType;
use App\Form\Text1Type;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\Information;
use App\Form\InformationType;
use App\Entity\PasswordUpdate;
use App\Entity\Slogan;
use App\Form\PasswordUpdateType;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\Text1Repository;
use App\Repository\SloganRepository;
use Symfony\Component\Form\FormError;
use App\Repository\CategoryRepository;
use App\Repository\InformationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard', methods: ['GET'])]
    public function index(Request $request): Response
    {


        $user = $this->getUser();
        if (!$user) {
            $session = $request->getSession();
            $session->clear();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/index.html.twig', [
            'accueil' => 'accueil',
            'user' => $user
        ]);
    }


    #[Route('/profil', name: 'admin_profil', methods: ['GET', 'POST'])]
    public function profil(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        SloganRepository $sloganRepository,
        UserRepository $userRepository,
        InformationRepository $informationRepository,
        SluggerInterface $slugger,
        ManagerRegistry $doctrine
    ): Response {


        $user1 = $this->getUser();

        if (!$user1) {
            $session = $request->getSession();
            $session->clear();
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $em = $doctrine->getManager();
        $slogans = $sloganRepository->findAll();
        if ($slogans[0]) {
            $slogan1 = $slogans[0];
        } else {
            $slogan1 = new Slogan();
        }


        // Slogan form

        if ($request->get('slogan')) {
            $slogan1->setTitle($request->get('slogan'));
            $em->persist($slogan1);
            $em->flush();

            return $this->redirectToRoute('admin_profil', [], Response::HTTP_SEE_OTHER);
        }


        //      Ajout et mise à jour des Informations du site

        if ($informationRepository->find(1)) {
            $information = $informationRepository->find(1);
        } else {
            $information = new Information();
        }

        $info_form = $this->createForm(InformationType::class, $information);
        $info_form->handleRequest($request);

        if ($info_form->isSubmitted() && $info_form->isValid()) {
            $informationRepository->add($information, true);

            return $this->redirectToRoute('admin_profil', [], Response::HTTP_SEE_OTHER);
        }







        $userform = $this->createForm(UserType::class, $user);
        $userform->remove('email');
        $userform->remove('roles');
        $userform->remove('password');

        $userform->handleRequest($request);

        if ($userform->isSubmitted() && $userform->isValid()) {
            $fl = $userform->get('avatarFile')->getData();
            if ($fl) {
                $originalFileName = pathinfo($fl->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFileName = $safeFilename . '.' . $fl->guessExtension();

                // Move the file to the directory
                try {
                    $fl->move(
                        $this->getParameter('avatar_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setAvatar($newFileName);
            }
            $em->persist($user);
            $em->flush();
        }


        //      Update password
        $passwordUpdate = new PasswordUpdate();
        $formPass = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $formPass->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            // Si l'ancien mot de passe n'est pas bon
            if (!$passwordEncoder->isPasswordValid($user, $passwordUpdate->getOldPassword())) {
                $formPass->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            }
            $newEncodedPassword = $passwordEncoder->hashPassword($user, $passwordUpdate->getNewPassword());
            $user->setPassword($newEncodedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès');
        }

        return $this->render('admin/profil/profil.html.twig', [
            'profil' => 'profil',
            'form_pass' => $formPass->createView(),
            'user' => $user,
            'info_form' => $info_form->createView(),
            'slogan' => $slogan1,
            'userform' => $userform->createView()
        ]);
    }






    #[Route('/gestion_service', name: 'admin_tableau', methods: ['GET', 'POST'])]
    public function show(
        Request $request,
        ItemRepository $itemRepository,
        CategoryRepository $categoryRepository,
        ManagerRegistry $doctrine
    ): Response {

        $user = $this->getUser();

        if (!$user) {
            $session = $request->getSession();
            $session->clear();
            return $this->redirectToRoute('app_login');
        }

        $em = $doctrine->getManager();

        $items = $em->getRepository(Item::class)->findAll();
        // création nouveau Item
        $item = new Item();

        $categories = $em->getRepository(Category::class)->findAll();
        $form1 = $this->createForm(ItemType::class, $item);
        $form1->remove('description');

        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $description = $request->get('description');
            $categorie = $em->getRepository(Category::class)->findOneBy(['id' => $request->get('category')]);
            $item->setCategory($categorie);
            $item->setDescription($description);
            $itemRepository->add($item, true);
            return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
        }


        // création catégory
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);
            return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud/tabledata.html.twig', [
            'tableau' => 'tableau',
            'user' => $user,
            'items' => $items,
            'form1' => $form1,
            'categories' => $categories,
            'form' => $form,
        ]);
    }










    #[Route('/service/edit', name: 'service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ItemRepository $itemRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $item = $itemRepository->find($request->get('id'));
        $categorie = $em->getRepository(Category::class)->findOneBy(['id' => $request->get('category')]);

        $item->setCategory($categorie);
        if ($request->get('description')) {
            $item->setDescription($request->get('description'));
        }

        $item->setTitle($request->get('title'));
        $item->setPrice($request->get('price'));

        if ($request->get('imageFile')) {
            $fl = $request->get('imageFile')->getData();
            if ($fl) {
                $originalFileName = pathinfo($fl->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFileName = $safeFilename . '.' . $fl->guessExtension();

                // Move the file to the directory
                try {
                    $fl->move(
                        $this->getParameter('images_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $item->setImage($newFileName);
            }
        }


        $itemRepository->add($item, true);

        return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/category/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function categoryEdit(Request $request, CategoryRepository $categoryRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $category = $categoryRepository->find($request->get('id'));

        $category->setTitle($request->get('title'));
        $category->setCode($request->get('code'));



        if ($request->get('imageFile')) {
            $fl = $request->get('imageFile')->getData();
            if ($fl) {
                $originalFileName = pathinfo($fl->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFileName = $safeFilename . '.' . $fl->guessExtension();

                // Move the file to the directory
                try {
                    $fl->move(
                        $this->getParameter('category_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $category->setFond($newFileName);
            }
        }


        $categoryRepository->add($category, true);

        return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/Information', name: 'information', methods: ['GET', 'POST'])]
    public function new(Request $request, InformationRepository $informationRepository): Response
    {

        $user = $this->getUser();
        if (!$user) {
            $session = $request->getSession();
            $session->clear();
            return $this->redirectToRoute('app_login');
        }

        if ($informationRepository->find(1)) {
            $information = $informationRepository(1);
        } else {
            $information = new Information();
        }

        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $informationRepository->add($information, true);

            return $this->redirectToRoute('admin_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('information/new.html.twig', [
            'information' => $information,
            'form' => $form,
        ]);
    }

    #[Route('/service/{id}/delete', name: 'delete_item', methods: ['POST'])]
    public function delete_item(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $itemRepository->remove($item, true);
        }
        $this->addFlash('success', 'service supprimé avec succès!');
        return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id}/delete', name: 'delete_category', methods: ['POST'])]
    public function delete_category(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }
        $this->addFlash('success', 'categorie supprimé avec succès!');
        return $this->redirectToRoute('admin_tableau', [], Response::HTTP_SEE_OTHER);
    }
}
