<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Collator;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $loation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?string $salary = null;

    //----------------------------Relación ofertas de trabajo y usuario, a través de aplicación------------------------------------
    //Relación uno a muchos con aplicar: Una oferta de trabajo puede tener muchas aplicaciones de usuario
    #[ORM\OneToMany(mappedBy:'jobOffer', targetEntity: Application::class)]
    private Collection $applications;


    public function __construct()
    {
        $this->applications = new ArrayCollection(); //inicializar la colección de aplicaciones cuando se cree una nueva oferta
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLoation(): ?string
    {
        return $this->loation;
    }

    public function setLoation(string $loation): static
    {
        $this->loation = $loation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }


    //obetner todas las aplicaicones asociadas a una oferta
    public function getapplications(): Collection
    {
        return $this->applications;
    }



}
