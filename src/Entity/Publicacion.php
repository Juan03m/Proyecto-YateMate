<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PublicacionRepository::class)]
class Publicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'publicacion', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Embarcacion $embarcacion = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $sector = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marina = null;


    
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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }



    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

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

    public function getSector(): ?int
    {
        return $this->sector;
    }

    public function setSector(?int $sector): static
    {
    
         $this->sector = $sector;

        return $this;
    }

    public function getMarina(): ?string
    {
        return $this->marina;
    }

    public function setMarina(?string $marina): static
    {
        $this->marina = $marina;

        return $this;
    }



}
