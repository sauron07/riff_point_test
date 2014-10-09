<?php
namespace User\Service\Traits;

use Doctrine\ORM\EntityManager;

/**
 * Class EntityManagerAwareTrait
 * @package User\Service\Traits
 */
trait EntityManagerAwareTrait
{
    /** @var  EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     * @return $this
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}