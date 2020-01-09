<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Authors;
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
            $pub = $request->request->get('publication');

            $auth1 = $pub['wsauthor_one'];
            $auth2 = $pub['wsauthor_two'];
            $auth3 = $pub['wsauthor_three'];

            $author = $security->getUser();

            $authors = [];
            
            $authOrig = new Authors();
            $authOrig->setAuthorId($author->getId());
            $authOrig->setIsAuthor(true);
            $authOrig->setAuthorName($author->getName());
            $ex = [];
            $cnt = 0;

            $cnt += $pub['author_shares'];

            if($auth1['author'] !== "") {
                $ex[$auth1['author']] = $auth1['share'];
                $cnt += $auth1['share'];
            }
            if($auth2['author'] !== "") {
                $ex[$auth2['author']] = $auth2['share'];
                $cnt += $auth2['share'];
            }
            if($auth3['author'] !== "") {
                $ex[$auth3['author']] = $auth3['share'];
                $cnt += $auth3['share'];
            }

            if ($cnt > 100) {
                return $this->render('error.html.twig', [
                    'title' => 'Ilość udziałów przekracza 100%!',
                ]);
            }

            $all_exists = true;
            foreach ($ex as $usr => $sh) {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                ]);

                if ($user === null) {
                    $all_exists = false;
                }
            }
            if (!$all_exists) {
                return $this->render('error.html.twig', [
                    'title' => 'Użytkownicy nie istnieją!',
                ]);
            }

            $mag = $pub['magazine'];
            $conf = $pub['conference'];
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
            
            foreach ($ex as $usr => $sh) {
                $user = $this->getDoctrine()
                ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                    ]);
                
                $auth = new Authors();
                $auth->setAuthorId($user->getId());
                $auth->setPublicationId($publication->getId());
                $auth->setShare($sh);
                $auth->setIsAuthor(false);
                $auth->setAuthorName($user->getName());
                $entityManager->persist($auth);
            }
            
            $authOrig->setPublicationId($publication->getId());
            $authOrig->setShare($pub['author_shares']);
            
            $entityManager->persist($authOrig);

            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'publication/add_publication.html.twig',
            [
                'title' => 'Dodaj publikacje',
                'form' => $form->createView(),
            ]
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
        $authRepo = $this->getDoctrine()
            ->getRepository(Authors::class);
        $userRepo = $this->getDoctrine()
            ->getRepository(User::class);
        $pubAuth = $authRepo->findBy([
            'publication_id' => $id,
            'is_author' => false
        ]);
        $authOrig = $authRepo->findOneBy([
            'publication_id' => $id,
            'is_author' => true
        ]);
        $userOrig = $userRepo->findOneBy(['id' => $authOrig->getAuthorId()]);

        if (!$publication) {
            return $this->render('error.html.twig', [
                'title' => 'Publikacja nie istnieje!',
            ]);
        }

        $data = [
            'author_shares' => $authRepo->findOneBy(['is_author' => true])->getShare(),
            'wsauthor_one' => [
                'author' => $pubAuth ? (isset($pubAuth[0]) ? ($userRepo->findOneBy(['id' => $pubAuth[0]->getAuthorId()])->getName()) : null ) : null,
                'share' => $pubAuth ? (isset($pubAuth[0]) ? $pubAuth[0]->getShare() : null) : null
            ],
            'wsauthor_two' => [
                'author' => $pubAuth ? (isset($pubAuth[1]) ? ($userRepo->findOneBy(['id' => $pubAuth[1]->getAuthorId()])->getName()) : null ) : null,
                'share' => $pubAuth ? (isset($pubAuth[1]) ? $pubAuth[1]->getShare() : null) : null
            ],
            'wsauthor_three' => [
                'author' => $pubAuth ? (isset($pubAuth[2]) ? ($userRepo->findOneBy(['id' => $pubAuth[2]->getAuthorId()])->getName()) : null ) : null,
                'share' => $pubAuth ? (isset($pubAuth[2]) ? $pubAuth[2]->getShare() : null) : null
            ],
        ];

        if ($request->request->get('publication')) {

            $pub = $request->request->get('publication');

            if($pub['author_shares']) {
                $data['author_shares'] = $pub['author_shares'];
            }

            $auth1 = $pub['wsauthor_one'];
            $auth2 = $pub['wsauthor_two'];
            $auth3 = $pub['wsauthor_three'];

            $ex = [];
            $cnt = 0;

            $cnt += $pub['author_shares'];

            if($auth1['author'] !== "") {
                $data['wsauthor_one'] = [
                    'author' => $auth1['author'],
                    'share' => $auth1['share']
                ];
                $ex[$auth1['author']] = $auth1['share'];
                $cnt += $auth1['share'];
            }
            if($auth2['author'] !== "") {
                $data['wsauthor_two'] = [
                    'author' => $auth2['author'],
                    'share' => $auth2['share']
                ];
                $ex[$auth2['author']] = $auth2['share'];
                $cnt += $auth2['share'];
            }
            if($auth3['author'] !== "") {
                $data['wsauthor_three'] = [
                    'author' => $auth3['author'],
                    'share' => $auth3['share']
                ];
                $ex[$auth3['author']] = $auth3['share'];
                $cnt += $auth3['share'];
            }

            if ($cnt > 100) {
                return $this->render('error.html.twig', [
                    'title' => 'Ilość udziałów przekracza 100%!',
                ]);
            }

            // $authRepo->deleteByPublicationIdWithoutAuthor($id);

            $all_exists = true;
            foreach ($ex as $usr => $sh) {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                ]);

                if ($user === null) {
                    $all_exists = false;
                }
            }
            if (!$all_exists) {
                return $this->render('error.html.twig', [
                    'title' => 'Użytkownicy nie istnieją!',
                ]);
            }

            $mag = $pub['magazine'];
            $conf = $pub['conference'];
            if (!empty($mag) && !empty($conf)) {
                $publication->setMagazine($mag);
                $publication->setConference(null);
            } elseif (empty($mag) && empty($conf)) {
                return $this->render('error.html.twig', [
                    'title' => 'Należy podać tylko jedną wartość Magazyn/Konferencja!',
                ]);
            }
        }

        $form = $this->createForm(PublicationType::class, $publication, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            $authRepo->deleteByPublicationIdWithoutAuthor($id);
            $entityManager->flush();

            $authOrig->setPublicationId($id);
            $authOrig->setShare($pub['author_shares']);
            $authOrig->setAuthorId($userOrig->getId());
            $authOrig->setIsAuthor(true);
            $authOrig->setAuthorName($userOrig->getName());
            $entityManager->persist($authOrig);
            $entityManager->flush();

            foreach ($ex as $usr => $sh) {
                $user = $this->getDoctrine()
                ->getRepository(User::class)->findOneBy([
                    "name" => $usr,
                    ]);
                
                $auth = new Authors();
                $auth->setAuthorId($user->getId());
                $auth->setPublicationId($publication->getId());
                $auth->setShare($sh);
                $auth->setIsAuthor(false);
                $auth->setAuthorName($user->getName());
                $entityManager->persist($auth);
                $entityManager->flush();
            }

            $entityManager->flush();

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
