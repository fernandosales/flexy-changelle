<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product entity class.
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 *
 *
*/
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $stock;

    /**
     * @var Collection|Image[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Image",
     *     mappedBy="product",
     *     cascade={"ALL"},
     *     orphanRemoval=true,
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $images;

    /**
     * @var Collection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="produtcs")
     * @ORM\JoinTable(name="users_groups")
     */
    private $tags;

    /**
     *
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {

        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    ### TAG RELATIONS

    /**
     * Remove tag
     *
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * Add barcode
     *
     * @param Tag $tag
     *
     * @return self
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->barcodes[] = $tag;
        }
        return $this;
    }

    /**
     * Get tags
     *
     * @return Collection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    ### END TAG RELATIONS

    ### IMAGE RELATIONS

    /**
     * @return Collection|Image[]
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image)
    {
        $image->setProduct($this);
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }
        return $this;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function removeImage(Image $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }
        return $this;
    }

    ### END IMAGE RELATIONS
}