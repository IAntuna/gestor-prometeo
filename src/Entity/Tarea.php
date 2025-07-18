<?php

namespace App\Entity;

use App\Repository\TareaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: TareaRepository::class)]
class Tarea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 50)]
    private ?string $estado = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $plazo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fechaInicio = null; // <-- Nueva propiedad

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fechaFin = null; // <-- Añadido ahora

    #[ORM\Column]
    private ?float $horasEstimadas = null;

    #[ORM\Column]
    private ?float $horasRealizadas = null;

    #[ORM\ManyToOne(inversedBy: 'tareas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proyecto $proyecto = null;

    #[ORM\ManyToOne(inversedBy: 'tareas')]
    private ?Tipologia $tipologia = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'tareas')]
    private Collection $users;

    /**
     * @var Collection<int, RegistroDeHoras>
     */
    #[ORM\OneToMany(targetEntity: RegistroDeHoras::class, mappedBy: 'tarea')]
    private Collection $registroDeHoras;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->registroDeHoras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPlazo(): ?\DateTimeImmutable
    {
        return $this->plazo;
    }

    public function setPlazo(\DateTimeImmutable $plazo): static
    {
        $this->plazo = $plazo;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeImmutable
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?\DateTimeImmutable $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeImmutable
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeImmutable $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getHorasEstimadas(): ?float
    {
        return $this->horasEstimadas;
    }

    public function setHorasEstimadas(float $horasEstimadas): static
    {
        $this->horasEstimadas = $horasEstimadas;

        return $this;
    }

    public function getHorasRealizadas(): ?float
    {
        return $this->horasRealizadas;
    }

    public function setHorasRealizadas(float $horasRealizadas): static
    {
        $this->horasRealizadas = $horasRealizadas;

        return $this;
    }

    public function getProyecto(): ?Proyecto
    {
        return $this->proyecto;
    }

    public function setProyecto(?Proyecto $proyecto): static
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    public function getTipologia(): ?Tipologia
    {
        return $this->tipologia;
    }

    public function setTipologia(?Tipologia $tipologia): static
    {
        $this->tipologia = $tipologia;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTarea($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTarea($this);
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
            $registroDeHora->setTarea($this);
        }

        return $this;
    }

    public function removeRegistroDeHora(RegistroDeHoras $registroDeHora): static
    {
        if ($this->registroDeHoras->removeElement($registroDeHora)) {
            if ($registroDeHora->getTarea() === $this) {
                $registroDeHora->setTarea(null);
            }
        }

        return $this;
    }
}
