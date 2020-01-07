<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\User;
use App\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PublicationController extends AbstractController
{
    /**
     * @Route("/add_publication", name="addPublication")
     */
    public function add_publication(Request $request, Security $security)
    {
        $publication = new Publication();
        if ($request->request->get('publication')) {
            $authors = $request->request->get('publication')['authors'];
            $authors = explode(",", $authors);

            $all_exists = true;
            foreach ($authors as $usr) {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                ]);

                if ($user === null) {
                    $all_exists = false;
                } else {
                    $publication->addUsers($user);
                }
            }
            if (!$all_exists) {
                return $this->render('error.html.twig', [
                    'title' => 'Użytkownicy nie istnieją!',
                ]);
            }

            $shares = $request->request->get('publication')['shares'];
            $shares = explode(",", $shares);
            $cnt = 0;
            foreach ($shares as $sh) {
                $s = explode(":", $sh);
                $cnt += $s[1];
            }

            if ($cnt > 100) {
                return $this->render('error.html.twig', [
                    'title' => 'Ilość udziałów przekracza 100%!',
                ]);
            }

            $mag = $request->request->get('publication')['magazine'];
            $conf = $request->request->get('publication')['conference'];
            if (!empty($mag) && !empty($conf)) {
                $publication->setMagazine($mag);
                $publication->setConference(null);
            } elseif (empty($mag) && empty($conf)) {
                return $this->render('error.html.twig', [
                    'title' => 'Należy podać tylko jedną wartość Magazyn/Konferencja!',
                ]);
            }
        }
        $form = $this->createForm(PublicationType::class, $publication);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'publication/add_publication.html.twig',
            array(
                'title' => 'Dodaj publikacje',
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/edit_publication/{id}", name="editPublication")
     */
    public function editPublication(Request $request, int $id, Security $security)
    {
        $publication = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->find($id);
        if (!$publication) {
            return $this->render('error.html.twig', [
                'title' => 'Publikacja nie istnieje!',
            ]);
        }
        $auth = explode(",", $publication->getAuthors());
        if ($auth[0] !== $security->getUser()->getName()) {
            return $this->render('error.html.twig', [
                'title' => 'Nie jesteś autorem!',
            ]);
        }

        if ($request->request->get('publication')) {
            $authors = $request->request->get('publication')['authors'];
            $authors = explode(",", $authors);

            $publication->clearUsers();
            $this->getDoctrine()->getManager()->getConnection()->executeQuery("DELETE FROM publications_list WHERE publication_id = '{$publication->getId()}'");

            $all_exists = true;
            foreach ($authors as $usr) {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                ]);

                if ($user === null) {
                    $all_exists = false;
                } else {
                    $publication->addUsers($user);
                }
            }
            if (!$all_exists) {
                return $this->render('error.html.twig', [
                    'title' => 'Użytkownicy nie istnieją!',
                ]);
            }

            $shares = $request->request->get('publication')['shares'];
            $shares = explode(",", $shares);
            $cnt = 0;
            foreach ($shares as $sh) {
                $s = explode(":", $sh);
                $cnt += $s[1];
            }

            if ($cnt > 100) {
                return $this->render('error.html.twig', [
                    'title' => 'Ilość udziałów przekracza 100%!',
                ]);
            }

            $mag = $request->request->get('publication')['magazine'];
            $conf = $request->request->get('publication')['conference'];
            if (!empty($mag) && !empty($conf)) {
                $publication->setMagazine($mag);
                $publication->setConference(null);
            } elseif (empty($mag) && empty($conf)) {
                return $this->render('error.html.twig', [
                    'title' => 'Należy podać tylko jedną wartość Magazyn/Konferencja!',
                ]);
            }
        }

        // 1) build the form
        $form = $this->createForm(PublicationType::class, $publication);

        // // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render('publication/edit_publication.html.twig', [
            'title' => 'Edytuj publikacje',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view_publication/{id}", name="viewPublication")
     */
    public function viewPublication(Request $request, int $id)
    {
        $publication = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->find($id);
        if (!$publication) {
            return $this->render('error.html.twig', [
                'title' => 'Nie znaleziono publikacji!',
            ]);
        }

        return $this->render('publication/view_publication.html.twig', [
            'title' => $publication->getTitle(),
            'pub' => $publication,
        ]);
    }
}
