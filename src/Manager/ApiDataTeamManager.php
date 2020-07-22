<?php

namespace App\Manager;

use App\Entity\Department;
use App\Entity\Job;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Team;

use App\Repository\DepartmentRepository;
use App\Repository\JobRepository;

use Doctrine\ORM\EntityManagerInterface;

class ApiDataTeamManager
{

    private $em;
    private $movie;
    private $person;
    private $department;
    private $team;

    private $castingResult;

    private $departmentRepository;
    private $jobRepository;

    public function __construct(EntityManagerInterface $em, DepartmentRepository $departmentRepository, JobRepository $jobRepository)
    {
        $this->em = $em;
        $this->departmentRepository = $departmentRepository;
        $this->jobRepository = $jobRepository;
    }

    public function getTeamApiIdFromApi($index)
    {
        if(isset ($this->teamResult["teams"][$index])){
            $apiId = $this->teamResult["teams"][$index]["id"];
        }else{
            $apiId = NULL ;
        }
        return $apiId;
    }

    public function getTeamNameFromApi($index)
    {
        if(isset ($this->teamResult["teams"][$index])){
            $teamName = $this->teamResult["teams"][$index]["name"];
        }else{
            $teamName = NULL ;
        }
        return $teamName;
    }

    public function setTeamDataApi($castingResult, Person $person, Movie $movie)
    {
        $this->movie = $movie;
        $this->person = $person; 
        $this->castingResult = $castingResult;

        $team = new Team();
        $team->setPerson($this->person);
        $team->setMovie($this->movie);

        if($this->departmentRepository->isFound($this->castingResult["department"]) != NULL){
            $this->department = $this->departmentRepository->isFound($this->castingResult["department"]);
        }else{
            $department = new Department;
            $department->setName($this->castingResult["department"]);
            $this->department = $department;                  
        }
        $this->em->persist($this->department);
        $this->em->flush();

        if($this->jobRepository->isFound($this->department) != NULL){
            $this->job = $this->jobRepository->isFound($this->department);
        }else{
            $job = new Job();
            $job->setName($this->castingResult["job"]);
            $job->setDepartment($this->department);
            $this->job = $job;
        }
        $team->setJob($this->job);
        $this->team = $team;

        $this->em->persist($this->job);
        $this->em->flush();

        $this->em->persist($this->team);
        $this->em->flush();

        return $team;
    }
}