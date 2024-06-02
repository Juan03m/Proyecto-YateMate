<?php

namespace App\Entity;

use App\Repository\AmarraRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $tamaño = null;


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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getTamaño(): ?string
    {
        return $this->tamaño;
    }

    public function setTamaño(string $tamaño): static
    {
        $this->tamaño = $tamaño;

        return $this;
    }

}
