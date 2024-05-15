<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    #[Assert\Email(message: "Not Valid")]
    private ?string $email = null;

    #[ORM\ManyToOne(targetEntity: Equipe::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $equipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): static
    {
        $this->nationalite = $nationalite;

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

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }
}