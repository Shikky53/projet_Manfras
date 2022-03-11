<?php

namespace App\Controller;

use App\Entity\Scan;
use App\Form\ScanType;
use App\Services\HandleImage;
use App\Repository\ScanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/scan')]
class ScanController extends AbstractController
{
    #[Route('/', name: 'scan_index', methods: ['GET'])]
    public function index(ScanRepository $scanRepository): Response
    {
        return $this->render('scan/index.html.twig', [
            'scans' => $scanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'scan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, HandleImage $handleImage): Response
    {
        $scan = new Scan();
        $form = $this->createForm(ScanType::class, $scan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get('scan')->getData();
           
            if ($file) {
                $handleImage->save($file, $scan);
            }

            $entityManager->persist($scan);
            $entityManager->flush();

            return $this->redirectToRoute('scan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scan/new.html.twig', [
            'scan' => $scan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'scan_show', methods: ['GET'])]
    public function show(Scan $scan): Response
    {
        return $this->render('scan/show.html.twig', [
            'scan' => $scan,
        ]);
    }

    #[Route('/{id}/edit', name: 'scan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scan $scan, EntityManagerInterface $entityManager, HandleImage $handleImage): Response
    {
        $oldImage = $scan->getImage();
        $form = $this->createForm(ScanType::class, $scan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form->get('scan')->getData();

            if ($file){
                $handleImage->edit($file,$scan,$oldImage);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('scan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scan/edit.html.twig', [
            'scan' => $scan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'scan_delete', methods: ['POST'])]
    public function delete(Request $request, Scan $scan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($scan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('scan_index', [], Response::HTTP_SEE_OTHER);
    }
}
