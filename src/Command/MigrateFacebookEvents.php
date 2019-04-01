<?php

namespace App\Command;

use App\Entity\GeoPoint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrateFacebookEvents extends Command
{
    protected static $defaultName = 'migrate:facebook';

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->container = $container;
        $this->entityManager = $entityManager;
    }


    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $geoPoints = $this->entityManager->getRepository(GeoPoint::class)->findAll();

        foreach ($geoPoints as $geoPoint) {
            if (strpos($geoPoint->getFacebookEvent(), '<a href') !== false) {
                $dom = new \DOMDocument;
                $dom->loadHTML($geoPoint->getFacebookEvent());
                foreach ($dom->getElementsByTagName('a') as $node) {
                    $geoPoint->setFacebookEvent($node->getAttribute('href'));
                }
            }

            $this->entityManager->persist($geoPoint);
        }

        $this->entityManager->flush();

        $io->success('Imported complete');
    }
}
