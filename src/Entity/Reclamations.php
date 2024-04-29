<?php

namespace App\Entity;
use App\Entity\Reponse;
use App\Repository\ReclamationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: ReclamationsRepository::class)]
class Reclamations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    #[Assert\Email(message: "Not Valid")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    
    private ?string $typeReclamation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    
    private ?string $etat = null;

    #[ORM\OneToOne(inversedBy: "reclamation", cascade: ["persist"])]
    private ?Reponse $reponse = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTypeReclamation(): ?string
    {
        return $this->typeReclamation;
    }

    public function setTypeReclamation(string $typeReclamation): self
    {
        $this->typeReclamation = $typeReclamation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function __toString(){return (string)$this->getId();}

public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }
    
}