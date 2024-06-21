<?php

namespace App\Entity;

use App\Repository\ReservaAmarraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaAmarraRepository::class)]
class ReservaAmarra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservaAmarra', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?PublicacionAmarra $publicacionAmarra = null;

    #[ORM\Column(nullable: true)]
    private ?bool $aceptada = null;

    #[ORM\ManyToOne(inversedBy: 'reservaAmarras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $solicitante = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaDesde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechaHasta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicacionAmarra(): ?PublicacionAmarra
    {
        return $this->publicacionAmarra;
    }

    public function setPublicacionAmarra(PublicacionAmarra $publicacionAmarra): static
    {
        $this->publicacionAmarra = $publicacionAmarra;

        return $this;
    }

    public function isAceptada(): ?bool
    {
        return $this->aceptada;
    }

    public function setAceptada(?bool $aceptada): static
    {
        $this->aceptada = $aceptada;

        return $this;
    }

    public function getSolicitante(): ?Usuario
    {
        return $this->solicitante;
    }

    public function setSolicitante(?Usuario $solicitante): static
    {
        $this->solicitante = $solicitante;

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
}
