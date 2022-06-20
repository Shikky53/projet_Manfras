<?php

namespace App\Controller;

use App\Repository\MangaRepository;
use App\Repository\ChapitreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DateTimeMangaController extends AbstractController
{
    #[Route('/new/manga', name: 'date_time_manga')]
    public function index(MangaRepository $mangaRepository, ChapitreRepository $chapitreRepository): Response
    {
        $recentsMangas = $mangaRepository->findBy([],['debut' => 'DESC']);
        $recentsChapitres = $chapitreRepository->findBy([],['debut' => 'DESC']);

        return $this->render('date_time_manga/index.html.twig', [
            'recentsMangas' => $recentsMangas,
            'recentsChapitres' => $recentsChapitres
        ]);
    }
}
