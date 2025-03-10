<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
    private Collection $students; 



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





}
