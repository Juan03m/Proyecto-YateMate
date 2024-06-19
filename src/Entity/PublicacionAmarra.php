<?php

namespace App\Entity;

use App\Repository\PublicacionAmarraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicacionAmarraRepository::class)]
class PublicacionAmarra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'publicacionAmarra', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Amarra $Amarra = null;

    #[ORM\ManyToOne(inversedBy: 'publicacionAmarras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaDesde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaHasta = null;

    #[ORM\Column(nullable:true)]
    private ?int $numero = null;

    #[ORM\Column(nullable:true)]
    private ?int $sector = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $marina = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $tamano = null;

    #[ORM\OneToOne(mappedBy: 'publicacionAmarra', cascade: ['persist', 'remove'])]
    private ?ReservaAmarra $reservaAmarra = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmarra(): ?Amarra
    {
        return $this->Amarra;
    }

    public function setAmarra(Amarra $Amarra): static
    {
        $this->Amarra = $Amarra;

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

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fechaDesde;
    }

    public function setFechaDesde(\DateTimeInterface $fechaDesde): static
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fechaHasta;
    }

    public function setFechaHasta(\DateTimeInterface $fechaHasta): static
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

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

    public function getTamano(): ?string
    {
        return $this->tamano;
    }

    public function setTamano(string $tamano): static
    {
        $this->tamano = $tamano;

        return $this;
    }

    public function getReservaAmarra(): ?ReservaAmarra
    {
        return $this->reservaAmarra;
    }

    public function setReservaAmarra(ReservaAmarra $reservaAmarra): static
    {
        // set the owning side of the relation if necessary
        if ($reservaAmarra->getPublicacionAmarra() !== $this) {
            $reservaAmarra->setPublicacionAmarra($this);
        }

        $this->reservaAmarra = $reservaAmarra;

        return $this;
    }
    public function __toString()
    {
        $amarra=$this->getAmarra();
        return $amarra->__toString();
    }
}
