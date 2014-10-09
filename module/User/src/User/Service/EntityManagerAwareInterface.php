<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;

/**
 * Interface EntityManagerAwareInterface
 * @package User\Service
 */
interface EntityManagerAwareInterface
{
    /**
     * @param EntityManager $entityManager
     * @return mixed
     */
    public function setEntityManager(EntityManager $entityManager);
}