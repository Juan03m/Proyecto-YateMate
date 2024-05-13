<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Por favor, ingresa una contraseña.")
     * @Assert\Length(min=5, minMessage="La contraseña debe tener al menos {{ limit }} caracteres.")
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Z])(?=.*\W).+$/",
     *     message="La contraseña debe contener al menos una mayúscula y un carácter especial."
     * )
     */
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Bien>
     */
    #[ORM\OneToMany(targetEntity: Bien::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $bienes;

    /**
     * @var Collection<int, Embarcacion>
     */
    #[ORM\OneToMany(targetEntity: Embarcacion::class, mappedBy: 'usuario')]
    private Collection $embarcaciones;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dni = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cuil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Amarra $amarra = null;

    /**
     * @var Collection<int, Publicacion>
     */
    #[ORM\OneToMany(targetEntity: Publicacion::class, mappedBy: 'usuario', orphanRemoval: true)]
    private Collection $publicaciones;

    public function __construct()
    {
        $this->bienes = new ArrayCollection();
        $this->embarcaciones = new ArrayCollection();
        $this->publicaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Bien>
     */
    public function getBienes(): Collection
    {
        return $this->bienes;
    }

    public function addBiene(Bien $biene): static
    {
        if (!$this->bienes->contains($biene)) {
            $this->bienes->add($biene);
            $biene->setOwner($this);
        }

        return $this;
    }

    public function removeBiene(Bien $biene): static
    {
        if ($this->bienes->removeElement($biene)) {
            // set the owning side to null (unless already changed)
            if ($biene->getOwner() === $this) {
                $biene->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Embarcacion>
     */
    public function getEmbarcaciones(): Collection
    {
        return $this->embarcaciones;
    }

    public function addEmbarcacione(Embarcacion $embarcacione): static
    {
        if (!$this->embarcaciones->contains($embarcacione)) {
            $this->embarcaciones->add($embarcacione);
            $embarcacione->setUsuario($this);
        }

        return $this;
    }

    public function removeEmbarcacione(Embarcacion $embarcacione): static
    {
        if ($this->embarcaciones->removeElement($embarcacione)) {
            // set the owning side to null (unless already changed)
            if ($embarcacione->getUsuario() === $this) {
                $embarcacione->setUsuario(null);
            }
        }

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getCuil(): ?string
    {
        return $this->cuil;
    }

    public function setCuil(?string $cuil): static
    {
        $this->cuil = $cuil;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(?string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getAmarra(): ?Amarra
    {
        return $this->amarra;
    }

    public function setAmarra(?Amarra $amarra): static
    {
        $this->amarra = $amarra;

        return $this;
    }

    /**
     * @return Collection<int, Publicacion>
     */
    public function getPublicaciones(): Collection
    {
        return $this->publicaciones;
    }

    public function addPublicacione(Publicacion $publicacione): static
    {
        if (!$this->publicaciones->contains($publicacione)) {
            $this->publicaciones->add($publicacione);
            $publicacione->setUsuario($this);
        }

        return $this;
    }

    public function removePublicacione(Publicacion $publicacione): static
    {
        if ($this->publicaciones->removeElement($publicacione)) {
            // set the owning side to null (unless already changed)
            if ($publicacione->getUsuario() === $this) {
                $publicacione->setUsuario(null);
            }
        }

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
{

    $metadata->addPropertyConstraint('password', new Length([
        'min' => 8,
        'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres',
    ]));

    $metadata->addPropertyConstraint('password', new Regex([
        'pattern' => '/^(?=.*[A-Z])(?=.*[^a-zA-Z\d]).+$',
        'message' => 'La contraseña debe contener al menos una mayúscula y un carácter especial',
    ]));
}
    /*
    public function __toString()
    {
        $usuario= 'ID  '.$this->getId().'  '.'Email '.$this->getEmail();
        return $usuario;
    }
*/
}
