<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/my_publications/{id}", name="myPublications")
     */
    public function myPublications(int $id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $publications = $user->getPublications()->getValues();

        foreach ($publications as $obj) {
            dump($obj);
        }

        return $this->render('profile/my_publications.html.twig', [
            'title' => 'Moje publikacje',
            'publications' => $publications,
        ]);
    }

    /**
     * @Route("/edit_profile/{id}", name="editProfile")
     */
    public function editProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder, int $id, Security $security)
    {
        // 1) build the form
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $edit_profile = false;
        if ($user) {
            $edit_profile = true;

            if ($security->getUser()->getRole() === "user" && $security->getUser()->getId() !== $user->getId()) {
                return $this->render('error.html.twig', [
                    'title' => 'Nie możesz edytować profilu innych użytkowników!',
                ]);
            }

        } else {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user, [
            'show_role' => ($security->getUser()->getRole() === "admin"),
            'role' => $user->getRole(),
            'edit_profile' => $edit_profile,
        ]);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRole($user->getRole());

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'title' => 'Edytuj moje dane',
            'form' => $form->createView(),
        ]);
    }
}
