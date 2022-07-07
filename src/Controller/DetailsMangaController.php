<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Entity\Chapitre;
use App\Repository\ChapitreRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DetailsMangaController extends AbstractController
{
    #[Route('/details/manga/{id}', name: 'details_manga')]
    public function index(Manga $manga, ChapitreRepository $chapitreRepository): Response
    {
        
        $chapitres = $chapitreRepository->findBy([
            'manga' => $manga
        ]);

        if($chapitres != null)
        {
            $premierChapitre = array_values($chapitres)[0];
            $dernierChapitre = end($chapitres);
        }
        else
        {
            $premierChapitre = "";
            $dernierChapitre = "";
        }

        return $this->render('details_manga/index.html.twig', [
            'manga' => $manga,
            'chapitres' => $chapitres,
            'premierChapitre' => $premierChapitre,
            'dernierChapitre' => $dernierChapitre
        ]);
    }
}