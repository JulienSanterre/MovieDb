<?php

namespace App\Manager;

use App\Entity\Genre;
use App\Repository\GenreRepository;

use Doctrine\ORM\EntityManagerInterface;

class ApiDataGenreManager
{
    private $genreResult;
    protected $apiVersion = "3";
    protected $apiKey = "e878038b2cfe16d637b35a6feffdff70";
    protected $langRequest = "&language=fr-FR&region=FR";

    private $em;
    private $genreRepository;

    public function __construct(EntityManagerInterface $em, GenreRepository $genreRepository)
    {
        $this->em = $em;
        $this->genreRepository = $genreRepository;
    }

    public function genreDataFromApi()
    { 
        $content = file_get_contents("https://api.themoviedb.org/".$this->apiVersion."/genre/movie/list?api_key=".$this->apiKey."&".$this->langRequest);
        $result  = json_decode($content,true);
        $this->genreResult = $result;
    }

    public function getGenreApiIdFromApi($index)
    {
        if(isset ($this->genreResult["genres"][$index])){
            $apiId = $this->genreResult["genres"][$index]["id"];
        }else{
            $apiId = NULL ;
        }
        return $apiId;
    }

    public function getGenreNameFromApi($index)
    {
        if(isset ($this->genreResult["genres"][$index])){
            $genreName = $this->genreResult["genres"][$index]["name"];
        }else{
            $genreName = NULL ;
        }
        return $genreName;
    }

    public function setGenreDataApi()
    {
        $this->genreDataFromApi();
        foreach($this->genreResult["genres"] as $index=>$result){
            if($this->genreRepository->isFound($this->genreResult["genres"][$index]["name"]) == NULL){
                $genre = new Genre();

                $genre->setName($this->getGenreNameFromApi($index));
                $genre->setApiId($this->getGenreApiIdFromApi($index));

                $this->em->persist($genre);
                $this->em->flush();
            }
        }
    }
}