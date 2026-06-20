<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Controller\Trait\CsrfProtectionTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/publisher')]
#[IsGranted('ROLE_ADMIN')]
final class PublisherController extends AbstractController
{
    use CsrfProtectionTrait;

    #[Route(name: 'app_publisher_index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publisherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publisher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publisher_show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('publisher/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publisher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publisher_delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValidOrFlash('delete' . $publisher->getId(), $request)) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
    }
}
