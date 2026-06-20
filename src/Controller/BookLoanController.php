<?php

namespace App\Controller;

use App\Entity\BookLoan;
use App\Form\BookLoanType;
use App\Repository\BookLoanRepository;
use App\Repository\SubjectRepository;
use App\Controller\Trait\CsrfProtectionTrait;
use App\Service\BookLoanService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book/loan')]
#[IsGranted('ROLE_ADMIN')]
final class BookLoanController extends AbstractController
{
    use CsrfProtectionTrait;

    #[Route(name: 'app_book_loan_index', methods: ['GET'])]
    public function index(
        Request $request,
        BookLoanRepository $bookLoanRepository,
        SubjectRepository $subjectRepository,
        PaginatorInterface $paginator,
    ): Response {
        $searchQuery = $request->query->get('q');
        $status = $request->query->get('status');
        $subjectId = filter_var(
            $request->query->get('subject'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]],
        ) ?: null;

        $queryBuilder = $bookLoanRepository->searchAndFilter($searchQuery, $status, $subjectId);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('book_loan/index.html.twig', [
            'pagination' => $pagination,
            'search_query' => $searchQuery,
            'current_status' => $status,
            'current_subject' => $subjectId,
            'subjects' => $subjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_loan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookLoanService $bookLoanService): Response
    {
        $bookLoan = new BookLoan();
        $form = $this->createForm(BookLoanType::class, $bookLoan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookLoanService->create($bookLoan);

            return $this->redirectToRoute('app_book_loan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_loan/new.html.twig', [
            'book_loan' => $bookLoan,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_book_loan_show', methods: ['GET'])]
    public function show(BookLoan $bookLoan): Response
    {
        return $this->render('book_loan/show.html.twig', [
            'book_loan' => $bookLoan,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_book_loan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookLoan $bookLoan, BookLoanService $bookLoanService): Response
    {
        $form = $this->createForm(BookLoanType::class, $bookLoan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookLoanService->update($bookLoan);

            return $this->redirectToRoute('app_book_loan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_loan/edit.html.twig', [
            'book_loan' => $bookLoan,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_book_loan_delete', methods: ['POST'])]
    public function delete(Request $request, BookLoan $bookLoan, BookLoanService $bookLoanService): Response
    {
        if ($this->isCsrfTokenValidOrFlash('delete' . $bookLoan->getId(), $request)) {
            $bookLoanService->delete($bookLoan);
        }

        return $this->redirectToRoute('app_book_loan_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id<\d+>}/return', name: 'app_book_loan_return', methods: ['POST'])]
    public function returnBook(Request $request, BookLoan $bookLoan, BookLoanService $bookLoanService): Response
    {
        $redirectRoute = str_contains((string) $request->headers->get('referer'), '/book/loan/' . $bookLoan->getId())
            ? 'app_book_loan_show'
            : 'app_book_loan_index';
        $redirectParams = $redirectRoute === 'app_book_loan_show' ? ['id' => $bookLoan->getId()] : [];

        if (!$this->isCsrfTokenValidOrFlash('return' . $bookLoan->getId(), $request)) {
            return $this->redirectToRoute($redirectRoute, $redirectParams, Response::HTTP_SEE_OTHER);
        }

        try {
            $bookLoanService->markAsReturned($bookLoan);
            $this->addFlash('success', 'بازگشت کتاب با موفقیت در سیستم ثبت شد.');
        } catch (\LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute($redirectRoute, $redirectParams, Response::HTTP_SEE_OTHER);
    }
}
