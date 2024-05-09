<?php

namespace App\Entity;

use App\Repository\EmbarcacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
    #[Assert\NotBlank(message: "Por favor, introduzca un nombre.")]
    
    private ?string $Nombre = null;

    #[ORM\Column]
    private ?string $Tamano = null;

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

    // Getters and setters for Tamano
    public function getTamano(): ?string
    {
        return $this->Tamano;
    }

    public function setTamano(?float $Tamano): self
    {
        $this->Tamano = $Tamano;

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
    public function validate(ExecutionContextInterface $context, $payload,EmbarcacionRepository $embarcacionRepository)
    {
        $existingEmbarcacion = $embarcacionRepository->findOneBy(['Nombre' => $this->Nombre]);

        if ($existingEmbarcacion && ($existingEmbarcacion->getId() !== $this->id)) {
            $context->buildViolation('El nombre de la embarcación ya está registrado.')
                ->atPath('Nombre')
                ->addViolation();
        }
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
}
