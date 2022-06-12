<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Entity\Manga;
use App\Repository\MangaRepository;
use App\Repository\EditeurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(MangaRepository $mangaRepository, EditeurRepository $editeurRepository): Response
    {
        $AllMangas = [];
        $AllEditeursNom = [];
        $mangas = $mangaRepository->findAll();
        $editeur = $editeurRepository->findAll('nom');
        
        for ($i=0; $i < count($editeur); $i++) { 
            array_push($AllEditeursNom, $editeur[$i]->getNom());
            // dump('Ceci est tout mon tableau avec tout les noms d\'éditeurs', $AllEditeursNom);
        }
        
        for ($i=0; $i < count($AllEditeursNom); $i++) { 

           $filteredMangas = $this->filterByEditeur($AllEditeursNom[$i],$mangas,$editeurRepository);

            // dump('Voici le noms de tout mes editeurs',$this->filterByEditeur($AllEditeursNom[$i],$mangas,$editeurRepository));

            array_push($AllMangas, $filteredMangas);

            dump('Ceci sont tout mes Mangas  Bordel' , $AllMangas);
        }

        return $this->render('home/index.html.twig', [
            'mangas' => $AllMangas,
            'editeurs' => $AllEditeursNom,
        ]);
    }

    public function filterByEditeur(string $editeurNom, $mangas, EditeurRepository $editeurRepository)
    {
        

        $editeur = [];

        // dd($editeurRepository->findAll());
        
        for ($i=0; $i < count($mangas); $i++) {  

            $currentManga = $mangas[$i]->getEditeur()->getNom();

            if ($currentManga === $editeurNom) 
                array_push($editeur, $mangas[$i]);
        }

        return $editeur;
    }
}
