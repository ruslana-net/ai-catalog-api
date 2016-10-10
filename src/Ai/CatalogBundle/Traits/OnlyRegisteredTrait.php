<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Trait
 * @package  Ai\CatalogBundle\Traits
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\Traits;

/**
 * Trait OnlyRegisteredTrait
 *
 * @category Trait
 * @package Ai\CatalogBundle\Traits
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */
trait OnlyRegisteredTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="only_registered", type="boolean")
     */
    private $onlyRegistered=false;

    /**
     * Set onlyRegistered
     *
     * @param boolean $onlyRegistered
     * @return $this
     */
    public function setOnlyRegistered($onlyRegistered)
    {
        $this->onlyRegistered = $onlyRegistered;

        return $this;
    }

    /**
     * Get onlyRegistered
     *
     * @return boolean
     */
    public function getOnlyRegistered()
    {
        return $this->onlyRegistered;
    }
}