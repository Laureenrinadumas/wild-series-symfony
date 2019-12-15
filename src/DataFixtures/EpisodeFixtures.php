<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++){
            $episode = new Episode();
            $slugify = new Slugify();
            $episode->setTitle($faker->text(20));
            $episode->setNumber($faker->numberBetween(1,9));
            $episode->setSynopsis($faker->text(300));
            $episode->setSlug($slugify->generate($episode->getTitle()));
            $number = rand(0, 10);
            $episode->setSeason($this->getReference('season_' . $number));
            $manager->persist($episode);
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
        return [SeasonFixtures::class];
    }


}