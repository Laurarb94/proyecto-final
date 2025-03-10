<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    //------Relación con subcategory. Uno a muchos, una categoría puede tener muchas subcategorías, pero cada subcategoría pertenece sólo a 1 categoría
    #[ORM\OneToMany(mappedBy:'category', targetEntity: Subcategory::class, orphanRemoval: true)]
    private Collection $subcategories;


    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    //-----Métodos relación con subcategoria
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->setCategory($this);
        }
        
        return $this;
    }
    
    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->removeElement($subcategory)) {
            if ($subcategory->getCategory() === $this) {
                $subcategory->setCategory(null);
            }
        }

    return $this;

    }


}
