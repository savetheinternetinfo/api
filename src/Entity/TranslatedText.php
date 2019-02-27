<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslatedTextRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="language_textkey", columns={"language", "text_key"})})
 */
class TranslatedText
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\JoinColumn(nullable=false, name="language")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TextKey")
     * @ORM\JoinColumn(nullable=false, name="text_key")
     */
    private $textKey;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTextKey(): ?TextKey
    {
        return $this->textKey;
    }

    public function setTextKey(?TextKey $textKey): self
    {
        $this->textKey = $textKey;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
