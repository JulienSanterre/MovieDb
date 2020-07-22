<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Genre;
use App\Entity\Person;
use App\Entity\Job;
use App\Entity\Team;
use App\Entity\Department;
use App\Repository\MovieRepository;
use App\Repository\CastingRepository;
use App\Repository\PersonRepository;
use App\Repository\JobRepository;
use App\Repository\TeamRepository;
use App\Repository\GenreRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(MovieRepository $movieRepository)
    {
        return $this->render('main/index.html.twig', [
            'movies' => $movieRepository->findAllByTitle(),
        ]);
    }

    /**
     * @Route("/movie/{id}", name="movie_show")
     */
    public function show(Movie $movie)
    {
        return $this->redirectToRoute('slug_show', ['slug' => $movie->getSlug()]);
    }

    /**
     * @Route("/movie/show/{slug}", name="slug_show")
     */
    public function showSlug(Movie $movie, CastingRepository $castingRepository, TeamRepository $teamRepository, PersonRepository $PersonRepository)
    {
        $castings = $castingRepository->findCastingWithMovie($movie);
        $teams = $teamRepository->findTeamWithMovie($movie);
        $collection[] = $castings;
        $collection[] = $teams;

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
            'teams' => $teams,
            'collection' => $collection,
            'slug' => $movie->getSlug()
        ]);
    }

    /**
     * @Route("/films/liste", name="test_show")
     */
    public function showForAllListMovie(MovieRepository $movieRepository,GenreRepository $GenreRepository)
    {        
        return $this->render('movie/show_movies_list.html.twig', [
            'movies' => $movieRepository->findAllByTitle(),
            'genres' => $GenreRepository->findAllByName(),
        ]);
    }

    /**
     * @Route("/movie/admin/list", name="movie_list_show")
     */
    public function showListMovie(MovieRepository $movieRepository)
    {        
        return $this->render('admin/show_movies_list.html.twig', [
            'movies' => $movieRepository->findAllByTitle(),
        ]);
    }

    /**
     * @Route("/genre", name="genre_list_show")
     */
    public function showListGenre(GenreRepository $genreRepository)
    {
        return $this->render('admin/show_genres_list.html.twig', [
            'genres' => $genreRepository->findAllByName(),
        ]);
    }

    /**
     * @Route("/genre/{apiId}", name="genre_show")
     */
    public function showGenre(Genre $genre)
    {
        return $this->render('genre/show.html.twig', [
            'genre' => $genre
        ]);
    }

    /**
     * @Route("/job", name="job_list_show")
     */
    public function showListJob(JobRepository $jobRepository)
    {
        return $this->render('admin/show_jobs_list.html.twig', [
            'jobs' => $jobRepository->findAllByName(),
        ]);
    }

    /**
     * @Route("/job/{id}", name="job_show")
     */
    public function showJob(Job $job, TeamRepository $teamRepository)
    {
        $teams = $teamRepository->findTeamWithJob($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'teams' => $teams,
        ]);
    }

    /**
     * @Route("/department", name="department_list_show")
     */
    public function showListDepartment(DepartmentRepository $departmentRepository)
    {
        return $this->render('admin/show_departments_list.html.twig', [
            'departments' => $departmentRepository->findAllByName(),
        ]);
    }

    /**
     * @Route("/department/{id}", name="department_show")
     */
    public function showDepartment(Department $department)
    {
        return $this->render('department/show.html.twig', [
            'department' => $department
        ]);
    }

    /**
     * @Route("/person/{apiId}", name="person_show")
     */
    public function showPerson(Person $person, CastingRepository $castingRepository, TeamRepository $teamRepository)
    {
        $castings = $castingRepository->findCastingWithPerson($person);
        $teams = $teamRepository->findTeamWithPerson($person);

        return $this->render('person/show.html.twig', [
            'person' => $person,
            'castings' => $castings,
            'teams' => $teams,
        ]);
    }

    /**
     * @Route("/person", name="person_list_show")
     */
    public function showListPerson(PersonRepository $personRepository)
    {
        return $this->render('admin/show_persons_list.html.twig', [
            'persons' => $personRepository->findAllByName(),
        ]);
    }

    /**
     * @Route("/team", name="team_list_show")
     */
    public function showListTeam(TeamRepository $teamRepository)
    {
        return $this->render('admin/show_teams_list.html.twig', [
            'teams' => $teamRepository->findAllById(),
        ]);
    }

    /**
     * @Route("/team/{id}", name="team_show")
     */
    public function showTeam(Job $job, Team $team)
    {

        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/casting", name="casting_list_show")
     */
    public function showListCasting(CastingRepository $castingRepository)
    {
        return $this->render('admin/show_castings_list.html.twig', [
            'castings' => $castingRepository->findAllById(),
        ]);
    }

    /**
     * @Route("/casting/{id}", name="casting_show")
     */
    public function showCasting(Casting $casting)
    {
        return $this->render('casting/show.html.twig', [
            'casting' => $casting,
        ]);
    }
}
