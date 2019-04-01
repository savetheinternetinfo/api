<?php

namespace App\Controller;

use App\Entity\GeoPoint;
use App\Entity\PressRelease;
use App\Entity\Supporter;
use App\Entity\SupporterOrganisation;
use App\Entity\TranslatedText;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public const API_NODES = [
        'points' => 'getGeoPoints',
        'translation' => 'getLanguageData',
        'press' => 'getPressReleases',
        'supporter' => 'getSupporters',
        'gallery' => 'getGallery',
    ];


    /**
     * @Route("/api/get", name="api")
     * @param Request $request
     * @return JsonResponse
     */
    public function apiAction(Request $request): JsonResponse
    {
        $nodes = $request->get('q');
        $nodeArray = explode(',', $nodes);
        $nodeArray = array_keys(array_flip($nodeArray));

        $responseData = [];
        foreach ($nodeArray as $nodeName) {
            if (!isset(self::API_NODES[$nodeName])) {
                continue;
            }

            $functionName = self::API_NODES[$nodeName];

            $responseData[$nodeName] = $this->$functionName();
        }

        return $this->json($responseData);
    }

    /**
     * @Route("/api/supporters", name="api_supporters")
     * @return JsonResponse
     */
    public function oldApiAction(): JsonResponse
    {
        $supporterList = $this->getDoctrine()->getRepository(SupporterOrganisation::class)->findAll();

        $data = [];
        foreach (self::sortSupporters($supporterList) as $supporter) {
            $data[] = [
                'name' => $supporter->getName(),
                'url' => $supporter->getUrl(),
                'logoURL' => '/uploads/images/supporters/' . $supporter->getImage(),
            ];
        }

        return $this->json([
            [
                'orga' => $data
            ]
        ]);
    }

    /**
     * @Route("/mapcoords", name="mapcoords")
     * @return JsonResponse
     */
    public function oldMapCoordsAction(): JsonResponse
    {
        $geoPoints = $this->getDoctrine()->getRepository(GeoPoint::class)->findAll();

        $features = array_map(function (GeoPoint $geoPoint) {
            $data = [
                'type' => 'Feature',
                'properties' => [
                    [
                        'fa_icon' => 'fa-map-marker',
                        'value' => $geoPoint->getLocation()
                    ],
                    [
                        'fa_icon' => 'fa-clock-o',
                        'value' => $geoPoint->getTime()->format('d.m.Y H:i')
                    ]
                ],
                'STIDemo' => $geoPoint->getStiEvent(),
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $geoPoint->getLongitude(),
                        $geoPoint->getLatitude(),
                    ]
                ]
            ];

            if ($geoPoint->getFacebookEvent()) {
                if (strpos($geoPoint->getFacebookEvent(), '<a href') !== false) {
                    $data['properties'][] = [
                        'fa_icon' => 'fb_event',
                        'value' => $geoPoint->getFacebookEvent()
                    ];
                } else {
                    $data['properties'][] = [
                        'fa_icon' => 'fb_event',
                        'value' => '<a href="' . $geoPoint->getFacebookEvent() . '">Facebook Event</a>'
                    ];
                }
            }


            return $data;
        }, $geoPoints);

        return $this->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * @param Supporter[] $supporters
     * @return Supporter[]
     */
    public static function sortSupporters(array $supporters): array
    {
        usort($supporters, function (Supporter $a, Supporter $b) {
            return $b->getPriority() <=> $a->getPriority();
        });

        return $supporters;
    }

    public function getSupporters()
    {
        $supporterList = $this->getDoctrine()->getRepository(Supporter::class)->findAll();

        $data = [];
        foreach (self::sortSupporters($supporterList) as $supporter) {
            $entry = [
                'name' => $supporter->getName(),
            ];

            if ($supporter instanceof SupporterOrganisation) {
                $entry['url'] = $supporter->getUrl();
                $entry['image'] = '/uploads/images/supporters/' . $supporter->getImage();
            }

            $data[] = $entry;
        }

        return $data;
    }

    public function getPressReleases()
    {
        return array_map(function (PressRelease $pressRelease) {
            /** @noinspection NullPointerExceptionInspection */
            return [
                'title' => $pressRelease->getTitle(),
                'time' => $pressRelease->getTime(),
                'url' => $pressRelease->getUrl(),
                'lang' => $pressRelease->getLanguage()->getCode(),
            ];
        }, $this->getDoctrine()->getRepository(PressRelease::class)->findAll());
    }

    public function getGeoPoints()
    {
        return array_map(function (GeoPoint $geoPoint) {
            return [
                'location' => $geoPoint->getLocation(),
                'time' => $geoPoint->getTime(),
                'latitude' => $geoPoint->getLatitude(),
                'longitude' => $geoPoint->getLongitude(),
                'facebookEvent' => $geoPoint->getFacebookEvent(),
                'sti_event' => $geoPoint->getStiEvent()
            ];
        }, $this->getDoctrine()->getRepository(GeoPoint::class)->findAll());
    }

    public function getLanguageData(): array
    {
        $translations = $this->getDoctrine()->getRepository(TranslatedText::class)->findAll();

        $data = [];
        foreach ($translations as $translation) {
            if ($translation->getLanguage() === null || $translation->getTextKey() === null) {
                continue;
            }

            if ($translation->getLanguage()->getEnabled() === false) {
                continue;
            }

            if ($translation->getLanguage()->getVersion()->getVersionNumber() !== $translation->getVersion()->getVersionNumber()) {
                continue;
            }

            $data[$translation->getLanguage()->getCode()][$translation->getTextKey()->getCode()] = $translation->getContent();
        }

        return $data;
    }

    public function getGallery(): array
    {
        $galleryPath = getenv('GALLERY_PATH');
        $galleryUrl = getenv('GALLERY_URL');
        $data = [];

        if ($galleryPath === '') {
            return $data;
        }

        $directoryFinder = new Finder();
        $directoryFinder->directories()->in($galleryPath);
        foreach ($directoryFinder as $directory) {
            $finder = new Finder();
            $finder->files()->in($directory->getRealPath());

            foreach ($finder as $file) {
                if (strpos($file->getFilename(), 'thumb_') === 0) {
                    continue;
                }
                $data[] = [
                    'image' => $galleryUrl . $directory->getBasename() . '/' . $file->getFilename(),
                    'thumbnail' => $galleryUrl . $directory->getBasename() . '/' . $file->getFilename(),
                    'language' => $directory->getBasename(),
                ];
            }
        }

        return $data;
    }
}
