<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Authors;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'title' => 'Panel administratora',
        ]);
    }

    /**
     * @Route("/edit_user_list", name="editUserList")
     */
    public function editUserList()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('admin/edit_user_list.html.twig', [
            'title' => 'Panel administratora',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/delete_user/{id}", name="deleteUser")
     */
    public function deleteUser(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $id]);
        $auth = $entityManager->getRepository(Authors::class);
        $pub = $entityManager->getRepository(Publication::class);

        if (!$user) {
            return $this->render('error.html.twig', [
                'title' => 'Nie ma takiego użytkownika!',
            ]);
        }

        $auth->deleteByUserId($user->getId());
        $pub->deleteIfNotAuthors();

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->render('success.html.twig', [
            'title' => 'Usunięto ' . $user->getName() . '!',
        ]);
    }

    /**
     * @Route("/delete_publication/{id}", name="deletePublication")
     */
    public function deletePublication(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $publication = $entityManager->getRepository(Publication::class)->find($id);
        $auth = $entityManager->getRepository(Authors::class);

        if (!$publication) {
            return $this->render('error.html.twig', [
                'title' => 'Nie ma publikacji o id ' . $id,
            ]);
        }


        $auth->deleteByPublicationId($id);

        $entityManager->remove($publication);
        $entityManager->flush();

        return $this->render('success.html.twig', [
            'title' => 'Usunięto ' . $publication->getTitle() . '!',
        ]);
    }
}
