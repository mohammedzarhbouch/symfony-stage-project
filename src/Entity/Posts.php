<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'posts')] //   USER
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', cascade: ['remove'])]

    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\Column]
    private ?int $total_rating_score = null;

    #[ORM\Column]
    private ?int $amount_of_ratings = null;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $likes;

    #[ORM\Column]
    private ?int $total_likes = 0;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }


    public function averageRating(): float
    {
        if ($this->amount_of_ratings === 0) {
            return 0;
        }
        $average =  $this->total_rating_score / $this->amount_of_ratings;

        return round($average *2) /2;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'user' => $this->user->getUsername(),
            'date' => date_format($this->date, 'Y-m-d'),
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'total_rating_score' => $this->total_rating_score,
            'average_rating' => $this->averageRating(),
            'total_likes' => $this->total_likes,

        ];
    }











    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getUser(): ?User // user IS USER_ID
    {
        return $this->user; // user IS USER_ID
    }

    public function setUser(?User $user): static // user IS USER_ID
    {
        $this->user = $user;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setPost($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPost() === $this) {
                $rating->setPost(null);
            }
        }

        return $this;
    }

    public function getTotalRatingScore(): ?int
    {
        return $this->total_rating_score;
    }

    public function setTotalRatingScore(int $total_rating_score): static
    {
        $this->total_rating_score = $total_rating_score;

        return $this;
    }

    public function getAmountOfRatings(): ?int
    {
        return $this->amount_of_ratings;
    }

    public function setAmountOfRatings(int $amount_of_ratings): static
    {
        $this->amount_of_ratings = $amount_of_ratings;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    public function getTotalLikes(): ?int
    {
        return $this->total_likes;
    }

    public function setTotalLikes(int $total_likes): static
    {
        $this->total_likes = $total_likes;

        return $this;
    }





}
