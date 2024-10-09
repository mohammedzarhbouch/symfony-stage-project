<?php

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'followedBy')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $followedUser = null; // Change to a single User instance

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'following')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $followerUser = null; // Change to a single User instance

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollowedUser(): ?User
    {
        return $this->followedUser;
    }

    public function setFollowedUser(User $followedUser): static
    {
        $this->followedUser = $followedUser;

        return $this;
    }

    public function getFollowerUser(): ?User
    {
        return $this->followerUser;
    }

    public function setFollowerUser(User $followerUser): static
    {
        $this->followerUser = $followerUser;

        return $this;
    }
}
