<?php

namespace App\Entity;

use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
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
    
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?float $prix;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private ?int $quantiteenstock;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "idCategorie", referencedColumnName: "idcategorie")]
    private $categorie;

    #[ORM\Column(length: 255)]
    private ?string $image;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantiteenstock(): ?int
    {
        return $this->quantiteenstock;
    }

    public function setQuantiteenstock(int $quantiteenstock): static
    {
        $this->quantiteenstock = $quantiteenstock;

        return $this;
    }
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
    
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdcategorie(): ?int
{
    return $this->idcategorie;
}




}
