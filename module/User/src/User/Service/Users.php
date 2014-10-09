<?php

namespace User\Service;

use User\Repository\UserRepository;
use User\Service\Traits\EntityManagerAwareTrait;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use User\Entity\User;
use Zend\Authentication\Result ;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;

/**
 * Class Users
 * @package User\Service
 */
class Users implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /** @var AuthenticationService  */
    public $authService;

    /** @var UserRepository */
    protected $repository;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @param AuthenticationService $authService
     * @param SessionManager $sessionManager
     */
    public function __construct(AuthenticationService $authService, SessionManager $sessionManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    public function init()
    {
        $this->repository = $this->getEntityManager()->getRepository('User\Entity\User');
    }

    /**
     * @param $date
     * @return array
     */
    public function registerUser($date)
    {
        $result = $this->getUserRepository()->registerUser($date);
        return $result;
    }

    /**
     * @param $params
     * @return array
     */
    public function login($params)
    {
        $username = $params['username'];
        $pass = $params['password'];

        /** @var ObjectRepository $adapter */
        $adapter = $this->authService->getAdapter();
        $adapter->setIdentityValue($username);
        $adapter->setCredentialValue($pass);

        /** @var AuthenticationService $result */
        $result = $adapter->authenticate();

        if(!$result->isValid()){
            switch ($result->getCode()){
                case (Result::FAILURE_CREDENTIAL_INVALID):
                    return ['message' => 'Неверный пароль', 'success' => false];
                    break;
                case (Result::FAILURE_IDENTITY_NOT_FOUND):
                    return ['message' => 'Пользователь не найден', 'success' => false];
                    break;
                default:
                    return ['message' => 'Ошибка авторизации', 'success' => false];
            }
        }

        $identity = $result->getIdentity();
        $this->authService->getStorage()->write($identity);

        if ($params['rememberMe'] != '') {
            $time = 1209600;
            $this->sessionManager->rememberMe($time);
        }

        return ['message' => 'Success login', 'success' => true];
    }

    public function logout()
    {
        $this->authService->clearIdentity();
        $this->sessionManager->forgetMe();
    }

    /**
     * @return UserRepository
     */
    private function getUserRepository()
    {
        return $this->getEntityManager()->getRepository(User::USER_ENTITY);
    }
}