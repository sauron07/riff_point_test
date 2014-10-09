<?php

namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\User;

/**
 * Class UserRepository
 * @package User\Repository
 */
class UserRepository extends EntityRepository
{

    /**
     * @param $formData
     * @return array
     */
    public function registerUser($formData)
    {
        try {
            $user = new User();

            $user->setUsername($formData['username']);
            $user->setPassword($formData['password']);
            $user->setEmail($formData['email']);
            $user->setFirstName($formData['firstName']);
            $user->setLastName($formData['lastName']);
            $user->setAboutMe($formData['aboutMe']);

            $this->_em->persist($user);
            $this->_em->flush($user);

            return ['success' => true, 'message' => 'Registration successful. Use your username and password to login in.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }
}