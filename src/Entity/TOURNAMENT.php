<?php

namespace App\Entity;

use App\Repository\TOURNAMENTRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TOURNAMENTRepository::class)]
class TOURNAMENT
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start_register = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end_register = null;

    #[ORM\Column]
    private ?int $nb_max_team = null;

    #[ORM\Column]
    private ?int $nb_max_by_team = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStartRegister(): ?\DateTimeInterface
    {
        return $this->date_start_register;
    }

    public function setDateStartRegister(\DateTimeInterface $date_start_register): static
    {
        $this->date_start_register = $date_start_register;

        return $this;
    }

    public function getDateEndRegister(): ?\DateTimeInterface
    {
        return $this->date_end_register;
    }

    public function setDateEndRegister(\DateTimeInterface $date_end_register): static
    {
        $this->date_end_register = $date_end_register;

        return $this;
    }

    public function getNbMaxTeam(): ?int
    {
        return $this->nb_max_team;
    }

    public function setNbMaxTeam(int $nb_max_team): static
    {
        $this->nb_max_team = $nb_max_team;

        return $this;
    }

    public function getNbMaxByTeam(): ?int
    {
        return $this->nb_max_by_team;
    }

    public function setNbMaxByTeam(int $nb_max_by_team): static
    {
        $this->nb_max_by_team = $nb_max_by_team;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }
}
