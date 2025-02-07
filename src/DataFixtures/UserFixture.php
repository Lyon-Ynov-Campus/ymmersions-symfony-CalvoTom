<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\USER;
use App\Entity\TEAM;
use App\Entity\REGISTER;
use App\Entity\MATCHS;
use App\Entity\TOURNAMENT;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager) : void
    {
        $user = new USER();
        $user->setName('admin');
        $user->setEmail('admin@gmail.com');
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $user->setDateBirth(new \DateTime('2025-02-06'));

        // Hachage du mot de passe avec le password hasher
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        $team = new TEAM();
        $team->setName("Undifined");
        $manager->persist($team);

        $faker = Factory::create();
        
        // Créer les utilisateurs (joueurs)
        $users = [];
        for ($i = 1; $i <= 16; $i++) {
            $user = new USER();
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setDateBirth($faker->dateTimeThisCentury);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);

            // Hachage du mot de passe avec le password hasher
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user;
        }

        // Créer le tournoi unique : Grand Tournament
        $tournament = new TOURNAMENT();
        $tournament->setName("Grand Tournament");
        $tournament->setDateStartRegister(new \DateTime('2025-01-01'));
        $tournament->setDateEndRegister(new \DateTime('2025-01-31'));
        $tournament->setDateStart(new \DateTime('2025-02-01'));
        $tournament->setNbMaxTeam(8);
        $tournament->setNbMaxByTeam(2);
        $manager->persist($tournament);

        // Créer les équipes
        $teams = [];
        for ($i = 1; $i <= 8; $i++) {
            $team = new TEAM();
            $team->setName("Team $i");
            $manager->persist($team);
            $teams[] = $team;

            // Associer des joueurs à l'équipe (chaque équipe a 2 joueurs)
            $register1 = new REGISTER();
            $register1->setIdTeam($team);
            $register1->setIdUser($users[($i - 1) * 2]);
            $register1->setIdTournament($tournament); // Associer au tournoi unique
            $manager->persist($register1);

            $register2 = new REGISTER();
            $register2->setIdTeam($team);
            $register2->setIdUser($users[($i - 1) * 2 + 1]);
            $register2->setIdTournament($tournament); // Associer au tournoi unique
            $manager->persist($register2);
        }

        // Créer les matchs de la phase 1 (Huitième de finale)
        $matches = [];
        for ($i = 0; $i < 4; $i++) {
            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($teams[$i * 2]);
            $match->setIdTeam2($teams[$i * 2 + 1]);
            $match->setDate(new \DateTime('2025-02-01'));
            $match->setScoreTeam1(rand(0, 5)); // Score aléatoire pour l'équipe 1
            $match->setScoreTeam2(rand(0, 5)); // Score aléatoire pour l'équipe 2
            $match->setPhase(1);
            $manager->persist($match);
            $matches[] = $match;
        }

        // Créer les matchs de la phase 2 (Quart de finale)
        $quartFinalMatches = [];
        for ($i = 0; $i < 2; $i++) {
            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($teams[$i * 2]); // Choisir les équipes gagnantes
            $match->setIdTeam2($teams[$i * 2 + 1]);
            $match->setDate(new \DateTime('2025-02-02'));
            $match->setScoreTeam1(rand(0, 5));
            $match->setScoreTeam2(rand(0, 5));
            $match->setPhase(2);
            $manager->persist($match);
            $quartFinalMatches[] = $match;
        }

        // Créer les matchs de la phase 3 (Demi-finales)
        $semiFinalMatches = [];
        for ($i = 0; $i < 1; $i++) {
            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($teams[$i * 2]);
            $match->setIdTeam2($teams[$i * 2 + 1]);
            $match->setDate(new \DateTime('2025-02-03'));
            $match->setScoreTeam1(rand(0, 5));
            $match->setScoreTeam2(rand(0, 5));
            $match->setPhase(3);
            $manager->persist($match);
            $semiFinalMatches[] = $match;
        }

        $manager->flush();
    }
}
