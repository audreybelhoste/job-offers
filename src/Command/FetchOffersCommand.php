<?php

namespace App\Command;

use App\Entity\Offer;
use App\Service\JobOffers;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FetchOffersCommand extends Command
{
    protected static $defaultName = 'job:fetch-offers';
    protected static $defaultDescription = 'Fetch offers from API.';

    public function __construct(ManagerRegistry $doctrine, JobOffers $jobOffers)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->jobOffers = $jobOffers;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->doctrine->getManager();
        $offerRepository = $this->doctrine->getRepository(Offer::class);
        
        $jobOffersList = $this->jobOffers->fetchJobOffers();
        $section = $output->section();

        foreach($jobOffersList['resultats'] as $job){
            if($offerRepository->findOneByDistantId($job['id'])){

                $oldJob = $offerRepository->findOneByDistantId($job['id']);

                if($oldJob->getUpdatedAt() < new DateTime($job['dateActualisation'], new DateTimeZone('Europe/Paris'))){
                    $oldJob->setDistantId($job['id']);
                    $oldJob->setName($job['intitule']);
                    $oldJob->setDescription($job['description']);
                    $oldJob->setUrl($job['origineOffre']['urlOrigine']);
                    $oldJob->setUpdatedAt(new DateTime('now' , new DateTimeZone('Europe/Paris')));
    
                    $entityManager->flush();

                    $message = ($job['natureContrat']??'unknow') . ' ' . ($job['entreprise']['nom']??'unknow') . ' modifié.';
                    $section->writeln($message);
                }

            } else {
                $offer = new Offer();
                $offer->setDistantId($job['id']);
                $offer->setName($job['intitule']);
                $offer->setDescription($job['description']);
                $offer->setUrl($job['origineOffre']['urlOrigine']);

                $entityManager->persist($offer); 
                $entityManager->flush();


                $message = ($job['natureContrat']??'unknow') . ' ' . ($job['entreprise']['nom']??'unknow') . ' ajouté.';
                $section->writeln($message);
            }
        } 

        return Command::SUCCESS;
    }
}
