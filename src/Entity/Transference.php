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
     * @var string
     * @ORM\Column(type="guid", unique=true)
     */
    private $number;

    /**
     * @var Wallet
     * @ORM\ManyToOne(targetEntity="Wallet")
     * @ORM\JoinColumn(name="payer_wallet_id", referencedColumnName="id")
     */
    private $payerWallet;

    /**
     * @var Wallet
     * @ORM\ManyToOne(targetEntity="Wallet")
     * @ORM\JoinColumn(name="payee_wallet_id", referencedColumnName="id")
     */
    private $payeeWallet;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $amount;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;

    public function __construct(
        ?int $id,
        string $number,
        Wallet $payerWallet,
        Wallet $payeeWallet,
        float $amount,
        int $status,
        DateTimeImmutable $created
    ) {
        $this->id = $id;
        $this->number = $number;
        $this->payerWallet = $payerWallet;
        $this->payeeWallet = $payeeWallet;
        $this->amount = $amount;
        $this->status = $status;
        $this->created = $created;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getPayerWallet(): Wallet
    {
        return $this->payerWallet;
    }

    public function getPayeeWallet(): Wallet
    {
        return $this->payeeWallet;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }
}