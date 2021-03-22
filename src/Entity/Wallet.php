<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tb_wallet")
 */
class Wallet
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User|null
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\OneToOne(targetEntity="User")
     */
    private $user;

    /**
     * The money available to do transitions
     *
     * @var float|null
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $onHand;

    /**
     * The money of transitions in progress
     *
     * @var float|null
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $onHold;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setOnHand(float $onHand): void
    {
        $this->onHand = $onHand;
    }

    public function setOnHold(float $onHold): void
    {
        $this->onHold = $onHold;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getOnHand(): ?float
    {
        return $this->onHand;
    }

    public function getOnHold(): ?float
    {
        return $this->onHold;
    }
}