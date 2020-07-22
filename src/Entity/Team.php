<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="teams")
     * @ORM\JoinColumn(nullable=true)
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="teams")
     * @ORM\JoinColumn(nullable=true)
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="teams")
     * @ORM\JoinColumn(nullable=true)
     */
    private $job;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->movie . $this->person;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

}
