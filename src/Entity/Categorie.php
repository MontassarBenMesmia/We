<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcategorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "NotBlank")]
    private $nomcategorie;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class, orphanRemoval: true)]
    private $produits;

    public function getIdcategorie(): ?int
    {
        return $this->idcategorie;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): static
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    public function getProduit(): ?Produit
{
    return $this->produit;
}

    public function __toString() {
        // Retourne la représentation textuelle de l'objet
        // Par exemple, vous pourriez retourner le nom de la catégorie
        return $this->getNomcategorie   (); // Remplacez getNom() par la méthode qui renvoie le nom de la catégorie
    }


}