<?php

namespace App\Controller;

use App\Entity\GeoPoint;
use App\Entity\PressRelease;
use App\Entity\Supporter;
use App\Entity\TranslatedText;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public const API_NODES = [
        'points' => 'getGeoPoints',
        'translation' => 'getLanguageData',
        'press' => 'getPressReleases',
        'supporter' => 'getSupporters',
    ];


    /**
     * @Route("/api/{$nodes}", name="api")
     * @param string $nodes
     * @return JsonResponse
     */
    public function apiAction(string $nodes): JsonResponse
    {
        $nodeArray = explode(',', $nodes);
        $nodeArray = array_keys(array_flip($nodeArray));

        $responseData = [];
        foreach ($nodeArray as $nodeName) {
            if (!isset(self::API_NODES[$nodeName])) {
                continue;
            }

            $functionName = self::API_NODES[$nodeName];

            $responseData[$nodeName] = $this->$functionName;
        }

        return $this->json($responseData);
    }

    public function getSupporters()
    {
        return $this->getDoctrine()->getRepository(Supporter::class)->findAll();
    }

    public function getPressReleases()
    {
        return $this->getDoctrine()->getRepository(PressRelease::class)->findAll();
    }

    public function getGeoPoints()
    {
        return $this->getDoctrine()->getRepository(GeoPoint::class)->findAll();
    }

    public function getLanguageData(): array
    {
        $translations = $this->getDoctrine()->getRepository(TranslatedText::class)->findAll();

        $data = [];
        foreach ($translations as $translation) {
            if ($translation->getLanguage() === null || $translation->getTextKey() === null) {
                continue;
            }

            $data[$translation->getLanguage()->getCode()][$translation->getTextKey()->getCode()] = $translation->getContent();
        }

        return $data;
    }
}
