<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?bool $is_read;

    #[ORM\ManyToOne(inversedBy: 'sent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'received')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient = null;

    #[ORM\OneToMany(mappedBy: 'related_message', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications_message;

    public function __construct(){
        $this->created_at = new \DateTimeImmutable();
        $this->is_read = false;
        $this->notifications_message = new ArrayCollection();

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function isIsread(): ?bool
    {
        return $this->is_read;
    }

    public function setIsread(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotificationsMessage(): Collection
    {
        return $this->notifications_message;
    }

    public function addNotificationsMessage(Notification $notificationsMessage): self
    {
        if (!$this->notifications_message->contains($notificationsMessage)) {
            $this->notifications_message->add($notificationsMessage);
            $notificationsMessage->setRelatedMessage($this);
        }

        return $this;
    }

    public function removeNotificationsMessage(Notification $notificationsMessage): self
    {
        if ($this->notifications_message->removeElement($notificationsMessage)) {
            // set the owning side to null (unless already changed)
            if ($notificationsMessage->getRelatedMessage() === $this) {
                $notificationsMessage->setRelatedMessage(null);
            }
        }

        return $this;
    }
}
