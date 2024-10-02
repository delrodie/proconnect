<?php

namespace App\Twig\Runtime;

use App\Service\AllRepositories;
use Twig\Extension\RuntimeExtensionInterface;

class PrestataireRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
        // Inject dependencies if needed
    }

    public function prestataireRating($value)
    {
        $candidatures = $this->allRepositories->getPrestataireRating($value);
        $total = count($candidatures);
        $somme = $moyenne = 0;

        if ($total > 1){
            foreach ($candidatures as $candidature) {
                $somme += (int) $candidature->getNote();
            }

            $moyenne = (float) $somme / $total;
        }
//        dd($moyenne);

        return $moyenne;
    }

    public function projetRealise($value)
    {
        return $this->allRepositories->getPrestataireRating($value);
    }

    public function prestataireNombreByDomaine($value): int
    {
        $nombre = 0;
        foreach (($this->allRepositories->getOneDomaine($value))->getCategories() as $category) {
            $competence = $this->allRepositories->getOneCompetence($category->getSlug());
            if ($competence){
                $nombre += count($this->allRepositories->getPrestataireByCompetence($competence));
            }

        }

        return $nombre;
    }
}
