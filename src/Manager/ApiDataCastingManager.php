<?php

namespace App\Manager;

use App\Entity\Casting;
use App\Manager\ApiDataPersonManager;
use App\Manager\ApiDataTeamManager;
use App\Repository\CastingRepository;
use App\Repository\PersonRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ApiDataCastingManager
{
    private $castingResult;
    protected $apiVersion = "3";
    protected $apiKey = "e878038b2cfe16d637b35a6feffdff70";
    protected $basePicturePath = "https://image.tmdb.org/t/p/w500";
    protected $langRequest = "&language=fr-FR&region=FR";

    private $em;
    private $flashBag;
    private $castingRepository;
    private $personRepository;
    private $apiDataPersonManager;
    private $apiDataTeamManager;

    private $counterApi;

    protected $movie;
    protected $person;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flashBag, CastingRepository $castingRepository, 
    ApiDataPersonManager $apiDataPersonManager, PersonRepository $personRepository, ApiDataTeamManager $apiDataTeamManager)
    {
        $this->em = $em;
        $this->flashBag = $flashBag;
        $this->castingRepository = $castingRepository;
        $this->apiDataPersonManager = $apiDataPersonManager;
        $this->apiDataTeamManager = $apiDataTeamManager;
        $this->personRepository = $personRepository;
    }

    public function castingDataFromApi($currentDbMovieId)
    {
        $content = file_get_contents("https://api.themoviedb.org/".$this->apiVersion."/movie/".$currentDbMovieId."/credits?api_key=".$this->apiKey."&".$this->langRequest);
        $result  = json_decode($content,true);
        $this->castingResult = $result;
    }

    public function getCastingRoleFromApi($index)
    {
        if(isset ($this->castingResult["cast"][$index])){
            $role = $this->castingResult["cast"][$index]["character"];
        }else{
            $role = NULL ;
        }
        return $role;
    }

    public function getCastingRoleCrewFromApi($index){   
        $role = NULL ;
        return $role;
    }

    public function getCastingApiPersonIdFromApi($index)
    {
        if(isset ($this->castingResult["cast"][$index])){
            $castingApiId = $this->castingResult["cast"][$index]["id"];
        }else{
            $castingApiId = NULL ;
        }
        return $castingApiId;
    }

    public function getCastingApiPersonIdCrewFromApi($index)
    {
        if(isset ($this->castingResult["crew"][$index])){
            $castingApiId = $this->castingResult["crew"][$index]["id"];
        }else{
            $castingApiId = NULL ;
        }
        return $castingApiId;
    }

    public function getCastingCreditOrderFromApi($index)
    {
        if(isset ($this->castingResult["cast"][$index])){
            $castingCreditOrder = $this->castingResult["cast"][$index]["order"];
        }else{
            $castingCreditOrder = NULL ;
        }
        return $castingCreditOrder;
    }

    public function getCastingCreditOrderCrewFromApi()
    {
        $castingCreditOrder = NULL ;
        return $castingCreditOrder;
    }

    public function setCastingDataApi($movie)
    {
        $currentDbMovieId = $movie->getApiMovieId();
        $this->movie = $movie;
        $this->castingDataFromApi($currentDbMovieId);
        if($this->castingResult["cast"] != null){
            foreach ($this->castingResult["cast"] as $index=>$result) {
                // Need contrôle of nbr request cause is limit to 30 each 10 second
                if($this->counterApi >= 25){
                    sleep(10);
                    $this->counterApi = 0;
                }
                $casting = new Casting();

                $casting->setMovie($this->movie);
                $casting->setRole($this->getCastingRoleFromApi($index));
                $casting->setCreditOrder($this->getCastingCreditOrderFromApi($index));

                // TODO set person for $casting->setPerson and get return of class person object to set (need object person)

                $this->person = $this->apiDataPersonManager->setPersonDataApi($this->castingResult, $this->apiVersion, $this->apiKey, $this->langRequest, $this->basePicturePath, $index, "cast");
                
                if ($this->personRepository->isFound($this->getCastingApiPersonIdFromApi($index)) == null) {

                    $this->em->persist($this->person);
                    $this->em->flush();
                }else{
                    $this->person = $this->personRepository->isFound($this->getCastingApiPersonIdFromApi($index));
                }

                $casting->setPerson($this->person);
                if ($this->person != null) {
                    $this->em->persist($casting);
                    $this->em->flush();
                }

                $this->counterApi++;

                // TODO : CHANGE MSG OR STRUCTURE TOO MUCH MSG  (prefer Nbr msg added + title of movie index 0  + redirect to this movie)
                // $this->flashBag->add('success', 'Le Film '.$this->getMovieTitleFromApi($index).' a été ajouté a la base de donnée');
            }
        }
        if($this->castingResult["crew"] != null){
            foreach ($this->castingResult["crew"] as $index=>$result) {
                // Need contrôle of nbr request cause is limit to 40 each 10 second
                if($this->counterApi >= 25){
                    sleep(10);
                    $this->counterApi = 0;
                }
                $casting = new Casting();

                $casting->setMovie($this->movie);
                $casting->setRole($this->getCastingRoleCrewFromApi($index));
                
                $casting->setCreditOrder($this->getCastingCreditOrderCrewFromApi());

                $this->person = $this->apiDataPersonManager->setPersonDataApi($this->castingResult, $this->apiVersion, $this->apiKey, $this->langRequest, $this->basePicturePath, $index, "crew");

                
                if ($this->personRepository->isFound($this->getCastingApiPersonIdCrewFromApi($index)) == null) {
                    $this->em->persist($this->person);
                    $this->em->flush();
                }else{
                    $this->person = $this->personRepository->isFound($this->getCastingApiPersonIdCrewFromApi($index));
                }

                $casting->setPerson($this->person);
                if ($this->person != null) {
                    $this->em->persist($casting);
                    $this->em->flush();
                }

                if ($this->person != null) {
                    $this->apiDataTeamManager->setTeamDataApi($this->castingResult["crew"][$index], $this->person, $this->movie);
                }

                $this->counterApi++;
            }
        }
    }
}