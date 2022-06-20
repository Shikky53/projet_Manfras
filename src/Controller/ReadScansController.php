<?php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Repository\ScanRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadScansController extends AbstractController
{
    #[Route('/read/scans/', name: 'read_scans')]
    public function index(): Response
    {
        // $scans = $scanRepository->findBy([
        //     'chapitre' => $chapitre
        // ]);


        return $this->render('read_scans/index.html.twig', [
            // 'scans' => $scans,
        ]);
    }
}
