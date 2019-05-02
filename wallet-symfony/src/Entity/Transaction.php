<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{

    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TransactionType", inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transactionType;


    public function __construct()
    {
        $this->transactionType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|TransactionType[]
     */
    public function getTransactionType(): Collection
    {
        return $this->transactionType;
    }

    public function addTransactionType(TransactionType $transactionType): self
    {
        if (!$this->transactionType->contains($transactionType)) {
            $this->transactionType[] = $transactionType;
            $transactionType->setTransaction($this);
        }

        return $this;
    }

    public function removeTransactionType(TransactionType $transactionType): self
    {
        if ($this->transactionType->contains($transactionType)) {
            $this->transactionType->removeElement($transactionType);
            // set the owning side to null (unless already changed)
            if ($transactionType->getTransaction() === $this) {
                $transactionType->setTransaction(null);
            }
        }

        return $this;
    }

    public function setTransactionType(?TransactionType $transactionType): self
    {
        $this->transactionType = $transactionType;

        return $this;
    }
}
