<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionTypeRepository;

/**
 * TransactionType
 *
 * @ORM\Table(name="transaction_type", uniqueConstraints={@ORM\UniqueConstraint(name="uq_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\TransactionTypeRepository")
 */
class TransactionType
{
    /**
     * @var int
     *
     * @ORM\Column(name="transaction_type_id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $transactionTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    public function getTransactionTypeId(): ?int
    {
        return $this->transactionTypeId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }


}
