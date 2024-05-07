<?php

namespace App\Entity;

use App\Repository\AmarraRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmarraRepository::class)]
class Amarra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Sector = null;

    #[ORM\Column]
    private ?int $Marina = null;

    #[ORM\Column]
    private ?int $Posicion = null;

    #[ORM\Column]
    private ?int $tamaño = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSector(): ?int
    {
        return $this->$Sector;
    }

    public function setSector(int $Sector): static
    {
        $this->Sector = $Sector;

        return $this;
    }

    public function getMarina(): ?int
    {
        return $this->$Marina;
    }

    public function setMarina(int $Marina): ?int
    {
        $this ->Marina = $Marina;
    }
    
    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(?int $posicion): void
    {
        $this->posicion = $posicion;
    }

    public function getTamaño(): ?int
    {
        return $this->tamaño;
    }

    public function setTamaño(?int $tamaño): void
    {
        $this->tamaño = $tamaño;
    }
    
}
