<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }

        $form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        return $this->render(
            'wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]
        );
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @Route("/show/{slug<^[ a-zA-Z0-9-é]+$>}", defaults={"slug" = null}, name="show")
     * @param string $slug The slugger
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table .');
        }
        $slug = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($slug)),
                '-')
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy([
                'title' => mb_strtolower($slug)
            ]);
        if (!$program) {
            throw $this->createNotFoundException('No program with ' . $slug . ' title, found in program\'s table.');
        }
        $seasons = $program->getSeasons();
        $actors = $program->getActors();

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
            'seasons' => $seasons,
            'actors' => $actors,
        ]);
    }

    /**
     * 3 last programs in category
     * @Route("/category/{categoryName}",  defaults={"categoryName"=null}, name="show_category")
     * @param string|null $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this->createNotFoundException('No category has been sent to find a category in category\'s table.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($categoryName)),
                '-')
        );
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        if (!$category) {
            throw $this->createNotFoundException('No category' . $categoryName . 'found in category\'s table.');
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'asc']);

        if (!$programs) {
            throw $this->createNotFoundException('No program found un program\'s table');
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/season/{id}", defaults={"id" = null}, name="show_season")
     * @param int $id
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No season has been find in season\'s table.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();
        if (!$season) {
            throw $this->createNotFoundException('No season found with '.$id.' in Season\'s table.');
        }

        return $this->render('wild/season.html.twig', [
            'program'  => $program,
            'season'   => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route ("/episode/{id}", name="show_episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season'  => $season,
            'program' => $program,
        ]);
    }
}


