<?php

namespace App\Controller;

use App\Repository\MangaRepository;
use App\Repository\GenresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenresPageController extends AbstractController
{
    #[Route('/genres/page', name: 'genres_page')]
    public function index(MangaRepository $mangaRepository, GenresRepository $genresRepository): Response
    {
        $AllMangas = [];
        $AllGenresName = [];
        $mangas = $mangaRepository->findAll();
        $genres = $genresRepository->findAll('name');

        for ($i=0; $i < count($genres); $i++) { 
            array_push($AllGenresName, $genres[$i]->getName());
        }

        for ($i=0; $i < count($AllGenresName); $i++) { 
            
            $filteredMangas = $this->filteredByGenres($AllGenresName[$i], $mangas);

            array_push($AllMangas, $filteredMangas);
        }

        return $this->render('genres_page/index.html.twig', [
            'mangas' => $AllMangas,
            'genres' => $AllGenresName,
        ]);
    }

    public function filteredByGenres(string $genresName, $mangas)
    {
        $genres = [];

        for ($i=0; $i < count($mangas); $i++) {
            
            $currentManga = $mangas[$i]->getGenres()->getName();

            if ($currentManga === $genresName)
               array_push($genres, $mangas[$i]);

        }

        return $genres;
    }
}
