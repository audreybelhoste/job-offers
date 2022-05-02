<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/", name="app_offer")
     */
    public function index(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findAll();

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/create-offer", name="create_offer")
     */
    public function createOffer(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $offer = new Offer();

        //TODO Add form contraints and validations

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'offre a été ajoutée'
            );

            return $this->redirectToRoute('app_offer');
        }

        return $this->render('offer/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
