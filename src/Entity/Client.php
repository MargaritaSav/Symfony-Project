<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("schedule")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("schedule")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups("schedule")
     */
    private $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("schedule")
     */
    private $problem_description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="client")
     */
    private $appointment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_contactForm;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ppa;

    public function __construct()
    {
        $this->appointment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getProblemDescription(): ?string
    {
        return $this->problem_description;
    }

    public function setProblemDescription(?string $problem_description): self
    {
        $this->problem_description = $problem_description;

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getAppointment(): Collection
    {
        return $this->appointment;
    }

    public function addAppointment(Schedule $appointment): self
    {
        if (!$this->appointment->contains($appointment)) {
            $this->appointment[] = $appointment;
            $appointment->setClient($this);
        }

        return $this;
    }

    public function removeAppointment(Schedule $appointment): self
    {
        if ($this->appointment->contains($appointment)) {
            $this->appointment->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getClient() === $this) {
                $appointment->setClient(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function getIsContactForm(): ?bool
    {
        return $this->is_contactForm;
    }

    public function setIsContactForm(?bool $is_contactForm): self
    {
        $this->is_contactForm = $is_contactForm;

        return $this;
    }

    public function getPpa(): ?bool
    {
        return $this->ppa;
    }

    public function setPpa(?bool $ppa): self
    {
        $this->ppa = $ppa;

        return $this;
    }
}
