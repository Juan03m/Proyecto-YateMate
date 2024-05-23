<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $solicitado = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $solicitante = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Embarcacion $embarcacion = null;

    #[ORM\ManyToOne(inversedBy: 'solicitudes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bien $bien = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolicitado(): ?Usuario
    {
        return $this->solicitado;
    }

    public function setSolicitado(?Usuario $solicitado): static
    {
        $this->solicitado = $solicitado;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

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

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): static
    {
        $this->bien = $bien;

        return $this;
    }

  
}
