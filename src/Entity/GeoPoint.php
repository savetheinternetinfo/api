<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeoPointRepository")
 */
class GeoPoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=19)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=19)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookEvent;

    /**
     * @ORM\Column(type="boolean", name="sti_event")
     */
    private $StiEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getFacebookEvent(): ?string
    {
        return $this->facebookEvent;
    }

    public function setFacebookEvent(?string $facebookEvent): self
    {
        $this->facebookEvent = $facebookEvent;

        return $this;
    }

    public function getStiEvent(): ?bool
    {
        return $this->StiEvent;
    }

    public function setStiEvent(?bool $StiEvent): self
    {
        $this->StiEvent = $StiEvent;

        return $this;
    }
}
