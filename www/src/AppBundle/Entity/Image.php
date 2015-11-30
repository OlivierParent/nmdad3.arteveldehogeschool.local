<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image.
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass=ImageRepository::class)
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
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     */
    protected $uri;

    /**
     * @var UploadedFile
     *
     * http://api.symfony.com/2.7/Symfony/Component/HttpFoundation/File/UploadedFile.html
     *
     * @Assert\NotBlank(groups={"Backoffice"})
     * @Assert\File(maxSize="6000000", groups={"Backoffice"})
     * @Assert\File(mimeTypes={"image/gif", "image/png", "image/jpeg"}, groups={"Backoffice"})
     */
    protected $file;

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

    /**
     * Set file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
