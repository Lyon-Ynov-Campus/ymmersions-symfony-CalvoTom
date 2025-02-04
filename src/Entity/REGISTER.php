<?php

namespace App\Entity;

use App\Repository\REGISTERRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: REGISTERRepository::class)]
class REGISTER
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?USER $id_user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TEAM $id_team = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOURNAMENT $id_tournament = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?USER
    {
        return $this->id_user;
    }

    public function setIdUser(?USER $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdTeam(): ?TEAM
    {
        return $this->id_team;
    }

    public function setIdTeam(?TEAM $id_team): static
    {
        $this->id_team = $id_team;

        return $this;
    }

    public function getIdTournament(): ?TOURNAMENT
    {
        return $this->id_tournament;
    }

    public function setIdTournament(?TOURNAMENT $id_tournament): static
    {
        $this->id_tournament = $id_tournament;

        return $this;
    }
}
