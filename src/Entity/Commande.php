<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_effet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_exp = null;

    #[ORM\Column(length: 255)]
 
    private ?array $full_doa = null;

    #[ORM\ManyToOne(targetEntity: Assurance::class)]
    //#[ORM\JoinColumn(nullable: false,onDelete:'CASCADE')]
    private ?Assurance $doaCom = null;


    #[ORM\ManyToOne(inversedBy: 'commandes')]
    //#[ORM\JoinColumn(nullable: false,onDelete:'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    //#[ORM\JoinColumn(nullable: false,onDelete:'CASCADE')]
    private ?Offre $off = null;

    #[ORM\Column]
    #[Assert\Positive(message:"The Value Must Be Positive!")]
    private ?float $InsValue = null;

    #[ORM\Column(type: 'boolean')]
    private $isChecked = false;

    public function getIsChecked(): bool
    {
        return $this->isChecked;
    }

    public function setIsChecked(bool $isChecked): static
    {
        $this->isChecked = $isChecked;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateEffet(): ?\DateTimeInterface
    {
        return $this->date_effet;
    }

    public function setDateEffet(\DateTimeInterface $date_effet): static
    {
        $this->date_effet = $date_effet;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->date_exp;
    }

    public function setDateExp(\DateTimeInterface $date_exp): static
    {
        $this->date_exp = $date_exp;

        return $this;
    }

    public function getFullDoa(): ?array
    {
        return $this->full_doa;
    }

    public function setFullDoa(array $full_doa): static
    {
        $this->full_doa = $full_doa;

        return $this;
    }

    public function getDoaCom(): ?assurance
    {
        return $this->doaCom;
    }

    public function setDoaCom(?assurance $doaCom): static
    {
        $this->doaCom = $doaCom;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInsValue(): ?float
    {
        return $this->InsValue;
    }

    public function setInsValue(float $InsValue): static
    {
        $this->InsValue = $InsValue;

        return $this;
    }

    public function getOff(): ?Offre
    {
        return $this->off;
    }

    public function setOff(?Offre $off): static
    {
        $this->off = $off;

        return $this;
    }

}