<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopRepository")
 */
class Shop
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column (type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column (type="string")
     */
    private ?string $title = null;

    /**
     * @ORM\Column (type="string")
     */
    private ?string $address = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Book", mappedBy="shop", cascade={"persist"})
     */
    private Collection $books;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="shop", cascade={"persist"})
     */
    private Collection $products;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->clearProducts();
        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setShop($this);
        }

        return $this;
    }

    public function clearProducts(): self
    {
        /** @var Product $product */
        foreach ($this->products as $product) {
            $this->removeProduct($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->setShop(null);
        }

        return $this;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setShop($this);
        }

        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function setBooks(Collection $books): self
    {
        $this->clearBooks();
        foreach ($books as $book) {
            $this->addBook($book);
        }

        return $this;
    }

    public function clearBooks(): self
    {
        /** @var Book $book */
        foreach ($this->books as $book) {
            $this->removeProduct($book);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            $book->setShop(null);
        }

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
