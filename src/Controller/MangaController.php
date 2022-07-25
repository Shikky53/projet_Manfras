<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Form\MangaType;
use App\Entity\Chapitre;
use App\Services\HandleImage;
use App\Repository\ScanRepository;
use App\Repository\MangaRepository;
use App\Repository\ChapitreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/manga')]
class MangaController extends AbstractController
{
    #[Route('/u{uId}/', name: 'manga_index', methods: ['GET'])]
    public function index(int $uId, MangaRepository $mangaRepository, UserRepository $userRepository): Response
    {

        $user = $userRepository->find($uId);

        dump($user);

        $mangas = $mangaRepository->findBy([
            'user' => $user
        ]);

        return $this->render('manga/index.html.twig', [
            'user' => $user,
            'mangas' => $mangas,
            'AllMangas' => $mangaRepository->findAll(),
        ]);
    }

    #[Route('{uId}/new', name: 'manga_new', methods: ['GET', 'POST'])]
    public function new(int $uId, Request $request, EntityManagerInterface $entityManager, HandleImage $handleImage, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($uId);
        $manga = new Manga();
        $manga->setUser($user);
        $form = $this->createForm(MangaType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                $handleImage->save($file, $manga);
            }

            $entityManager->persist($manga);
            $entityManager->flush();

            return $this->redirectToRoute('manga_index', ['uId' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manga/new.html.twig', [
            'manga' => $manga,
            'form' => $form,
        ]);
    }

    #[Route('{id}/u{uId}', name: 'manga_show', methods: ['GET'])]
    public function show(int $uId, int $id, MangaRepository $mangaRepository, ChapitreRepository $chapitreRepository,PaginatorInterface $paginator, Request $request, UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->find($uId);
        $manga = $mangaRepository->find($id);
        
        $chapitres = $paginator->paginate(
             $chapitreRepository->findBy([
            'manga' => $manga
        ]),
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
    

        return $this->render('manga/show.html.twig', [
            'user' => $user,
            'manga' => $manga,
            'chapitres' => $chapitres,
        ]);
    }

    #[Route('/{id}/{uId}/edit', name: 'manga_edit', methods: ['GET', 'POST'])]
    public function edit(int $uId, Request $request, Manga $manga, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($uId);

        $form = $this->createForm(MangaType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('manga_show', ['uId' => $user->getId(),'id'=> $manga->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manga/edit.html.twig', [
            'manga' => $manga,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'manga_delete', methods: ['POST'])]
    public function delete(int $uId, Request $request, Manga $manga, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($uId);

        if ($this->isCsrfTokenValid('delete' . $manga->getId(), $request->request->get('_token'))) {
            $entityManager->remove($manga);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manga_index', ['uId' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
}
