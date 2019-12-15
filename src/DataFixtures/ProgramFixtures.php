<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{   const PROGRAMS = [
    'Walking Dead' => [
        'summary' => 'Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.',
        'category' => 'category_4',
    ],
    'The big bang theory' => [
        'summary' => 'Leonard Hofstadter et Sheldon Cooper vivent en colocation à Pasadena, ville de l\'agglomération de Los Angeles. Ce sont tous deux des physiciens surdoués, « geeks » de surcroît. C\'est d\'ailleurs autour de cela qu\'est axée la majeure partie comique de la série. Ils partagent quasiment tout leur temps libre avec leurs deux amis Howard Wolowitz et Rajesh Koothrappali pour jouer à des jeux vidéo comme Halo, organiser un marathon de la saga Star Wars, jouer à des jeux de société comme le Boggle klingon ou de rôles tel que Donjons et Dragons, voire discuter de théories scientifiques très complexes. Leur univers routinier est perturbé lorsqu\'une jeune femme, Penny, s\'installe dans l\'appartement d\'en face. Leonard a immédiatement des vues sur elle et va tout faire pour la séduire ainsi que l\'intégrer au groupe et à son univers, auquel elle ne connaît rien.',
        'category' => 'category_0',
    ],
    'Seven Deadly Sins' => [
        'summary' => 'Liones, royaume de Britannia. Le Grand Maître des Chevaliers Sacrés Zaratras a été sauvagement assassiné, et les fautifs seraient un ordre de chevaliers légendaires au nombre de sept au service du roi qui voulaient renverser le trône. Dix ans plus tard, une jeune fille nommée Elizabeth Liones part à la recherche de ces mystérieux chevaliers qui faisaient autrefois la fierté de Liones : les Seven Deadly Sins, un groupe regroupant sept grands criminels choisis par le roi en personne, pour déjouer un complot manigancé par les chevaliers sacrés contre la royauté. C’est alors qu’elle échoue au Boar Hat, une taverne dont le propriétaire est un petit garçon accompagné de son cochon Hawk. Mais quelle ne fut pas sa surprise lorsqu’elle découvre que l’enfant à la tête de la taverne n’est autre que le Péché du Dragon de la Colère, chef des Seven Deadly Sins, Meliodas ! C’est alors que débutent leurs aventures à la recherche des six autres Deadly Sins, afin de sauver Liones !',
        'category' => 'category_2',
    ],
    'Vikings' => [
        'summary' => 'Les exploits d\'un groupe de Vikings mené par Ragnar Lothbrok, l\'un des vikings les plus populaires de son époque et au destin semi-légendaire, sont narrés par la série. Ragnar serait d\'origine norvégienne et suédoise, selon les sources. Il est supposé avoir unifié les clans vikings en un royaume aux frontières indéterminées à la fin du viiie siècle (le roi Ecbert mentionne avoir vécu à la cour du roi Charlemagne, sacré empereur en l\'an 800). Mais il est surtout connu pour avoir été le promoteur des tout premiers raids vikings en terres chrétiennes, saxonnes, franques ou celtiques.',
        'category' => 'category_1',
    ],
    'Dragon Ball Z' => [
        'summary' => 'Dragon Ball Z se déroule cinq ans après le mariage de Son Goku et de Chichi, désormais parents de Son Gohan2. Raditz, un mystérieux guerrier extraterrestre, qui s\'avère être le frère de Son Goku, arrive sur Terre pour retrouver ce dernier. Ce dernier apprend qu\'il vient d\'une planète de guerriers redoutables dont il ne reste plus que quatre survivants, et qu\'il avait été envoyé sur la planète Terre dans le but de la conquérir (une chute alors qu\'il était enfant lui aurait fait perdre la mémoire).',
        'category' => 'category_2',
    ],
    'Fear The Walking Dead' => [
        'summary' => 'La série se déroule au tout début de l épidémie relatée dans la série mère The Walking Dead et se passe dans la ville de Los Angeles, et non à Atlanta. Madison est conseillère dans un lycée de Los Angeles. Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia, excellente élève qui découvre les premiers émois amoureux, et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.',
        'category' => 'category_4',
    ],
];
    public function load(ObjectManager $manager)
    {
        $i =0;
        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $slugify = new Slugify();
            $program->setTitle($title);
            $program->setSummary($data['summary']);
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $manager->persist($program);
            $this->addReference('program_' . $i , $program);
            $i++;
            $program->setCategory($this->getReference($data['category']));
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return[CategoryFixtures::class];
    }
}