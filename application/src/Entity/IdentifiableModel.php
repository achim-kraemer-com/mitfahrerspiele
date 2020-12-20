<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class IdentifiableModel
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(
     *     type="datetime_immutable",
     *     options={"default"="CURRENT_TIMESTAMP"}
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(
     *     type="datetime_immutable",
     *     options={"default"="CURRENT_TIMESTAMP"}
     * )
     */
    protected $updatedAt;

    /**
     * Constructor of IdentifiableModel.
     */
    public function __construct()
    {
        $this->id        = Uuid::uuid4();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getUuid(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
