<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * *@Groups("movie_single")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * On peut appliquer des contraintes de validations sur une entité. Ça aura un effet sur les formulaires mais pas que,
     * @Assert\NotBlank
     * @Assert\Length(min = 3)
     * @Groups("movie_single")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="movies", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Casting", mappedBy="movie", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"creditOrder" = "ASC"})
     */
    private $castings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="movie", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $popularity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vote_count;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $adult;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backdrop_path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $original_language;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $original_title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vote_average;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $release_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apiMovieId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trailerUrl;

    public function __construct()
    {
        $this->castings = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->genres = new ArrayCollection();
        $this->person = new ArrayCollection();
        $this->job = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    /**
     * @return Collection|Casting[]
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->contains($casting)) {
            $this->castings->removeElement($casting);
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setMovie($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getMovie() === $this) {
                $team->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;
        
        return $this;
    }

    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    public function setPopularity(?float $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getVoteCount(): ?string
    {
        return $this->vote_count;
    }

    public function setVoteCount(?string $vote_count): self
    {
        $this->vote_count = $vote_count;

        return $this;
    }

    public function getAdult(): ?bool
    {
        return $this->adult;
    }

    public function setAdult(?bool $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getBackdropPath(): ?string
    {
        return $this->backdrop_path;
    }

    public function setBackdropPath(?string $backdrop_path): self
    {
        $this->backdrop_path = $backdrop_path;

        return $this;
    }

    public function getOriginalLanguage(): ?string
    {
        return $this->original_language;
    }

    public function setOriginalLanguage(?string $original_language): self
    {
        $this->original_language = $original_language;

        return $this;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->original_title;
    }

    public function setOriginalTitle(?string $original_title): self
    {
        $this->original_title = $original_title;

        return $this;
    }

    public function getVoteAverage(): ?float
    {
        return $this->vote_average;
    }

    public function setVoteAverage(?float $vote_average): self
    {
        $this->vote_average = $vote_average;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getVideo(): ?bool
    {
        return $this->video;
    }

    public function setVideo(?bool $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getApiMovieId(): ?int
    {
        return $this->apiMovieId;
    }

    public function setApiMovieId(?int $apiMovieId): self
    {
        $this->apiMovieId = $apiMovieId;

        return $this;
    }

    /**
     * @Groups("movie_single")
     * @SerializedName("createdAt")
     */
    public function getApiCreatedAt()
    {
        return $this->createdAt->format('Y-m-d h:i:s');
    }

    /**
     * @Groups("movie_single")
     * @SerializedName("updatedAt")
     */
    public function getApiUpdatedAt()
    {
        if ($this->updatedAt != null) {
            return $this->updatedAt->format('Y-m-d h:i:s');
        }
        return null;
    }

    public function getTrailerUrl(): ?string
    {
        return $this->trailerUrl;
    }

    public function setTrailerUrl(?string $trailerUrl): self
    {
        $this->trailerUrl = $trailerUrl;

        return $this;
    }
}
