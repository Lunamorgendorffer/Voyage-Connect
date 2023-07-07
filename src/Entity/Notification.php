<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NotificationRepository;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $notify_message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notifications_message')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Messages $related_message = null;

    public function __construct(){
        $this->created_at = new DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotifyMessage(): ?string
    {
        return $this->notify_message;
    }

    public function setNotifyMessage(string $notify_message): self
    {
        $this->notify_message = $notify_message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRelatedMessage(): ?Messages
    {
        return $this->related_message;
    }

    public function setRelatedMessage(?Messages $related_message): self
    {
        $this->related_message = $related_message;

        return $this;
    }
}
