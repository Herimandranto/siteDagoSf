<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Form\ItemType;
use App\Form\UserType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\SloganRepository;
use Symfony\Component\Form\FormError;
use App\Repository\CategoryRepository;
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
    public function index(): Response
    {

        $user = $this->getUser();
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
        UserInterface $user,
        SluggerInterface $slugger,
        ManagerRegistry $doctrine
    ): Response {
        $user = $this->getUser();
        $em = $doctrine->getManager();
        $slogan1 = $sloganRepository->find(1);
        // Slogan form

        if ($request->get('slogan')) {
            $slogan1->setTitle($request->get('slogan'));
            $em->persist($slogan1);
            $em->flush();

            return $this->redirectToRoute('admin_profil', [], Response::HTTP_SEE_OTHER);
        }

        //      Update password

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
            'slogan' => $slogan1,
            'userform' => $userform->createView()
        ]);
    }






    #[Route('/tableau', name: 'admin_tableau', methods: ['GET', 'POST'])]
    public function show(
        Request $request,
        ItemRepository $itemRepository,
        CategoryRepository $categoryRepository,
        ManagerRegistry $doctrine
    ): Response {

        $user = $this->getUser();

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
}
