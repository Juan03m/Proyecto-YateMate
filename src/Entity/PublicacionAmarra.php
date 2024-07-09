<?php

namespace App\Entity;

use App\Repository\PublicacionAmarraRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicacionAmarraRepository::class)]
class PublicacionAmarra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


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

    #[ORM\OneToMany(mappedBy: 'publicacionAmarra', targetEntity: ReservaAmarra::class, cascade: ['persist', 'remove'])]
    private Collection $reservaAmarra;

    #[ORM\Column]
    private ?bool $estaVigente = null;

    #[ORM\Column]
    private ?bool $estaAlquilada = null;

    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    private ?Amarra $amarra = null;

    #[ORM\Column(nullable: true)]
    private ?bool $asistio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmarra(): ?Amarra
    {
        return $this->amarra;
    }

    public function setAmarra(Amarra $amarra): static
    {
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

        /**
     * @return Collection<int, ReservaAmarra>
     */
    public function getReservaAmarra(): Collection
    {
        return $this->reservaAmarra;
    }
    public function addReservaAmarra(ReservaAmarra $reservaAmarra): static
    {
        if (!$this->reservaAmarra->contains($reservaAmarra)) {
            $this->reservaAmarra->add($reservaAmarra);
            $reservaAmarra->setPublicacionAmarra($this);
        }

        return $this;
    }
    /*
    public function removeReservaAmarra(ReservaAmarra $reservaAmarra): static
    {
        if ($this->reservaAmarra->removeElement($reservaAmarra)) {
            // set the owning side to null (unless already changed)
            if ($reservaAmarra->getPublicacionAmarra() === $this) {
                $reservaAmarra->setPublicacionAmarra(null);
            }
        }

        return $this;
    }
        */
    public function __toString()
    {
        $amarra=$this->getAmarra();
        return $amarra->__toString();
    }

    public function isEstaVigente(): ?bool
    {
        return $this->estaVigente;
    }

    public function setEstaVigente(bool $estaVigente): static
    {
        $this->estaVigente = $estaVigente;

        return $this;
    }

    public function isEstaAlquilada(): ?bool
    {
        return $this->estaAlquilada;
    }

    public function setEstaAlquilada(bool $estaAlquilada): static
    {
        $this->estaAlquilada = $estaAlquilada;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;


    }

    public function isAsistio(): ?bool
    {
        return $this->asistio;
    }

    public function setAsistio(?bool $asistio): static
    {
        $this->asistio = $asistio;

        return $this;
    }

    public function tieneReservaActiva(): bool
    {
        foreach ($this->reservaAmarra as $reserva) {
            if ($reserva->isAceptada() == true || $reserva->isAceptada() == null) {
                return true;
            }
        }
        return false;
    
    }
   
}
