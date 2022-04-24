<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $pageQuantity = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop", inversedBy="books", cascade={"persist"})
     */
    private ?Shop $shop = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="books", cascade={"persist"})
     */
    private Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function setAuthors(Collection $authors): self
    {
        $this->clearAuthors();
        foreach ($authors as $author) {
            $this->addAuthor($author);
        }

        return $this;
    }

    public function clearAuthors(): self
    {
        /** @var Author $author */
        foreach ($this->authors as $author) {
            $this->removeAuthor($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
            $author->removeBook($this);
        }

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPageQuantity(): ?int
    {
        return $this->pageQuantity;
    }

    public function setPageQuantity(?int $pageQuantity): self
    {
        $this->pageQuantity = $pageQuantity;

        return $this;
    }
}
