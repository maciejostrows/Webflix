<?php
// src/MainBundle/Entity/User.php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->setBan(0);
        parent::__construct();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        // your own logic
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="ban", type="integer")
     */
    private $ban;

    /**
     * @return mixed
     */
    public function getBan()
    {
        return $this->ban;
    }

    /**
     * @param mixed $ban
     */
    public function setBan($ban)
    {
        $this->ban = $ban;
    }

    /**
     * @ORM\ManyToMany(targetEntity="Movies", inversedBy="users")
     * @ORM\JoinTable(name="users_movies")
     */

    private $movies;



    /**
     * Add movie
     *
     * @param \MainBundle\Entity\Movies $movie
     *
     * @return User
     */
    public function addMovie(\MainBundle\Entity\Movies $movie)
    {
        $this->movies[] = $movie;

        return $this;
    }

    /**
     * Remove movie
     *
     * @param \MainBundle\Entity\Movies $movie
     */
    public function removeMovie(\MainBundle\Entity\Movies $movie)
    {
        $this->movies->removeElement($movie);
    }

    /**
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }
}
