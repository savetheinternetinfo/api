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

class ImportMapCommand extends Command
{
    protected static $defaultName = 'import:map';

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
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'Filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $filename = $input->getArgument('filename');
        if (!$filename) {
            $io->note('Please add the filename Argument');
            return;
        }

        $fileContent = file_get_contents($this->container->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . $filename);
        $mapData = json_decode($fileContent, true);

        foreach ($mapData['features'] as $feature) {
            $geoPoint = new GeoPoint();
            $geoPoint->setStiEvent(false);

            foreach ($feature['properties'] as $property) {
                switch ($property['fa_icon']) {
                    case 'fa-map-marker':
                        $geoPoint->setLocation($property['value']);
                        break;
                    case 'fa-clock-o':
                        $geoPoint->setTime(new \DateTime($property['value']));
                        break;
                    case 'fb_event':
                        $geoPoint->setFacebookEvent($property['value']);
                        break;

                }
            }

            if ($feature['STIDemo'] === true) {
                $geoPoint->setStiEvent(true);
            }

            $geoPoint->setLatitude($feature['geometry']['coordinates'][0]);
            $geoPoint->setLongitude($feature['geometry']['coordinates'][1]);

            $this->entityManager->persist($geoPoint);
        }

        $this->entityManager->flush();

        $io->success('Imported complete');
    }
}
