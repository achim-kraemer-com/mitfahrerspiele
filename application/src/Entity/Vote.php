<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote extends IdentifiableModel
{
    /**
     * @ORM\ManyToOne(targetEntity=Content::class, inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $ipAdress;

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIpAdress(): ?string
    {
        return $this->ipAdress;
    }

    public function setIpAdress(string $ipAdress): self
    {
        $this->ipAdress = $ipAdress;

        return $this;
    }
}
