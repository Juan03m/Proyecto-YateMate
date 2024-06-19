<?php

namespace App\Entity;

use App\Repository\AmarraRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AmarraRepository::class)]
#[UniqueEntity('embarcacion','Ya existe una embarcacion en esa amarra')]
#[UniqueEntity(fields: ['Numero', 'sector', 'marina'], message: 'Ya existe una amarra con ese número, sector y marina')]
class Amarra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Numero = null;

    #[ORM\Column]
    private ?int $sector = null;

    #[ORM\Column(length: 255)]
    private ?string $marina = null;


    #[ORM\OneToOne(inversedBy: 'amarra', cascade: ['persist'])]
    private ?Embarcacion $embarcacion = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Por favor, seleccione un tamaño para la amarra')]
    private ?string $tamano = null;

    #[ORM\ManyToOne(inversedBy: 'amarras')]
    private ?Usuario $usuario = null;

    #[ORM\OneToOne(mappedBy: 'Amarra', cascade: ['persist', 'remove'])]
    private ?PublicacionAmarra $publicacionAmarra = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->Numero;
    }

    public function setNumero(int $Numero): static
    {
        $this->Numero = $Numero;

        return $this;
    }

    public function getSector(): ?int
    {
        return $this->sector;
    }

    public function setSector(int $sector): static
    {
        $this->sector = $sector;

        return $this;
    }

    public function getMarina(): ?string
    {
        return $this->marina;
    }

    public function setMarina(string $marina): static
    {
        $this->marina = $marina;

        return $this;
    }

   
    public function getEmbarcacion(): ?Embarcacion
    {
        return $this->embarcacion;
    }

    public function setEmbarcacion(?Embarcacion $embarcacion): static
    {
        $this->embarcacion = $embarcacion;

        return $this;
    }


    public function __toString()
    {
        $string = 'Nro: ' . $this->getNumero() . ',  ' . 'Sector: ' . $this->getSector() . ',  ' . 'Marina: ' . $this->getMarina();
        return $string;
    }



    public function getTamano(): ?string
    {
        return $this->tamano;
    }

    public function setTamano(?string $tamano): static
    {
        $this->tamano = $tamano;

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

    public function getPublicacionAmarra(): ?PublicacionAmarra
    {
        return $this->publicacionAmarra;
    }

    public function setPublicacionAmarra(PublicacionAmarra $publicacionAmarra): static
    {
        // set the owning side of the relation if necessary
        if ($publicacionAmarra->getAmarra() !== $this) {
            $publicacionAmarra->setAmarra($this);
        }

        $this->publicacionAmarra = $publicacionAmarra;

        return $this;
    }

}
