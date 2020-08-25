<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \JsonSerializable
{
    use Timestamps;

    public const SEX = [
        1 => 'Male',
        2 => 'Female'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string User name
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string Unique user email
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var integer Sex
     * @ORM\Column(type="integer")
     */
    private $sex;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var integer Count of user orders
     * @ORM\Column(type="integer")
     */
    private $ordersCount = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user", orphanRemoval=true, fetch="EAGER")
     * @ORM\JoinColumn(name="order", referencedColumnName="user_id")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getSex(): int
    {
        return $this->sex;
    }

    /**
     * @param int $sex
     * @return User
     */
    public function setSex(int $sex): self
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdersCount(): int
    {
        return $this->ordersCount;
    }

    /**
     * @param int $ordersCount
     * @return User
     */
    public function setOrdersCount(int $ordersCount): self
    {
        $this->ordersCount = $ordersCount;
        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getProducts(): Collection
    {
        return $this->orders;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function setParams($params): self
    {
        if(isset($params['name'])) $this->setName($params['name']);
        if(isset($params['email'])) $this->setEmail($params['email']);
        if(isset($params['sex'])) $this->setSex($params['sex']);
        if(isset($params['password'])) $this->setPassword($params['password']);

        return $this;
    }

    public function getSexTitle()
    {
        return self::SEX[$this->getSex()] ?? '';
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function incrementOrdersCount(): self {
        $this->setOrdersCount($this->getOrdersCount() + 1);
        return $this;
    }

    public function decrementOrdersCount(): self {
        $this->setOrdersCount($this->getOrdersCount() - 1);
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'sex' => [
                'id' => $this->getSex(), // or help yourself... :DD
                'title' => $this->getSexTitle()
            ],
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'orders_count' => $this->getOrdersCount()
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders): void
    {
        $this->orders = $orders;
    }
}
