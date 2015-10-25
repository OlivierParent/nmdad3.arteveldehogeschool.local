<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PostAbstract.
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string", length=1)
 * @ORM\DiscriminatorMap({
 *     PostAbstract::POST_ARTICLE:"Article",
 *     PostAbstract::POST_IMAGE  :"Image"
 * })
 * @JMS\Discriminator(field = "discriminator", map = {
 *     PostAbstract::POST_ARTICLE:"AppBundle\Entity\Article",
 *     PostAbstract::POST_IMAGE  :"AppBundle\Entity\Image"
 * })
 * @JMS\ExclusionPolicy("all")
 */
abstract class PostAbstract
{
    const POST_ARTICLE = 'A';
    const POST_IMAGE = 'I';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @JMS\Expose()
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @JMS\Exclude()
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    // Relationships

    /**
     * @var User
     *
     * @Assert\NotBlank()
     *
     * Many-to-one relationship with User inversed by User::$posts.
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#one-to-many-bidirectional
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * Many-to-many relationship with PostAbstract inversed by PostAbstract::$posts.
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#many-to-many-bidirectional
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="posts_has_categories",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     * @JMS\Expose()
     */
    private $categories;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setCategories(new ArrayCollection());
    }

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
     * Set title.
     *
     * @param string $title
     *
     * @return PostAbstract
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return PostAbstract
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return PostAbstract
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime $deletedAt
     *
     * @return PostAbstract
     */
    public function setDeletedAt(\DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set publishedAt.
     *
     * @param \DateTime $publishedAt
     *
     * @return PostAbstract
     */
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt.
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set user.
     *
     * @param $user
     *
     * @return PostAbstract
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set categories.
     *
     * @param ArrayCollection $categories
     *
     * @return PostAbstract
     */
    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add category.
     *
     * @param Category $category
     *
     * @return PostAbstract
     */
    public function addCategory(Category $category)
    {
        $this->categories->add($category);

        return $this;
    }

    /**
     * Get categories.
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
