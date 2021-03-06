<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ratings
 *
 * @ORM\Table(name="ratings")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\RatingsRepository")
 */
class Ratings
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Ratings
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Movies", inversedBy="ratings")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */

    private $movies;

    /**
     * Set movies
     *
     * @param \MainBundle\Entity\Movies $movies
     *
     * @return Ratings
     */
    public function setMovies(\MainBundle\Entity\Movies $movies = null)
    {
        $this->movies = $movies;

        return $this;
    }

    /**
     * Get movies
     *
     * @return \MainBundle\Entity\Movies
     */
    public function getMovies()
    {
        return $this->movies;
    }
}
