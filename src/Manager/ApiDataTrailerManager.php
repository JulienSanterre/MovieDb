<?php

namespace App\Manager;

class ApiDataTrailerManager
{
    private $trailerResult;
    private $movieId;
    private $youtubeUrl;

    protected $apiVersion = "3";
    protected $apiKey = "e878038b2cfe16d637b35a6feffdff70";
    protected $langRequest = "&language=fr-FR&region=FR";
    protected $youtubeBasePath = "https://www.youtube.com/embed/";  

    public function trailerDataFromApi()
    { 
        $content = file_get_contents("https://api.themoviedb.org/".$this->apiVersion."/movie/".$this->movieId."/videos?api_key=".$this->apiKey."&".$this->langRequest);
        $result  = json_decode($content,true);
        $this->trailerResult = $result;
    }

    public function getTrailerUrlFromApi()
    {
        if(isset ($this->trailerResult["results"][0])){
            $videoUrl = $this->trailerResult["results"][0]["key"];
        }else{
            $videoUrl = NULL ;
        }
        return $videoUrl;
    }

    public function setYoutubeDataApi($id)
    {
        $this->movieId = $id;
        $this->trailerDataFromApi();
        $key = $this->getTrailerUrlFromApi();
        $this->youtubeUrl = $this->youtubeBasePath.$key;
        return $this->youtubeUrl;
    }
}