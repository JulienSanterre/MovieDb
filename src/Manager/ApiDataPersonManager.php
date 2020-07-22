<?php

namespace App\Manager;

use App\Entity\Person;

use App\Repository\PersonRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ApiDataPersonManager
{
    private $basePersonPicturePath;
    protected $apiVersion;
    protected $apiKey;
    protected $langRequest;

    private $em;
    private $flashBag;
    private $personRepository;

    private $castingData;
    private $personData;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flashBag, PersonRepository $personRepository)
    {
        $this->em = $em;
        $this->flashBag = $flashBag;
        $this->personRepository = $personRepository;
    }

    public function personDataFromApi($currentPersonId)
    {
        $content = file_get_contents("https://api.themoviedb.org/".$this->apiVersion."/person/".$currentPersonId."?api_key=".$this->apiKey."&".$this->langRequest);
        $result  = json_decode($content,true);
        $this->personData = $result;
    }

    public function getPersonNameFromApi(){
        
        if(isset ($this->personData["name"])){
            $name = $this->personData["name"];
        }else{
            $name = NULL ;
        }
        return $name;
    }

    public function getPersonApiIdFromApi()
    {
        if(isset ($this->personData["id"])){
            $apiId = $this->personData["id"];
        }else{
            $apiId = NULL ;
        }
        return $apiId;
    }

    public function getPersonGenderFromApi()
    {
        if(isset ($this->personData["gender"])){
            $gender = $this->personData["gender"];
        }else{
            $gender = NULL ;
        }
        return $gender;
    }

    public function getPersonPictureFromApi()
    {
        if(isset ($this->personData["profile_path"])){
            $picture = $this->basePersonPicturePath . $this->personData["profile_path"];
        }else{
            $picture = "https://www.nocowboys.co.nz/images/v3/no-image-available.png";
        }
        return $picture;
    }

    public function getPersonBirthdayFromApi()
    {
        if(isset ($this->personData["birthday"])){
            $birthday = $this->personData["birthday"];
        }else{
            $birthday = NULL ;
        }
        return $birthday;
    }

    public function getPersonDeathdayFromApi()
    {
        if(isset ($this->personData["deathday"])){
            $deathday = $this->personData["deathday"];
        }else{
            $deathday = NULL ;
        }
        return $deathday;
    }

    public function getPersonPlaceOfBirthdayFromApi()
    {
        if(isset ($this->personData["place_of_birth"])){
            $placeOfBirthday = $this->personData["place_of_birth"];
        }else{
            $placeOfBirthday = NULL ;
        }
        return $placeOfBirthday;
    }

    public function getPersonPopularityFromApi()
    {
        if(isset ($this->personData["popularity"])){
            $popularity = $this->personData["popularity"];
        }else{
            $popularity = NULL ;
        }
        return $popularity;
    }

    public function getPersonBiographyFromApi()
    {
        if(isset ($this->personData["biography"])){
            $biography = $this->personData["biography"];
        }else{
            $biography = NULL ;
        }
        return $biography;
    }

    public function setPersonDataApi(Array $castingData, $apiVersion, $apiKey, $langRequest, $basePicturePath, $index, $option)
    {
        $this->basePersonPicturePath = $basePicturePath;
        $this->apiVersion = $apiVersion;
        $this->apiKey = $apiKey;
        $this->langRequest = $langRequest;
        $this->castingData = $castingData;
        if($option == "cast" && $this->castingData[$option] != null){
                $this->PersonDataFromApi($this->castingData["cast"][$index]["id"]);
                    $person = new Person();

                    $person->setName($this->getPersonNameFromApi());
                    $person->setApiId($this->getPersonApiIdFromApi());
                    $person->setGender($this->getPersonGenderFromApi());
                    $person->setPicture($this->getPersonPictureFromApi());
                    $person->setPlaceOfBirthday($this->getPersonPlaceOfBirthdayFromApi());
                    $person->setPopularity($this->getPersonPopularityFromApi());
                    $person->setBiography($this->getPersonBiographyFromApi());

                    $person->setUpdatedAt(new \DateTime('now'));

                    if ($this->getPersonBirthdayFromApi() != null) {
                        $person->setBirthday(\DateTime::createFromFormat('Y-m-d', $this->getPersonBirthdayFromApi()));
                    }
                    if ($this->getPersonDeathdayFromApi() != null) {
                        $person->setDeathday(\DateTime::createFromFormat('Y-m-d', $this->getPersonDeathdayFromApi()));
                    }

                    return $person;

                // TODO : CHANGE MSG OR STRUCTURE TOO MUCH MSG  (prefer Nbr msg added + title of movie index 0  + redirect to this movie)
                // $this->flashBag->add('success', 'Le Film '.$this->getMovieTitleFromApi($index).' a été ajouté a la base de donnée');
        }
        if($option == "crew" && $this->castingData["crew"] != null){
                $this->PersonDataFromApi($this->castingData["crew"][$index]["id"]);
                    $person = new Person();

                    $person->setName($this->getPersonNameFromApi());
                    $person->setApiId($this->getPersonApiIdFromApi());
                    $person->setGender($this->getPersonGenderFromApi());
                    $person->setPicture($this->getPersonPictureFromApi());
                    $person->setPlaceOfBirthday($this->getPersonPlaceOfBirthdayFromApi());
                    $person->setPopularity($this->getPersonPopularityFromApi());
                    $person->setBiography($this->getPersonBiographyFromApi());
    
                    $person->setUpdatedAt(new \DateTime('now'));
    
                    if ($this->getPersonBirthdayFromApi() != null) {
                        $person->setBirthday(\DateTime::createFromFormat('Y-m-d', $this->getPersonBirthdayFromApi()));
                    }
                    if ($this->getPersonDeathdayFromApi() != null) {
                        $person->setDeathday(\DateTime::createFromFormat('Y-m-d', $this->getPersonDeathdayFromApi()));
                    }

                    return $person;
    
                    // TODO : CHANGE MSG OR STRUCTURE TOO MUCH MSG  (prefer Nbr msg added + title of movie index 0  + redirect to this movie)
                    // $this->flashBag->add('success', 'Le Film '.$this->getMovieTitleFromApi($index).' a été ajouté a la base de donnée');
        }
    }
}