<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tb_user")
 */
class User
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string|null
     * @ORM\Column(length=255)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=15,unique=true)
     */
    private $document;

    /**
     * @var string|null
     *@ORM\Column(length=255, unique=true)
     */
    private $email;

    /**
     * @var int|null
     * @ORM\Column(type="smallint")
     */
    private $type;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getType(): ?int
    {
        return $this->type;
    }
}