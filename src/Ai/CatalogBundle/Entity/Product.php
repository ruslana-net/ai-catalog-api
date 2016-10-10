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
use Ai\CatalogBundle\Traits\PriceTrait;
use Ai\CatalogBundle\Traits\TstampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product entity
 *
 * @ORM\Table(name="catalog_products")
 * @ORM\Entity(repositoryClass="Ai\CatalogBundle\Repository\ProductRepository")
 */
class Product
{
    use CrdateTrait,
        TstampTrait,
        EnabledTrait,
        PositionTrait,
        NameTrait,
        NameSlugTrait,
        DescrTrait,
        PriceTrait,
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="products")
     * @ORM\JoinTable(name="products_tags")
     */
    private $tags;

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
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Ai\CatalogBundle\Entity\User $user
     *
     * @return Product
     */
    public function setUser(\Ai\CatalogBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ai\CatalogBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set category
     *
     * @param \Ai\CatalogBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\Ai\CatalogBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Ai\CatalogBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \Ai\CatalogBundle\Entity\Tag $tag
     *
     * @return Product
     */
    public function addTag(\Ai\CatalogBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Ai\CatalogBundle\Entity\Tag $tag
     */
    public function removeTag(\Ai\CatalogBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
