<?php

namespace App\Twig;

use App\Entity\Authors;
use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UtilsExtension extends AbstractExtension
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('Authors', [$this, 'Authors']),
            new TwigFilter('AuthorsAsLink', [$this, 'AuthorsAsLink'], ['is_safe' => ['html']]),
            new TwigFilter('Shares', [$this, 'Shares']),
            new TwigFilter('IsAuthor', [$this, 'IsAuthor']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('Authors', [$this, 'Authors']),
            new TwigFunction('AuthorsAsLink', [$this, 'AuthorsAsLink'], ['is_safe' => ['html']]),
            new TwigFunction('Shares', [$this, 'Shares']),
            new TwigFunction('IsAuthor', [$this, 'IsAuthor']),
        ];
    }

    public function Authors($value)
    {
        $id = $value->getId();
        $pub = $this->em->getRepository(Authors::class)->findBy([
            "publication_id" => $id
        ]);
        
        $res = [];

        foreach($pub as $p) {
            $user = $this->em->getRepository(User::class)->find($p->getAuthorId());
            $res[] = $user->getName();
        }
        return implode(", ", $res);
    }

    public function AuthorsAsLink($value)
    {
        $id = $value->getId();
        $pub = $this->em->getRepository(Authors::class)->findBy([
            "publication_id" => $id
        ]);
        
        $res = [];

        foreach($pub as $p) {
            $user = $this->em->getRepository(User::class)->findOneBy(['id' => $p->getAuthorId()]);
            if($user) {
                $res[] = "<a href='/public/my_publications/".$user->getId()."'>".$user->getName()."</a>";
            }
        }
        return implode(", ", $res);
    }

    public function Shares($value)
    {
        $id = $value->getId();
        $pub = $this->em->getRepository(Authors::class)->findBy([
            "publication_id" => $id
        ]);

        $res = [];

        foreach($pub as $p) {
            $user = $this->em->getRepository(User::class)->find($p->getAuthorId());
            $res[] = $user->getName() . ": " . $p->getShare();
        }
        return implode(", ", $res);
    }

    public function IsAuthor($value)
    {
        $id = $value->getId();
        $pub = $this->em->getRepository(Authors::class)->findOneBy([
            "publication_id" => $id,
            "is_author" => true
        ]);

        if($pub === null) {
            return 0;
        }

        return $pub->getAuthorId();
    }
}
