<?php

/**
 * Vote entity.
 */

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vote.
 */
#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ORM\Table(name: 'votes', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'uniq_user_answer', columns: ['user_id', 'answer_id']),
])]
class Vote
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Answer to which this vote applies.
     */
    #[ORM\ManyToOne(targetEntity: Answer::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Answer $answer = null;

    /**
     * User who cast the vote.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?User $user = null;

    /**
     * Vote value (+1 or -1).
     */
    #[ORM\Column(type: 'integer')]
    #[Assert\Choice(choices: [-1, 1])]
    private int $value;

    /**
     * Get the ID.
     *
     * @return int|null Vote ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the associated answer.
     */
    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    /**
     * Set the associated answer.
     *
     * @return $this
     */
    public function setAnswer(?Answer $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the user who cast the vote.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user who cast the vote.
     *
     * @return $this
     */
    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get vote value.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Set vote value.
     *
     * @param int $value Must be +1 or -1
     *
     * @return $this
     *
     * @throws \InvalidArgumentException When value is not allowed
     */
    public function setValue(int $value): static
    {
        if (!in_array($value, [-1, 1], true)) {
            throw new \InvalidArgumentException('Vote value must be +1 or -1.');
        }
        $this->value = $value;

        return $this;
    }
}
