<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(columns={"transaction_type_id"}), @ORM\Index(columns={"user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="transaction_id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=11, scale=2, nullable=false)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @ORM\Version
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \TransactionType
     *
     * @ORM\ManyToOne(targetEntity="TransactionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="transaction_type_id")
     * })
     */
    private $transactionType;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTransactionType(): ?TransactionType
    {
        return $this->transactionType;
    }

    public function setTransactionType(?TransactionType $transactionType): self
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return $this->transactionType;
    }

}
