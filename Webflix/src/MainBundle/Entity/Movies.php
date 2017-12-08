<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Movies
 *
 * @ORM\Table(name="movies")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\MoviesRepository")
 */
class Movies
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=500)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;





    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=50)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=50)
     */
    private $thumbnail;

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }



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
     * Set title
     *
     * @param string $title
     *
     * @return Movies
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Movies
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Movies
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Movies
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }



    /**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="movies")
     */

    private $comments;

    public function __construct() {
        $this->features = new ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param \MainBundle\Entity\Comments $comment
     *
     * @return Movies
     */
    public function addComment(\MainBundle\Entity\Comments $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \MainBundle\Entity\Comments $comment
     */
    public function removeComment(\MainBundle\Entity\Comments $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="movies")
     */

    private $users;



    /**
     * Add user
     *
     * @param \MainBundle\Entity\User $user
     *
     * @return Movies
     */
    public function addUser(\MainBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \MainBundle\Entity\User $user
     */
    public function removeUser(\MainBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @ORM\OneToMany(targetEntity="Ratings", mappedBy="product")
     */

    private $ratings;



    /**
     * Add rating
     *
     * @param \MainBundle\Entity\Ratings $rating
     *
     * @return Movies
     */
    public function addRating(\MainBundle\Entity\Ratings $rating)
    {
        $this->ratings[] = $rating;

        return $this;
    }

    /**
     * Remove rating
     *
     * @param \MainBundle\Entity\Ratings $rating
     */
    public function removeRating(\MainBundle\Entity\Ratings $rating)
    {
        $this->ratings->removeElement($rating);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRatings()
    {
        return $this->ratings;
    }
}
