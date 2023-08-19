<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'likeComment')]
    private Collection $commentLike;

    public function __construct()
    {
        $this->usersLike = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    //fonction personalisé pour modif affichage de la date de publication 
    public function getCreationTime(): string
    {
        $now = new DateTime(); // Obtenir la date et l'heure actuelles
        $diff = $this->creationDate->diff($now); // Calculer la différence entre la date de création et la date actuelle

        if ($diff->d > 0) {
            return $diff->format('%ad ago'); // Si la différence est supérieure à 0 jours, renvoyer le format "X jours auparavant" (ex: il y a 3 jours)
        }

        if ($diff->h > 0) {
            return $diff->format('%hh %im ago'); //  Si la différence est supérieure à 0 heures, renvoyer le format "Xh Xm auparavant" (ex: il y a 2h 30m)
        }

        if ($diff->i > 0) {
            return $diff->format('%im ago'); // Si la différence est supérieure à 0 minutes, renvoyer le format "Xm auparavant" (ex: il y a 45m)
        }

        return 'Just now'; //  Si aucune des conditions ci-dessus n'est remplie, renvoyer "À l'instant" car le post est très récent
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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

   /**
     * @return Collection<int, User>
     */
    public function getCommentLike(): Collection
    {
        return $this->commentLike;
    }

    public function addCommentLike(User $commentLike): self
    {
        if (!$this->commentLike->contains($commentLike)) {
            $this->commentLike->add($commentLike);
        }

        return $this;
    }

    public function removeCommentLike(User $commentLike): self
    {
        $this->commentLike->removeElement($commentLike);
        
        return $this;
    }

    public function isLikedByUser(User $user): bool
    {

        return $this->commentLike->contains($user);

    }
    
    public function howManyLikes(): int 
    {
        return count ($this->commentLike); 
    }

    public function __toString(){
        return $this->text;
    
    }
}
