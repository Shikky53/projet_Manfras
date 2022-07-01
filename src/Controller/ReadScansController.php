<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Entity\Chapitre;
use App\Repository\ScanRepository;
use App\Repository\ChapitreRepository;
use App\Repository\MangaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ReadScansController extends AbstractController
{
    #[Route('/read/scans/{id}', name: 'read_scans')]
    public function index(int $id, ChapitreRepository $chapitreRepository, ScanRepository $scanRepository): Response
    {

        $chapitresDropdown = $chapitreRepository->findBy([

            'manga' => $chapitreRepository->find($id)->getManga()

        ]);

        $scans = $scanRepository->findBy([
            
            'chapitre' => $chapitreRepository->find($id)

        ]);

        return $this->render('read_scans/index.html.twig', [
            'chapitresDropdown' => $chapitresDropdown,
            'scans' => $scans
        ]);
    }
}