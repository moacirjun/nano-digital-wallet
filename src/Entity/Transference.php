<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tb_transference")
 */
class Transference
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="guid")
     */
    private $number;

    /**
     * @var Wallet|null
     * @ORM\ManyToOne(targetEntity="Wallet")
     * @ORM\JoinColumn(name="payer_wallet_id", referencedColumnName="id")
     */
    private $payerWallet;

    /**
     * @var Wallet|null
     * @ORM\ManyToOne(targetEntity="Wallet")
     * @ORM\JoinColumn(name="payee_wallet_id", referencedColumnName="id")
     */
    private $payeeWallet;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $amount;

    /**
     * @var int|null
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @var DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getPayerWallet(): ?Wallet
    {
        return $this->payerWallet;
    }

    public function setPayerWallet(Wallet $payerWallet): void
    {
        $this->payerWallet = $payerWallet;
    }

    public function getPayeeWallet(): ?Wallet
    {
        return $this->payeeWallet;
    }

    public function setPayeeWallet(Wallet $payeeWallet): void
    {
        $this->payeeWallet = $payeeWallet;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): void
    {
        $this->created = $created;
    }
}