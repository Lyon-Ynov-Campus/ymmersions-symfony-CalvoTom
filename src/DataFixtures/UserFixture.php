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

        
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        $team = new TEAM();
        $team->setName("Undifined");
        $manager->persist($team);

        $faker = Factory::create();
        
        
        $users = [];
        for ($i = 1; $i <= 16; $i++) {
            $user = new USER();
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setDateBirth($faker->dateTimeThisCentury);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);

            
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user;
        }
        
        $tournament = new TOURNAMENT();
        $tournament->setName("Grand Tournament");
        $tournament->setDateStartRegister(new \DateTime('2025-01-01'));
        $tournament->setDateEndRegister(new \DateTime('2025-01-31'));
        $tournament->setDateStart(new \DateTime('2025-02-01'));
        $tournament->setNbMaxTeam(8);
        $tournament->setNbMaxByTeam(2);
        $manager->persist($tournament);

        
        $teams = [];
        for ($i = 1; $i <= 8; $i++) {
            $team = new TEAM();
            $team->setName("Team $i");
            $manager->persist($team);
            $teams[] = $team;

            
            $register1 = new REGISTER();
            $register1->setIdTeam($team);
            $register1->setIdUser($users[($i - 1) * 2]);
            $register1->setIdTournament($tournament); 
            $manager->persist($register1);

            $register2 = new REGISTER();
            $register2->setIdTeam($team);
            $register2->setIdUser($users[($i - 1) * 2 + 1]);
            $register2->setIdTournament($tournament); 
            $manager->persist($register2);
        }

        
        $matches = [];
        for ($i = 0; $i < 4; $i++) {
            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($teams[$i * 2]);
            $match->setIdTeam2($teams[$i * 2 + 1]);
            $match->setDate(new \DateTime('2025-02-01'));
            $match->setScoreTeam1(rand(0, 5)); 
            $match->setScoreTeam2(rand(0, 5)); 
            $match->setPhase(1);
            $manager->persist($match);
            $matches[] = $match;
        }

        
        $quartFinalMatches = [];
        for ($i = 0; $i < 2; $i++) {
            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($teams[$i * 2]); 
            $match->setIdTeam2($teams[$i * 2 + 1]);
            $match->setDate(new \DateTime('2025-02-02'));
            $match->setScoreTeam1(rand(0, 5));
            $match->setScoreTeam2(rand(0, 5));
            $match->setPhase(2);
            $manager->persist($match);
            $quartFinalMatches[] = $match;
        }

        
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
