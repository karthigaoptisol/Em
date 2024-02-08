<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $id;

    #[ORM\Column(name: 'delivery_address', type: 'string', length: 100, nullable: false)]
    private ?string $deliveryAddress;

    #[ORM\Column(name: 'delivery_option', type: 'string', length: 100, nullable: false)]
    private ?string $deliveryOption;

    #[ORM\Column(name: 'estimated_delivery_date', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $estimatedDeliveryDate = null;
   

    #[ORM\Column(name: 'status', type: 'string', length: 100, nullable: false)]
    private ?string $status;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;
 
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItems::class)]
    private Collection $orderitems;

    public function __construct()
    {
        $this->id = Uuid::v4()->__toString();
        $this->orderitems = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getDeliveryOption(): ?string
    {
        return $this->deliveryOption;
    }

    public function setDeliveryOption(string $deliveryOption): static
    {
        $this->deliveryOption = $deliveryOption;

        return $this;
    }

    public function getEstimatedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->estimatedDeliveryDate;
    }

    public function setEstimatedDeliveryDate(?\DateTimeInterface $estimatedDeliveryDate): static
    {
        $this->estimatedDeliveryDate = $estimatedDeliveryDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, OrderItems>
     */
    public function getOrderitems(): Collection
    {
        return $this->orderitems;
    }

    public function addOrderitem(OrderItems $orderitem): static
    {
        if (!$this->orderitems->contains($orderitem)) {
            $this->orderitems->add($orderitem);
            $orderitem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderitem(OrderItems $orderitem): static
    {
        if ($this->orderitems->removeElement($orderitem)) {
            // set the owning side to null (unless already changed)
            if ($orderitem->getOrder() === $this) {
                $orderitem->setOrder(null);
            }
        }

        return $this;
    }

    
}
