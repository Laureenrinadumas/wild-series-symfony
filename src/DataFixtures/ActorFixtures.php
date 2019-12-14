<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln' => [
            'program' => ["program_0", "program_5"]
        ],
        'Norman Reedus' => [
            'program' => ["program_0"],
        ],
        'Lauren Cohan' => [
            'program' => ["program_0"],
        ],
        'Danai Gurira' => [
            'program' => ["program_0"],
        ],
    ];

    public function load(ObjectManager $manager)
    {

        foreach (self::ACTORS as $actorName => $data){
            $actor = new Actor();
            $actor->setName($actorName);
            foreach ($data['program'] as $program){
                $actor->addProgram($this->getReference($program));
            }
            $manager->persist($actor);
            $this->addReference('actor_' . $actorName, $actor);
        }

        $faker  =  Faker\Factory::create('en_US');
            for ($i = 0; $i < 50; $i++) {
                $actor = new Actor();
                $actor->setName($faker->name);
                $number= rand(0,4);
                $actor->addProgram($this->getReference('program_' . $number));
                $manager->persist($actor);
            }
            $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}