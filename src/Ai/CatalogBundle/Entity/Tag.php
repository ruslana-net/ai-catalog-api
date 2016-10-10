<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Entity
 * @package  Ai\CatalogBundle\Entity
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\Entity;

use Ai\CatalogBundle\Traits\CrdateTrait;
use Ai\CatalogBundle\Traits\EnabledTrait;
use Ai\CatalogBundle\Traits\NameSlugTrait;
use Ai\CatalogBundle\Traits\NameTrait;
use Ai\CatalogBundle\Traits\TstampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag entity
 *
 * @ORM\Table(name="catalog_tags")
 * @ORM\Entity(repositoryClass="Ai\CatalogBundle\Repository\TagRepository")
 */
class Tag
{
    use CrdateTrait,
        TstampTrait,
        EnabledTrait,
        NameTrait,
        NameSlugTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="tags")
     */
    private $products;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \Ai\CatalogBundle\Entity\Product $product
     *
     * @return Tag
     */
    public function addProduct(\Ai\CatalogBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Ai\CatalogBundle\Entity\Product $product
     */
    public function removeProduct(\Ai\CatalogBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
