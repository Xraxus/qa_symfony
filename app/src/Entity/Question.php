<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity representing a question in the Q&A application.
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]
class Question
{
    /**
     * Unique identifier of the question (primary key).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title of the question.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Content of the question (text field).
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * Date and time when the question was created.
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Date and time when the question was last updated.
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    /**
     * Gets the unique identifier of the question.
     *
     * @return int|null The question ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the title of the question.
     *
     * @return string|null The question title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the question.
     *
     * @param string $title The question title
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the content of the question.
     *
     * @return string|null The question content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the content of the question.
     *
     * @param string $content The question content
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the creation date of the question.
     *
     * @return \DateTimeImmutable|null The creation date
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation date of the question.
     *
     * @param \DateTimeImmutable $createdAt The creation date
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the last update date of the question.
     *
     * @return \DateTimeImmutable|null The last update date
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Sets the last update date of the question.
     *
     * @param \DateTimeImmutable $updatedAt The last update date
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
