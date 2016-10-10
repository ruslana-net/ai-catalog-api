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
 * Trait CacheTrait
 *
 * @category Trait
 * @package Ai\CatalogBundle\Traits
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */
trait CacheTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="no_cache", type="boolean")
     */
    private $noCache=false;

    /**
     * @var integer
     *
     * @ORM\Column(name="cache_timeout", type="integer")
     */
    private $cacheTimeout=0;

    /**
     * Set noCache
     *
     * @param boolean $noCache
     * @return $this
     */
    public function setNoCache($noCache)
    {
        $this->noCache = $noCache;

        return $this;
    }

    /**
     * Get noCache
     *
     * @return boolean
     */
    public function getNoCache()
    {
        return $this->noCache;
    }

    /**
     * Set cacheTimeout
     *
     * @param integer $cacheTimeout
     * @return $this
     */
    public function setCacheTimeout($cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }

    /**
     * Get cacheTimeout
     *
     * @return integer
     */
    public function getCacheTimeout()
    {
        return $this->cacheTimeout;
    }
}