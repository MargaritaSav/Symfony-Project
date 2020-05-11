<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionsRepository")
 */
class Questions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isAnswered = false;

   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $askedBy_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $askedBy_email;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isAnswerSent = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialist", inversedBy="questions")
     */
    private $answered_by;


    public function __construct(){
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsAnswered(): ?bool
    {
        return $this->isAnswered;
    }

    public function setIsAnswered(bool $isAnswered): self
    {
        $this->isAnswered = $isAnswered;

        return $this;
    }


    public function getAskedByName(): ?string
    {
        return $this->askedBy_name;
    }

    public function setAskedByName(string $askedBy_name): self
    {
        $this->askedBy_name = $askedBy_name;

        return $this;
    }

    public function getAskedByEmail(): ?string
    {
        return $this->askedBy_email;
    }

    public function setAskedByEmail(string $askedBy_email): self
    {
        $this->askedBy_email = $askedBy_email;

        return $this;
    }

    public function getIsAnswerSent(): ?bool
    {
        return $this->isAnswerSent;
    }

    public function setIsAnswerSent(bool $isAnswerSent): self
    {
        $this->isAnswerSent = $isAnswerSent;

        return $this;
    }

    public function getAnsweredBy(): ?Specialist
    {
        return $this->answered_by;
    }

    public function setAnsweredBy(?Specialist $answered_by): self
    {
        $this->answered_by = $answered_by;

        return $this;
    }

    public function __toString(){
        return $this->askedBy_name;
    }
}
