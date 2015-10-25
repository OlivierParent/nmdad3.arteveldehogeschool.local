<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category.
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    // Relationships

    /**
     * @var Category
     *
     * Many-to-One relationship with self.
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#one-to-many-self-referencing
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * One-to-Many relationship with self.
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#one-to-many-self-referencing
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var ArrayCollection
     *
     * Many-to-many relationship to PostAbstract mapped by PostAbstract::$categories
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#many-to-many-bidirectional
     *
     * @ORM\ManyToMany(targetEntity="PostAbstract", mappedBy="categories")
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setChildren(new ArrayCollection());
        $this->setPosts(new ArrayCollection());
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
     * Set name.
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Category
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
     * Set parent.
     *
     * @param Category $parent
     *
     * @return Category
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set children.
     *
     * @param ArrayCollection $children
     *
     * @return Category
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children.
     *
     * @return Category
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set posts.
     *
     * @param ArrayCollection $posts
     *
     * @return User
     */
    public function setPosts(ArrayCollection $posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get posts.
     *
     * @return PostAbstract
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
