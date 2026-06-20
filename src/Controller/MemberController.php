<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\BookLoanRepository;
use App\Repository\MemberRepository;
use App\Controller\Trait\CsrfProtectionTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/member')]
#[IsGranted('ROLE_ADMIN')]
final class MemberController extends AbstractController
{
    use CsrfProtectionTrait;

    #[Route(name: 'app_member_index', methods: ['GET'])]
    public function index(Request $request, MemberRepository $memberRepository, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->getString('q');

        $pagination = $paginator->paginate(
            $memberRepository->createSearchQueryBuilder($searchQuery),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('member/index.html.twig', [
            'pagination' => $pagination,
            'search_query' => $searchQuery,
        ]);
    }

    #[Route('/new', name: 'app_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_member_show', methods: ['GET'])]
    public function show(Member $member, BookLoanRepository $bookLoanRepository): Response
    {
        $loanHistory = $bookLoanRepository->findMemberHistory($member->getId());

        return $this->render('member/show.html.twig', [
            'member' => $member,
            'loan_history' => $loanHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValidOrFlash('delete' . $member->getId(), $request)) {
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
