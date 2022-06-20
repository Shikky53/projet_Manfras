<?php

namespace App\Controller;

use App\Entity\Scan;
use App\Entity\Chapitre;
use App\Form\ImagesType;
use App\Form\NumeroType;
use App\Form\ChapitreType;
use App\Services\HandleImage;
use App\Repository\ScanRepository;
use App\Repository\MangaRepository;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chapitre')]
class ChapitreController extends AbstractController
{
    #[Route('/', name: 'chapitre_index', methods: ['GET'])]
    public function index(ChapitreRepository $chapitreRepository): Response
    {
        return $this->render('chapitre/index.html.twig', [
            'chapitres' => $chapitreRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'chapitre_new', methods: ['GET', 'POST'])]
    public function new(int $id,Request $request, EntityManagerInterface $entityManager, MangaRepository $mangaRepository): Response
    {
        $manga = $mangaRepository->find($id);
        $chapitre = new Chapitre();
        $chapitre->setManga($manga);
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitre);
            $entityManager->flush();

            return $this->redirectToRoute('manga_show', ['id'=> $manga->getId() ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chapitre/new.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'chapitre_show', methods: ['GET'])]
    public function show(Chapitre $chapitre, ScanRepository $scanRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $firstScan = $scanRepository->findOneBy(['chapitre' => $chapitre]);
        $scans = $paginator->paginate(
            $scanRepository->findBy([
                'chapitre' => $chapitre
            ]),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('chapitre/show.html.twig', [
            'chapitre' => $chapitre,
            'scans' => $scans,
            'firstScan' => $firstScan
        ]);
    }

    #[Route('/{id}/{isScan}/edit', name: 'chapitre_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, string $isScan, ChapitreRepository $chapitreRepository, Request $request, HandleImage $handleImage, EntityManagerInterface $entityManager): Response
    {
        $chapitre = $chapitreRepository->find($id);
        $numeroForm = $this->createForm(NumeroType::class);
        $imageForm = $this->createForm(ImagesType::class);

        $numeroForm->handleRequest($request);
        if ($numeroForm->isSubmitted() && $numeroForm->isValid()) {
            $numero = $numeroForm->get('numero')->getData();
            $chapitre->setNumero($numero);
            $entityManager->persist($chapitre);
            $entityManager->flush();
            return $this->redirectToRoute('chapitre_show', ['id' => $chapitre->getId()], Response::HTTP_SEE_OTHER);
        }
        //Viveleback : mdp teamviewer
        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            
            /** @var UploadedFile $files */
            $files = $imageForm->get('image')->getData();
            
            foreach ($files as $file) {
                if ($file) {
                    $scan = new Scan();
                    $handleImage->save($file, $scan);
                    $chapitre->addScan($scan);
                    $entityManager->persist($scan);
                }
            }
            
            $entityManager->persist($chapitre);
            $entityManager->flush();

            return $this->redirectToRoute('chapitre_show', ['id' => $chapitre->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chapitre/edit.html.twig', [
            'chapitre' => $chapitre,
            'isScan' => $isScan,
            'imageForm' => $imageForm,
            'numeroForm' => $numeroForm,
        ]);
    }

    #[Route('/{id}', name: 'chapitre_delete', methods: ['POST'])]
    public function delete(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapitre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chapitre_index', [], Response::HTTP_SEE_OTHER);
    }
}
