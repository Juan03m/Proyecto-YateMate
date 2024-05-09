<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicacionRepository::class)]
class Publicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'publicacion', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Embarcacion $embarcacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmbarcacion(): ?Embarcacion
    {
        return $this->embarcacion;
    }

    public function setEmbarcacion(Embarcacion $embarcacion): static
    {
        $this->embarcacion = $embarcacion;

        return $this;
    }
}
