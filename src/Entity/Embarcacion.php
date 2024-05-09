<?php

namespace App\Entity;

use App\Repository\EmbarcacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmbarcacionRepository::class)]
class Embarcacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Matricula = null;

    #[ORM\Column(length: 255)]
    private ?string $Nombre = null;

    #[ORM\Column]
    private ?float $Tamano = null;

    #[ORM\Column(length: 255)]
    private ?string $Bandera = null;

    #[ORM\Column(length: 255)]
    private ?string $Tipo = null;

    #[ORM\OneToOne(mappedBy: 'embarcacion', cascade: ['persist', 'remove'])]
    private ?Publicacion $publicacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricula(): ?string
    {
        return $this->Matricula;
    }

    public function setMatricula(string $Matricula): static
    {
        $this->Matricula = $Matricula;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): static
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getTamano(): ?float
    {
        return $this->Tamano;
    }

    public function setTamano(float $Tamano): static
    {
        $this->Tamano = $Tamano;

        return $this;
    }

    public function getBandera(): ?string
    {
        return $this->Bandera;
    }

    public function setBandera(string $Bandera): static
    {
        $this->Bandera = $Bandera;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->Tipo;
    }

    public function setTipo(string $Tipo): static
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(Publicacion $publicacion): static
    {
        // set the owning side of the relation if necessary
        if ($publicacion->getEmbarcacion() !== $this) {
            $publicacion->setEmbarcacion($this);
        }

        $this->publicacion = $publicacion;

        return $this;
    }
}
