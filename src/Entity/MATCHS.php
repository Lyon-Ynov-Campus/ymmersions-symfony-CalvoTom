<?php

namespace App\Entity;

use App\Repository\MATCHSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MATCHSRepository::class)]
class MATCHS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOURNAMENT $id_tournament = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TEAM $id_team_1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TEAM $id_team_2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_team_1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_team_2 = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdTeam1(): ?TEAM
    {
        return $this->id_team_1;
    }

    public function setIdTeam1(?TEAM $id_team_1): static
    {
        $this->id_team_1 = $id_team_1;

        return $this;
    }

    public function getIdTeam2(): ?TEAM
    {
        return $this->id_team_2;
    }

    public function setIdTeam2(?TEAM $id_team_2): static
    {
        $this->id_team_2 = $id_team_2;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getScoreTeam1(): ?int
    {
        return $this->score_team_1;
    }

    public function setScoreTeam1(?int $score_team_1): static
    {
        $this->score_team_1 = $score_team_1;

        return $this;
    }

    public function getScoreTeam2(): ?int
    {
        return $this->score_team_2;
    }

    public function setScoreTeam2(?int $score_team_2): static
    {
        $this->score_team_2 = $score_team_2;

        return $this;
    }
}
