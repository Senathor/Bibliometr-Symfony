<?php

namespace App\Twig;

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
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('AuthorsAsLinks', [$this, 'AuthorsAsLinks']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('AuthorsAsLinks', [$this, 'AuthorsAsLinks']),
        ];
    }

    public function AuthorsAsLinks($value)
    {
        $auth = explode(",", $value);
        $res = [];
        foreach ($auth as $usr) {
            $user = $this->em->getRepository(User::class)->findOneBy([
                "name" => $usr,
            ]);
            $res[] = '<a href="my_publications/' . $user->getId() . '">' . $user->getName() . '</a>';
            // $res[] = [$usr => $user->getId()];
        }
        return $res;
        // return implode(", ", $res);
    }
}
