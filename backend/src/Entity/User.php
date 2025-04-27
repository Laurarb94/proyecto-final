<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\DataTransformer\StringToFloatTransformer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName2 = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biography = null;


    //-------------------Relación usuarios y mensajes-----------------------------------
    // Relación uno a muchos: un usuario puede enviar muchos mensajes, y un mensaje sólo puede tener un remitente y un destinataorio

    #[ORM\OneToMany(mappedBy:'sender', targetEntity: Message::class, orphanRemoval:true)] //orphanRemoval para que cuando borres un mensaje de la colección se borre de la bbdd también
    private Collection $sentMessages;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $receivedMessages;

    //------------------Relación usuarios y ofertas de trabajo, a través de aplication-----------------------------------------------
    //Relación uno a muchos con Aplicar: un usuario puede aplicar a muchas ofertas de trabajo
    #[ORM\OneToMany(mappedBy:'user', targetEntity: Application::class)]
    private Collection $applications;

    //-------------------Relación usuarios y cursos---------------------------------------------------------------------------------
    //Relación muchos a muchos: un usuario puede inscribirse en muchos cursos y un curso puede tener muchos alumnos
    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy:'students')]
    private Collection $courses;

    #[ORM\Column(type: 'string', length: 255, nullable: 'true')]
    private ?string $cv = null;

    //-------------Relación con oefrtas de empleo para obtener las ofertas que haya publicaod el usuario------------------
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: JobOffer::class)]
    private Collection $jobOffers;


    public function __construct()
    {
        $this->roles = [];
        $this->applications = new ArrayCollection(); //incializar la colección de aplicaciones cuando se cree un nuevo usuario
        $this->courses = new ArrayCollection();
        $this->jobOffers = new ArrayCollection();
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

    public function getLastName1(): ?string
    {
        return $this->lastName1;
    }

    public function setLastName1(string $lastName1): static
    {
        $this->lastName1 = $lastName1;

        return $this;
    }

    public function getLastName2(): ?string
    {
        return $this->lastName2;
    }

    public function setLastName2(?string $lastName2): static
    {
        $this->lastName2 = $lastName2;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; //garantizar que siempre tenga al menos el rol de usuario
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    //------------------Seguridad
    public function eraseCredentials(): void
    {
        
    }

    public function getUserIdentifier(): string
    {
        return $this->email; //el email va a ser el identidicador único del usuario
    }

    //----Relación usuarios y mensajes-------------

    //Gestión de mensajes enviados
    public function addSentMessage(Message $message): self
    {
        if(!$this->sentMessages->contains($message)){
            $this->sentMessages[] = $message;
            $message->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $message): self
    {
        $this->sentMessages->removeElement($message);
        return $this;
    }

    //Gestión mensajes recibidos
    public function addReceivedMessage(Message $message): self
    {
        if (!$this->receivedMessages->contains($message)) {
            $this->receivedMessages[] = $message;
            $message->setReceiver($this);
        }
        return $this;
    }
    
    public function removeReceivedMessage(Message $message): self
    {
        $this->receivedMessages->removeElement($message);
        return $this;
    }

    //----------------------Relación con aplicar-----------------------------
    //Obtener las aplicaciones a ofertas de empleo del usuario:
    public function getApplications(): Collection
    {
        return $this->applications;
    }


    //-------------------------Relación cursos---------------------------
    public function addCourse(Course $course): self
    {
        if(!$this->courses->contains($course)){
            $this->courses[] = $course;
            $course->addStudents($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        $this->courses->removeElement($course);
        return $this;
    }


    //----CV
    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;
        return $this;
    }

    //-----jobOffer
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers[] = $jobOffer;
            $jobOffer->setCompany($this);
        }
        
        return $this;
    }
    
    public function removeJobOffer(JobOffer $jobOffer): self
    {
        $this->jobOffers->removeElement($jobOffer);
        return $this;
    }




}
