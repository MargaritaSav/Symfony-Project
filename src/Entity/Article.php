<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @Vich\Uploadable
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $review;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "Говоруша"})
     */
    private $author = "Говоруша";

    /**
     * @ORM\Column(type="date")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $featured_image;

    /**
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="featured_image")
     * @var File
    */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attachements", mappedBy="article", cascade={"persist"})
     */
    private $attachements;



    public function __construct(){
        $this->creation_date = new \DateTime();
        $this->categories = new ArrayCollection();
        $this->attachements = new ArrayCollection();
        
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

    public function getSlugPath(): string 
    {
       return (new Slugify())->slugify($this->title);
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFormatedDate() : string
    {
        $monthsList = array(".1." => "января", ".2." => "февраля", ".3." => "марта", ".4." => "апреля", ".5." => "мая", ".6." => "июня", ".7." => "июля", ".8." => "августа", ".9." => "сентября", ".10." => "октября", ".11." => "ноября", ".12." => "декабря");
        $date = $this->creation_date->format("j.n.Y");
        
        $month = $this->creation_date->format(".n.");
        
           
       return str_replace($month, " ".$monthsList[$month]." ", $date);


    }


     public function __toString()
    {
        return $this->title;
    }

     public function getFeaturedImage()
     {
         return $this->featured_image;
     }

     public function setFeaturedImage($featured_image)
     {
         $this->featured_image = $featured_image;

         return $this;
     }

     public function setImageFile(File $image = null)
     {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
     }

     public function getImageFile()
     {
         return $this->imageFile;
     }

     public function getUpdatedAt(): ?\DateTimeInterface
     {
         return $this->updatedAt;
     }

     public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
     {
         $this->updatedAt = $updatedAt;

         return $this;
     }

     /**
      * @return Collection|Category[]
      */
     public function getCategories(): Collection
     {
         return $this->categories;
     }

     public function addCategory(Category $category): self
     {
         if (!$this->categories->contains($category)) {
             $this->categories[] = $category;
         }

         return $this;
     }

     public function removeCategory(Category $category): self
     {
         if ($this->categories->contains($category)) {
             $this->categories->removeElement($category);
         }

         return $this;
     }

     /**
      * @return Collection|Attachements[]
      */
     public function getAttachements(): Collection
     {
         return $this->attachements;
     }

     public function addAttachement(Attachements $attachement): self
     {
         if (!$this->attachements->contains($attachement)) {
             $this->attachements[] = $attachement;
             $attachement->setArticle($this);
         }

         return $this;
     }

     public function removeAttachement(Attachements $attachement): self
     {
         if ($this->attachements->contains($attachement)) {
             $this->attachements->removeElement($attachement);
             // set the owning side to null (unless already changed)
             if ($attachement->getArticle() === $this) {
                 $attachement->setArticle(null);
             }
         }

         return $this;
     }


}
