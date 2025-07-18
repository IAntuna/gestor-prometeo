<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Proyecto;
use App\Entity\Tarea;
use App\Entity\RegistroDeHoras;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USUARIO_ID', fields: ['usuarioId'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_GESTOR = 'ROLE_GESTOR';
    public const ROLE_EMPLEADO = 'ROLE_EMPLEADO';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'usuario_id', length: 180, unique: true)]
    private ?string $usuarioId = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Proyecto>
     */
    #[ORM\ManyToMany(targetEntity: Proyecto::class, inversedBy: 'usuarios')]
    private Collection $proyectos;

    /**
     * @var Collection<int, Tarea>
     */
    #[ORM\ManyToMany(targetEntity: Tarea::class, mappedBy: 'users')]
    private Collection $tareas;

    /**
     * @var Collection<int, RegistroDeHoras>
     */
    #[ORM\OneToMany(targetEntity: RegistroDeHoras::class, mappedBy: 'user')]
    private Collection $registroDeHoras;

    public function __construct()
    {
        $this->proyectos = new ArrayCollection();
        $this->tareas = new ArrayCollection();
        $this->registroDeHoras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuarioId(): ?string
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(string $usuarioId): static
    {
        $this->usuarioId = $usuarioId;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->usuarioId;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return Collection<int, Proyecto>
     */
    public function getProyectos(): Collection
    {
        return $this->proyectos;
    }

    public function addProyecto(Proyecto $proyecto): static
    {
        if (!$this->proyectos->contains($proyecto)) {
            $this->proyectos->add($proyecto);
            $proyecto->addUsuario($this);
        }
        return $this;
    }

    public function removeProyecto(Proyecto $proyecto): static
    {
        if ($this->proyectos->removeElement($proyecto)) {
            $proyecto->removeUsuario($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Tarea>
     */
    public function getTareas(): Collection
    {
        return $this->tareas;
    }

    public function addTarea(Tarea $tarea): static
    {
        if (!$this->tareas->contains($tarea)) {
            $this->tareas->add($tarea);
            $tarea->addUser($this);
        }
        return $this;
    }

    public function removeTarea(Tarea $tarea): static
    {
        if ($this->tareas->removeElement($tarea)) {
            $tarea->removeUser($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, RegistroDeHoras>
     */
    public function getRegistroDeHoras(): Collection
    {
        return $this->registroDeHoras;
    }

    public function addRegistroDeHora(RegistroDeHoras $registroDeHora): static
    {
        if (!$this->registroDeHoras->contains($registroDeHora)) {
            $this->registroDeHoras->add($registroDeHora);
            $registroDeHora->setUser($this);
        }
        return $this;
    }

    public function removeRegistroDeHora(RegistroDeHoras $registroDeHora): static
    {
        if ($this->registroDeHoras->removeElement($registroDeHora)) {
            if ($registroDeHora->getUser() === $this) {
                $registroDeHora->setUser(null);
            }
        }
        return $this;
    }
}
