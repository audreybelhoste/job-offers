<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Service\JobOffers;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/offer", name="app_offer")
     */
    public function index(JobOffers $jobOffers, OfferRepository $offerRepository, ManagerRegistry $doctrine): Response
    {
        // $jobOffersList = $jobOffers->fetchJobOffers();

        // dd($jobOffersList['resultats'][35]);

        // foreach($jobOffersList['resultats'] as $job){
        //     dd($job);
        // } 

        $dbJobs = $offerRepository->findAll();

        return $this->render('offer/index.html.twig', [
            'dbJobs' => $dbJobs,
        ]);
    }

    /**
     * @Route("/create-offer", name="create_offer")
     */
    public function createOffer(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $offer = new Offer();

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
