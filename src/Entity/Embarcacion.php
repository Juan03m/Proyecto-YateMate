<?php

namespace App\Entity;

use App\Repository\EmbarcacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;


#[ORM\Entity(repositoryClass: EmbarcacionRepository::class)]
#[UniqueEntity(
    fields: ["Matricula"],
    message: "La matricula ya esta registrada",
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
    
    #[ORM\OneToOne(mappedBy: 'embarcacion', cascade: ['persist'])]
    private ?Amarra $amarra = null;

    #[ORM\ManyToOne(inversedBy: 'embarcaciones')]
  //  #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column]
    private ?float $manga = null;

    #[ORM\Column]
    private ?float $eslora = null;

    #[ORM\Column]
    private ?float $puntal = null;

    /**
     * @var Collection<int, Solicitud>
     */
    #[ORM\OneToMany(targetEntity: Solicitud::class, mappedBy: 'embarcacion', orphanRemoval: true)]
    private Collection $solicitudes;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
    }



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
            'message' => 'Esta Matricula ya está registrada',
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

    public function getManga(): ?float
    {
        return $this->manga;
    }

    public function setManga(float $manga): static
    {
        $this->manga = $manga;

        return $this;
    }

    public function getEslora(): ?float
    {
        return $this->eslora;
    }

    public function setEslora(float $eslora): static
    {
        $this->eslora = $eslora;

        return $this;
    }

    public function getPuntal(): ?float
    {
        return $this->puntal;
    }

    public function setPuntal(float $puntal): static
    {
        $this->puntal = $puntal;

        return $this;
    }

    /**
     * @return Collection<int, Solicitud>
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): static
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes->add($solicitude);
            $solicitude->setEmbarcacion($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): static
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getEmbarcacion() === $this) {
                $solicitude->setEmbarcacion(null);
            }
        }

        return $this;
    }



}