<?php


namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $season = new Season();
            $season->setNumber($faker->unique()->numberBetween(1,20));
            $season->setYear($faker->year);
            $season->setDescription($faker->sentence(40));
            $number = rand(0,5);
            $season->setProgram($this->getReference('program_' . $number));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}