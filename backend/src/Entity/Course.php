<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    //---------------Relación con usuarios---------------------
    //Relación muchos a muchos: un usuario puede inscribirse en muchos cursos y un curso puede tener muchos alumnos
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy:'courses')]
    #[Groups(['course:read'])]
    private Collection $students; 

    
    //--------------Relación con cateorías---------------------------
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable:false)]
    private ?Category $category = null;

    //----------------Relación con subcategorías----------------------------
    #[ORM\ManyToOne(targetEntity: Subcategory::class)]
    #[ORM\JoinColumn(nullable:false)]
    private ?Subcategory $subcategory = null;



    public function __construct()
    {
        $this->students = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }


    //Relación con usuarios. 
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudents(User $students): self
    {
        if(!$this->students->contains($students)){
            $this->students[] = $students;
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        $this->students->removeElement($student);
        return $this;
    }

    //Relación categoría
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    //Relación subcategoría
    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;
        return $this;
    }





}
