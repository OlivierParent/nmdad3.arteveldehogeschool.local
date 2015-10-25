<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image.
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ImageRepository")
 */
class Image extends PostAbstract
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uri.
     *
     * @param string $uri
     *
     * @return Image
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
