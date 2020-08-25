<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Traits\Timestamps;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreRemove;
use DateTime;
/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks
 */
class Order implements \JsonSerializable
{
    use Timestamps;

    public const STATUSES = [
        1 => 'Waiting',
        2 => 'Completed',
        3 => 'Refunded'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var string Order number
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @var int Order status
     * @ORM\Column(type="integer")
     */
    private $status;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user")
     */
    private $user;

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Order
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Order
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Order
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /** @PrePersist
     */
    public function incrementUserOrdersCount(): void
    {
        $this->getUser()->incrementOrdersCount();
    }

    /** @PreRemove
     */
    public function decrementUserOrdersCount(): void
    {
        $this->getUser()->decrementOrdersCount();
    }

    public function setParams($params): self
    {
        if(isset($params['status'])) $this->setStatus($params['status']);
        if(isset($params['number'])) $this->setNumber($params['number']);
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUser()->getId(),
            'number' => $this->getNumber(),
            'status' => [
                'id' => $this->getStatus(),
                'title' => self::STATUSES[$this->getStatus()]
            ],
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
