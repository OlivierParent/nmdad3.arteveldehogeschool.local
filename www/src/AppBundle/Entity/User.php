<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User.
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';

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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Exclude()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enabled_at", type="datetime", nullable=true)
     * @JMS\Exclude()
     */
    private $enabledAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="locked_at", type="datetime", nullable=true)
     * @JMS\Exclude()
     */
    private $lockedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=true)
     * @JMS\Exclude()
     */
    private $expiredAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_credentials_at", type="datetime", nullable=true)
     * @JMS\Exclude()
     */
    private $expiredCredentialsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Remember me.
     *
     * @var bool
     */
    public $remember = true;

    // Members for Relationships.
    // --------------------------

    /**
     * @var ArrayCollection
     *
     * One-to-many relationship to PostAbstract mapped by PostAbstract::$user
     * Further reading: http://docs.doctrine-project.org/en/latest/reference/association-mapping.html#many-to-many-bidirectional
     *
     * @ORM\OneToMany(targetEntity="PostAbstract", mappedBy="user")
     * @JMS\Exclude()
     */
    private $posts;

    // Members for UserInterface implementation.
    // -----------------------------------------

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var null
     */
    private $salt = null;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, options={"fixed":true})
     * @JMS\Exclude()
     */
    private $password;

    // Members for UserInterface implementation.
    // -----------------------------------------


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setEnabledAt(new \DateTime());
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
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * Set enabledAt.
     *
     * @param \DateTime $enabledAt
     *
     * @return User
     */
    public function setEnabledAt(\DateTime $enabledAt)
    {
        $this->enabledAt = $enabledAt;

        return $this;
    }

    /**
     * Get enabledAt.
     *
     * @return \DateTime
     */
    public function getEnabledAt()
    {
        return $this->enabledAt;
    }

    /**
     * Set lockedAt.
     *
     * @param \DateTime $lockedAt
     *
     * @return User
     */
    public function setLockedAt(\DateTime $lockedAt)
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    /**
     * Get lockedAt.
     *
     * @return \DateTime
     */
    public function getLockedAt()
    {
        return $this->lockedAt;
    }

    /**
     * Set expiredAt.
     *
     * @param \DateTime $expiredAt
     *
     * @return User
     */
    public function setExpiredAt(\DateTime $expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt.
     *
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set expiredCredentialsAt.
     *
     * @param \DateTime $expiredCredentialsAt
     *
     * @return User
     */
    public function setExpiredCredentialsAt(\DateTime $expiredCredentialsAt)
    {
        $this->expiredCredentialsAt = $expiredCredentialsAt;

        return $this;
    }

    /**
     * Get expiredCredentialsAt.
     *
     * @return \DateTime
     */
    public function getExpiredCredentialsAt()
    {
        return $this->expiredCredentialsAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime $deletedAt
     *
     * @return User
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

    // Methods for Relationships.
    // --------------------------

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

    // Methods for UserInterface implementation.
    // -----------------------------------------
    // http://api.symfony.com/2.7/Symfony/Component/Security/Core/User/UserInterface.html

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [self::ROLE_USER];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // Do nothing.
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return; // Salt not needed for Bcrypt algorithm.
    }

    // Methods for AdvancedUserInterface implementation.
    // -------------------------------------------------
    // http://api.symfony.com/2.7/Symfony/Component/Security/Core/User/AdvancedUserInterface.html

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return true;
    }

    // Methods for Serializable implementation.
    // ----------------------------------------
    // http://php.net/manual/en/class.serializable.php

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }
}
