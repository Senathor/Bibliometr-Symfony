<?php
namespace App\Controller;

use App\Entity\Publication;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $publications = $this->getDoctrine()
            ->getRepository(Publication::class)
            ->findAll();
        $pub = new Publication();

        $form = $this->createForm(SearchType::class, $pub, ['method' => "POST"]);

        $cond = null;

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $o = (object) [];

            $search = $request->request->get('search');
            $title = $search['title'];
            $authors = $search['authors'];
            $shares = $search['shares'];
            $points = $search['points'];
            $magazine = $search['magazine'];
            $conference = $search['conference'];
            $url = $search['url'];
            $data_od = $search['data_od'];
            $data_do = $search['data_do'];
            $sort = $search['sort'];
            $order = $search['order'];
            $nazwabox = empty($search['nazwabox']) ? null : $search['nazwabox'];
            $authorbox = empty($search['authorbox']) ? null : $search['authorbox'];
            $sharesbox = empty($search['sharesbox']) ? null : $search['sharesbox'];
            $databox = empty($search['databox']) ? null : $search['databox'];
            $punktybox = empty($search['punktybox']) ? null : $search['punktybox'];
            $magazinebox = empty($search['magazinebox']) ? null : $search['magazinebox'];
            $conferencebox = empty($search['conferencebox']) ? null : $search['conferencebox'];
            $urlbox = empty($search['urlbox']) ? null : $search['urlbox'];
            $select = empty($search['pubid']) ? null : $search['pubid'];

            $tableNames = [];
            $nazwabox ? array_push($tableNames, ["getTitle" => "Tytuł publikacji"]) : "";
            $authorbox ? array_push($tableNames, ["getAuthors" => "Autorzy"]) : "";
            $sharesbox ? array_push($tableNames, ["getShares" => "Udziały"]) : "";
            $databox ? array_push($tableNames, ["getTimezone" => "Data publikacji"]) : "";
            $punktybox ? array_push($tableNames, ["getPoints" => "Punkty ministerialne"]) : "";
            $magazinebox ? array_push($tableNames, ["getMagazine" => "Czasopismo"]) : "";
            $conferencebox ? array_push($tableNames, ["getConference" => "Konferencja"]) : "";
            $urlbox ? array_push($tableNames, ["getUrl" => "URL/DOI"]) : "";

            $title ? ($o->title = $title) : null;
            $authors ? ($o->authors = $authors) : null;
            $shares ? ($o->shares = $shares) : null;
            $points ? ($o->points = $points) : null;
            $magazine ? ($o->magazine = $magazine) : null;
            $conference ? ($o->conference = $conference) : null;
            $url ? ($o->url = $url) : null;
            $data_od ? ($o->data_od = $data_od) : null;
            $data_do ? ($o->data_do = $data_do) : null;
            $sort ? ($o->sort = $sort) : null;
            $order ? ($o->order = $order) : null;

            $ids = $request->request->get('pubid');
            if ($ids) {
                $o->ids = $ids;
            }
            $pubs = $this->getDoctrine()
                ->getRepository(Publication::class)
                ->findByCrit($o);
            if (!$pubs) {
                return $this->render('error.html.twig', [
                    'title' => "Nie znaleziono publikacji!",
                ]);
            }

            if ($form->get('export')->isClicked()) {
                $tableStyle = [
                    'borderColor' => "000000",
                    'borderSize' => 2,
                    'layout' => 'autofit',
                    // 'cellSpacing' => 15
                    'bidiVisual' => false,
                    'alignment' => 'left',
                ];
                $cellStyle = [
                    'valign' => 'center',
                ];
                $cellTextStyle = [
                    'bold' => true,
                    'allCaps' => true,
                ];
                $header = array('size' => 48, 'bold' => true, 'center' => true, 'vAlign' => 'both');
                $sectionStyle = [
                    'marginLeft' => 25,
                    'marginRight' => 25,
                ];

                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection($sectionStyle);
                $section->addText(htmlspecialchars('Bibliometr'), $header);
                $tableR = $section->addTable($tableStyle);

                $pubName = "publication_" . $pubs[0]->getTitle();

                $tableNames = array_reverse($tableNames);

                $tableR->addRow(900);
                foreach ($tableNames as $nvm => $tableBox) {
                    foreach ($tableBox as $boxs => $fullName) {
                        $tableR->addCell(null, $cellStyle)->addText(htmlspecialchars($fullName), $cellTextStyle);
                    }
                }

                foreach ($pubs as $pub) {
                    $tableR->addRow();
                    foreach ($tableNames as $nvm => $tableBox) {
                        foreach ($tableBox as $boxs => $fullName) {
                            $val = $pub->{$boxs}();
                            $tableR->addCell()->addText(htmlspecialchars($val));
                        }
                    }
                }

                $file = $pubName . '.docx';
                header("Content-Description: File Transfer");
                header('Content-Disposition: attachment; filename="' . $file . '"');
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Expires: 0');
                $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $xmlWriter->save("php://output");

            }

            if ($form->get('search')->isClicked()) {
                return $this->render('home.html.twig', [
                    'title' => "Bilbliometr",
                    'publications' => $pubs,
                    'form' => $form->createView(),
                    'box' => true,
                    'nazwabox' => $nazwabox,
                    'authorbox' => $authorbox,
                    'sharesbox' => $sharesbox,
                    'databox' => $databox,
                    'punktybox' => $punktybox,
                    'magazinebox' => $magazinebox,
                    'conferencebox' => $conferencebox,
                    'urlbox' => $urlbox,
                ]);
            }
        }

        return $this->render('home.html.twig', [
            'title' => "Bilbliometr",
            'publications' => $publications,
            'form' => $form->createView(),
            'box' => false,
        ]);
    }
}
