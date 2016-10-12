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

use Ai\CatalogBundle\Traits\NameTrait;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client entity
 *
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity(repositoryClass="Ai\CatalogBundle\Repository\ClientRepository")
 */
class Client extends BaseClient
{
    use NameTrait;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
