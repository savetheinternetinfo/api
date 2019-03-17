<?php

namespace App\Command;

use App\Entity\Language;
use App\Entity\TextKey;
use App\Entity\TranslatedText;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportLanguageCommand extends Command
{
    protected static $defaultName = 'import:language';

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
            ->addArgument('language', InputArgument::REQUIRED, 'Language')
            ->addArgument('filename', InputArgument::REQUIRED, 'Filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $languageKey = $input->getArgument('language');
        if (!$languageKey) {
            $io->note('Please add the language Argument');
            return;
        }

        $filename = $input->getArgument('filename');
        if (!$filename) {
            $io->note('Please add the filename Argument');
            return;
        }

        $fileContent = file_get_contents($this->container->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . $filename);
        $languageData = json_decode($fileContent, true);

        $language = $this->entityManager->getRepository(Language::class)->findOneBy(['code' => $languageKey]);
        if ($language !== null) {
            $this->entityManager->remove($language);
            $this->entityManager->flush();
        }

        $language = new Language();
        $language->setCode($languageKey);
        $this->entityManager->persist($language);
        $this->entityManager->flush();


        $unsortedKeys = $this->entityManager->getRepository(TextKey::class)->findAll();
        $textKeys = [];
        foreach ($unsortedKeys as $textKey) {
            $textKeys[$textKey->getCode()] = $textKey;
        }

        foreach ($languageData as $key => $value) {
            $textKey = $textKeys[$key] ?? (new TextKey())->setCode($key);
            $this->entityManager->persist($textKey);

            $translatedText = (new TranslatedText())->setLanguage($language)->setTextKey($textKey)->setContent($value);
            $this->entityManager->persist($translatedText);
        }
        $this->entityManager->flush();

        $io->success('Imported complete');
    }
}
