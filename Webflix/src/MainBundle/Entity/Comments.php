<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\CommentsRepository")
 */
class Comments
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
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=500)
     */
    private $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="badComment", type="integer")
     */
    private $badComment;

    /**
     * @return int
     */
    public function getBadComment()
    {
        return $this->badComment;
    }

    /**
     * @param int $badComment
     */
    public function setBadComment($badComment)
    {
        $this->badComment = $badComment;
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
     * Set author
     *
     * @param string $author
     *
     * @return Comments
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Comments
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Movies", inversedBy="comments")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */

    private $movies;

    /**
     * Set movies
     *
     * @param \MainBundle\Entity\Movies $movies
     *
     * @return Comments
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

    public function __toString()
    {
        return $this->setMovies();
    }
}
