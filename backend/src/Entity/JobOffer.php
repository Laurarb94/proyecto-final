<?php

namespace App\Entity;

use App\Entity\User;

use App\Repository\JobOfferRepository;
use Collator;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['application:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['application:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['application:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['application:read'])]
    private ?string $loation = null;

    #[ORM\Column]
    #[Groups(['application:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['application:read'])]
    private ?string $salary = null;


    //----------------------------Relación ofertas de trabajo y usuario, a través de aplicación------------------------------------
    //Relación uno a muchos con aplicar: Una oferta de trabajo puede tener muchas aplicaciones de usuario
    #[ORM\OneToMany(mappedBy:'jobOffer', targetEntity: Application::class)]
    #[Groups(["job_offer_read"])]
    private Collection $applications;

    //-----------------------------Relación de oferta de empleo con usuario para saber el usuario que ha publicado la oferta---------
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['job_offer:read'])]
    private ?User $company = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;
    
    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;



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

    public function getCompany(): ?User
    {
        return $this->company;
    }

    public function setCompany(?User $company): static
    {
        $this->company = $company;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }
    
    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }
    
    public function setSubcategory(?Subcategory $subcategory): static
    {
        $this->subcategory = $subcategory;
        return $this;
    }


}
