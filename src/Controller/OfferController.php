<?php

namespace App\Controller;

use App\Service\JobOffers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/offer", name="app_offer")
     */
    public function index(JobOffers $jobOffers): Response
    {
        $jobOffersList = $jobOffers->fetchJobOffers();
        dd($jobOffersList);

        return $this->render('offer/index.html.twig', [
            'controller_name' => 'OfferController',
        ]);
    }
}
