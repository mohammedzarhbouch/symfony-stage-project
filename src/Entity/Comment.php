<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $commentAuthor = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentText = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Posts $post = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column]
    private ?int $voteScore = 0;

    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'comment', orphanRemoval: true)]
    private Collection $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentAuthor(): ?string
    {
        return $this->commentAuthor;
    }

    public function setCommentAuthor(string $commentAuthor): static
    {
        $this->commentAuthor = $commentAuthor;

        return $this;
    }

    public function getCommentText(): ?string
    {
        return $this->commentText;
    }

    public function setCommentText(string $commentText): static
    {
        $this->commentText = $commentText;

        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getVoteScore(): ?int
    {
        return $this->voteScore;
    }

    public function setVotes(int $voteScore): static
    {
        $this->voteScore = $voteScore;

        return $this;
    }

//    public function upvote(): void
//    {
//        $this->voteScore++;
//
//    }
//
//    public function downvote(): void
//    {
//        $this->voteScore--;
//    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVotes(Vote $votes): static
    {
        if (!$this->votes->contains($votes)) {
            $this->votes->add($votes);
            $votes->setComment($this);
        }

        return $this;
    }

    public function removeVotes(Vote $votes): static
    {
        if ($this->votes->removeElement($votes)) {
            // set the owning side to null (unless already changed)
            if ($votes->getComment() === $this) {
                $votes->setComment(null);
            }
        }

        return $this;
    }


}
