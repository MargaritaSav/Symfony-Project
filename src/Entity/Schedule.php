<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"schedule", "appointment"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"schedule", "appointment"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"schedule", "appointment"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"schedule", "appointment"})
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="appointment", cascade={"persist"})
     *@Groups("schedule")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialist", inversedBy="appointments")
     * @Groups({"schedule", "appointment"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $specialist;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"schedule", "appointment"})
     */
    private $is_booked = false;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups("schedule")
     */
    private $is_confirmed = false;

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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSpecialist(): ?Specialist
    {
        return $this->specialist;
    }

    public function setSpecialist(?Specialist $specialist): self
    {
        $this->specialist = $specialist;

        return $this;
    }
    public function __toString(){
        return $this->title;
    }

    public function getIsBooked(): ?bool
    {
        return $this->is_booked;
    }

    public function setIsBooked(bool $is_booked): self
    {
        $this->is_booked = $is_booked;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed): self
    {
        $this->is_confirmed = $is_confirmed;

        return $this;
    }

    public function getSpecialistId(){
       return $this->specialist->getId();
    }

}
