<?php

namespace App\Entity;

use App\Repository\EmbarcacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;


#[ORM\Entity(repositoryClass: EmbarcacionRepository::class)]
#[UniqueEntity(
    fields: ["Matricula"],
    message: "La matricula ya está en uso",
    groups: ["new"]
)]
 
class Embarcacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Matricula = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Por favor, introduzca un nombre.")]
    
    private ?string $Nombre = null;


    #[ORM\Column(length: 255)]
    private ?string $Bandera = null;

    #[ORM\Column(length: 255)]
    private ?string $Tipo = null;

    #[ORM\OneToOne(mappedBy: 'embarcacion', cascade: ['persist', 'remove'])]
    private ?Publicacion $publicacion = null;
    
    #[ORM\OneToOne(mappedBy: 'embarcacion', cascade: ['persist', 'remove'])]
    private ?Amarra $amarra = null;

    #[ORM\ManyToOne(inversedBy: 'embarcaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column]
    private ?float $alto = null;

    #[ORM\Column]
    private ?float $ancho = null;

    #[ORM\Column]
    private ?float $largo = null;


    // Getters and setters for id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getters and setters for Matricula
    public function getMatricula(): ?string
    {
        return $this->Matricula;
    }

    public function setMatricula(?string $Matricula): self
    {
        $this->Matricula = $Matricula;

        return $this;
    }

    // Getters and setters for Nombre
    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(?string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    // Getters and setters for Bandera
    public function getBandera(): ?string
    {
        return $this->Bandera;
    }

    public function setBandera(?string $Bandera): self
    {
        $this->Bandera = $Bandera;

        return $this;
    }

    // Getters and setters for Tipo
    public function getTipo(): ?string
    {
        return $this->Tipo;
    }

    public function setTipo(?string $Tipo): self
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    // Getters and setters for Publicacion
    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): self
    {
        // set the owning side of the relation if necessary
        if ($publicacion->getEmbarcacion() !== $this) {
            $publicacion->setEmbarcacion($this);
        }

        $this->publicacion = $publicacion;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'Matricula',
            'message' => 'Esta Matricula ya está en uso',
        ]));

        $metadata->addPropertyConstraint('Nombre', new Assert\NotBlank([
            'message' => 'La Matricula no puede estar vacío',
        ]));
    }


    public function getAmarra(): ?Amarra
    {
        return $this->amarra;
    }

    public function setAmarra(?Amarra $amarra): static
    {
        // unset the owning side of the relation if necessary
        if ($amarra === null && $this->amarra !== null) {
            $this->amarra->setEmbarcacion(null);
        }

        // set the owning side of the relation if necessary
        if ($amarra !== null && $amarra->getEmbarcacion() !== $this) {
            $amarra->setEmbarcacion($this);
        }

        $this->amarra = $amarra;

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


    public function __toString()
    {
        $embarcacion='Matricula:  '.$this->getMatricula().' '.'Nombre: '.$this->getNombre();
        return $embarcacion;
    }

    public function getAlto(): ?float
    {
        return $this->alto;
    }

    public function setAlto(float $alto): static
    {
        $this->alto = $alto;

        return $this;
    }

    public function getAncho(): ?float
    {
        return $this->ancho;
    }

    public function setAncho(float $ancho): static
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getLargo(): ?float
    {
        return $this->largo;
    }

    public function setLargo(float $largo): static
    {
        $this->largo = $largo;

        return $this;
    }

}