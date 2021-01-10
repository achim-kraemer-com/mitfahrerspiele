<?php

namespace App\Entity;

use App\Repository\LicensePlateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LicensePlateRepository::class)
 */
class LicensePlate extends IdentifiableModel
{
    /**
     * @ORM\Column(type="string", length=4)
     */
    private $shortText;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    public function getShortText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(string $shortText): self
    {
        $this->shortText = $shortText;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
