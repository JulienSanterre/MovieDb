<?php

namespace App\Manager;

use App\Entity\Movie;
use App\Manager\ApiDataCastingManager;
use App\Manager\ApiDataTrailerManager;
use App\Repository\MovieRepository;
use App\Repository\GenreRepository;

use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ApiDataMovieManager
{
    private $movieResult;
    protected $apiVersion = "3";
    protected $apiKey = "e878038b2cfe16d637b35a6feffdff70";
    protected $basePicturePath = "https://image.tmdb.org/t/p/w500";
    protected $langRequest = "&language=fr-FR&region=FR";

    // On aurait pu utiliser $this->slugger dans nos fonctions et ne pas préciser le service dans leurs paramètres.
    // Avec l'injection de dépendance suivante on n'aurait eu à utiliser que $this->slugger
    // Attention ça chargera toujours l'objet quelque soit la route. Donc il vaut mieux utilsier l'injection de dépendance quand onest sûr qu'on utilisera le slugger dans toutes les routes
    private $slugger;
    private $em;
    private $movieRepository;
    private $genreRepository;
    private $flashBag;
    protected $apiDataCastingManager;
    protected $apiDataTrailerManager;

    public $titleList;

    public function __construct(Slugger $slugger, EntityManagerInterface $em, MovieRepository $movieRepository, 
    FlashBagInterface $flashBag, ApiDataCastingManager $apiDataCastingManager, GenreRepository $genreRepository, 
    ApiDataTrailerManager $apiDataTrailerManager)
    {
        $this->slugger = $slugger;
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->genreRepository = $genreRepository;
        $this->flashBag = $flashBag;
        $this->apiDataCastingManager = $apiDataCastingManager;
        $this->apiDataTrailerManager = $apiDataTrailerManager;
    }

    public function movieDataFromApi($movieTitle)
    {  
        $request = str_replace(" ", "+", $movieTitle);
        $content = file_get_contents("https://api.themoviedb.org/".$this->apiVersion."/search/movie?api_key=".$this->apiKey."&query=".$request.$this->langRequest);
        $result  = json_decode($content,true);
        $this->movieResult = $result;
    }

    public function getMoviePictureUrlFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index]) && $this->movieResult["results"][$index]["poster_path"] != null){
            $pictureUrl = $this->basePicturePath . $this->movieResult["results"][$index]["poster_path"];
        }else{
            $pictureUrl = "https://www.nocowboys.co.nz/images/v3/no-image-available.png";
        }
        return $pictureUrl;
    }

    public function getMovieBackdropUrlFromApi($index)
    {   
        if(isset ($this->movieResult["results"][$index]) && $this->movieResult["results"][$index]["backdrop_path"] != null){
            $backdropUrl = $this->basePicturePath . $this->movieResult["results"][$index]["backdrop_path"];
        }else{
            $backdropUrl = "https://www.nocowboys.co.nz/images/v3/no-image-available.png";
        }
        return $backdropUrl;
    }

    public function getMoviePopularityFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $popularity = $this->movieResult["results"][$index]["popularity"];
        }else{
            $popularity = NULL ;
        }
        return $popularity;
    }

    public function getMovieVoteCountFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $voteCount = $this->movieResult["results"][$index]["vote_count"];
        }else{
            $voteCount = NULL ;
        }
        return $voteCount;
    }

    public function getMovieVideoFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            if($this->movieResult["results"][$index]["video"] == "true"){
                $video = TRUE;
            }else{
                $video = FALSE;
            }        
        }else{
            $video = NULL ;
        }
        return $video;
    }

    public function getMovieAdultFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            if($this->movieResult["results"][$index]["adult"] == "true"){
                $adult = TRUE;
            }else{
                $adult = FALSE;
            }        
        }else{
            $adult = NULL ;
        }
        return $adult;
    }

    public function getMovieOriginalLanguageFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $originalLanguage = $this->movieResult["results"][$index]["original_language"];
        }else{
            $originalLanguage = NULL ;
        }
        return $originalLanguage;
    }

    public function getMovieOriginalTitleFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $originalTitle = $this->movieResult["results"][$index]["original_title"];
        }else{
            $originalTitle = NULL ;
        }
        return $originalTitle;
    }

    public function getMovieVoteAverageFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $voteAverage = floatval($this->movieResult["results"][$index]["vote_average"]);
        }else{
            $voteAverage = NULL ;
        }
        return $voteAverage;
    }

    public function getMovieOverviewFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $overview = $this->movieResult["results"][$index]["overview"];
        }else{
            $overview = NULL ;
        }
        return $overview;
    }

    public function getMovieReleaseDateFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index]["release_date"])){
            if($this->movieResult["results"][$index]["release_date"] != ""){
                $releaseDate = $this->movieResult["results"][$index]["release_date"];
            }else{
                $releaseDate = NULL;
            }
        }else{
            $releaseDate = NULL;
        }
        return $releaseDate;
    }

    public function getMovieApiMovieIdFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $apiMovieId = $this->movieResult["results"][$index]["id"];
        }else{
            $apiMovieId = NULL;
        }
        return $apiMovieId;
    }

    public function getMovieApiGenreFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $genreId = $this->movieResult["results"][$index]["genre_ids"];
        }else{
            $genreId = NULL;
        }
        return $genreId;
    }

    public function getMovieTitleFromApi($index)
    {
        if(isset ($this->movieResult["results"][$index])){
            $title = $this->movieResult["results"][$index]["title"];
        }else{
            $title = NULL;
        }
        return $title;
    }

    public function setMovieDataApi(Movie $movie)
    {
        $this->movieDataFromApi($movie->getTitle());
        $index = 0;
        foreach($this->movieResult["results"] as $index=>$result){
            if($this->movieRepository->isFound($this->getMovieApiMovieIdFromApi($index)) == NULL){
                if ($index >= 1) {
                    $movie = new Movie();
                }

                if ($this->getMovieTitleFromApi($index) != null) {
                    $movie->setTitle($this->getMovieTitleFromApi($index));
                }
                $movie->setSlug($this->slugger->slugify($movie->getTitle()));

                $movie->setPicture($this->getMoviePictureUrlFromApi($index));
                $movie->setBackdropPath($this->getMovieBackdropUrlFromApi($index));
                $movie->setPopularity($this->getMoviePopularityFromApi($index));
                $movie->setVoteCount($this->getMovieVoteCountFromApi($index));
                $movie->setVideo($this->getMovieVideoFromApi($index));
                $movie->setAdult($this->getMovieAdultFromApi($index));
                $movie->setOriginalLanguage($this->getMovieOriginalLanguageFromApi($index));
                $movie->setOriginalTitle($this->getMovieOriginalTitleFromApi($index));
                $movie->setVoteAverage($this->getMovieVoteAverageFromApi($index));
                $movie->setOverview($this->getMovieOverviewFromApi($index));
                $movie->setApiMovieId($this->getMovieApiMovieIdFromApi($index));

                $genresListId = $this->getMovieApiGenreFromApi($index);
                if($genresListId != NULL){
                    foreach ($genresListId as $genreId) {
                        $genre = $this->genreRepository->findByApiId($genreId);
                        $movie->addGenre($genre);
                    }
                }

                if ($this->getMovieReleaseDateFromApi($index) != null) {
                    $movie->setReleaseDate(\DateTime::createFromFormat('Y-m-d', $this->getMovieReleaseDateFromApi($index)));
                }
                
                $movie->setTrailerUrl($this->apiDataTrailerManager->setYoutubeDataApi($movie->getApiMovieId()));

                $this->em->persist($movie);
                $this->em->flush();

                // TODO : CHANGE MSG OR STRUCTURE TOO MUCH MSG  (prefer Nbr msg added + title of movie index 0  + redirect to this movie)
                // $this->flashBag->add('success', 'Le Film '.$this->getMovieTitleFromApi($index).' a été ajouté a la base de donnée');
                
                /* Casting From Api DB with ApiDataMovieManager */
                
                $this->apiDataCastingManager->setCastingDataApi($movie);
                return $movie;    
            }
        }
        $this->flashBag->add('warning', 'Le Film est déja dans la base de donnée');
    }
}