<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Repository\ChapitreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailsMangaController extends AbstractController
{
    #[Route('/details/manga/{id}', name: 'details_manga', methods: ['GET'])]
    public function index(Manga $manga, ChapitreRepository $chapitreRepository): Response
    {
        $chapitres = $chapitreRepository->findBy([
            'manga' => $manga
        ]);

        return $this->render('details_manga/index.html.twig', [
            'manga' => $manga,
            'chapitres' => $chapitres
        ]);
    }
}
