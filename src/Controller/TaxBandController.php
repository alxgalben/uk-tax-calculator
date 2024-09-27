<?php

namespace App\Controller;

use App\Entity\TaxBand;
use App\Form\TaxBandType;
use App\Repository\TaxBandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Log\LoggerInterface;

#[Route('/admin/tax-band')]
class TaxBandController extends AbstractController
{
    #[Route('/', name: 'tax_band_index', methods: ['GET'])]
    public function index(TaxBandRepository $taxBandRepository, CacheInterface $cache, LoggerInterface $logger): Response
    {
        try {
            // try to get tax bands from cache
            $response = $cache->get('tax_band_list_response', function (ItemInterface $item) use ($taxBandRepository) {
                $item->expiresAfter(3600);

                $taxBands = $taxBandRepository->findAll();

                return $this->render('tax_band/index.html.twig', [
                    'tax_bands' => $taxBands,
                ]);
            });

            return $response;
        } catch (\Throwable $e) {
            $logger->error('Failed to retrieve tax bands from cache: ' . $e->getMessage());

            // fetch tax bands directly from the database and display them without cache
            $this->addFlash('error', 'Failed to load cached tax bands.');
            $taxBands = $taxBandRepository->findAll();

            return $this->render('tax_band/index.html.twig', [
                'tax_bands' => $taxBands,
            ]);
        }
    }

    #[Route('/new', name: 'tax_band_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taxBand = new TaxBand();
        $form = $this->createForm(TaxBandType::class, $taxBand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($taxBand);
            $entityManager->flush();

            $this->addFlash('success', 'New tax band added successfully.');

            return $this->redirectToRoute('tax_band_index');
        }

        return $this->render('tax_band/new.html.twig', [
            'form' => $form->createView(),
            'tax_band' => $taxBand,
        ]);
    }

    #[Route('/{id}/edit', name: 'tax_band_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaxBand $taxBand, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaxBandType::class, $taxBand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tax band updated successfully.');

            return $this->redirectToRoute('tax_band_index');
        }

        return $this->render('tax_band/edit.html.twig', [
            'form' => $form->createView(),
            'tax_band' => $taxBand,
        ]);
    }

    #[Route('/{id}/delete', name: 'tax_band_delete', methods: ['POST'])]
    public function delete(Request $request, TaxBand $taxBand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxBand->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($taxBand);
                $entityManager->flush();

                $this->addFlash('success', 'Tax band deleted successfully.');
            } catch (\Throwable $e) {
                $this->addFlash('error', 'Failed to delete the tax band.');
            }
        }

        return $this->redirectToRoute('tax_band_index');
    }
}
