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

use Ai\CatalogBundle\Traits\CeoTrait;
use Ai\CatalogBundle\Traits\CrdateTrait;
use Ai\CatalogBundle\Traits\DescrTrait;
use Ai\CatalogBundle\Traits\EnabledTrait;
use Ai\CatalogBundle\Traits\NameSlugTrait;
use Ai\CatalogBundle\Traits\NameTrait;
use Ai\CatalogBundle\Traits\PositionTrait;
use Ai\CatalogBundle\Traits\TstampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category entity
 *
 * @ORM\Table(name="catalog_categories")
 * @ORM\Entity(repositoryClass="Ai\CatalogBundle\Repository\CategoryRepository")
 */
class Category
{
    use CrdateTrait,
        TstampTrait,
        EnabledTrait,
        PositionTrait,
        NameTrait,
        NameSlugTrait,
        DescrTrait,
        CeoTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     **/
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
    public function __construct(string $name = null)
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->name = $name;
    }

    /**
     * Add product
     *
     * @param \Ai\CatalogBundle\Entity\Product $product
     *
     * @return Category
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
