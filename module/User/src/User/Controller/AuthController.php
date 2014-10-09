<?php

namespace User\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use User\Form\Login;
use User\Service\Users;

/**
 * Class AuthController
 * @package User\Controller
 */
class AuthController extends AbstractActionController
{
    /** @var  Users */
    protected  $service;

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->service = $this->getServiceLocator()->get('User\Service\Users');
        parent::onDispatch($e);
    }
    /**
     * @var  EntityManager
     */
    protected $em;

    /**
     * @return ViewModel
     */
    public function registrationAction(){

        /** @var \User\Form\Registration $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\Registration');

        if($this->request->isPost()){
            $date = $this->params()->fromPost();
            $form->setData($date);
            if ($form->isValid()){
                $this->service->registerUser($form->getData());
                $this->redirect()->toRoute('login');
            }
        }
        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        /** @var Login $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\Login');

        $data = $this->params()->fromPost();
        $form->setData($data);

        $result = [];
        $form->getInputFilterSpecification();
        if($this->params()->fromPost()){
            if($form->isValid()){
                $result = $this->service->login($form->getData());
                if($result['success'] == true){
                    return $this->redirect()->toRoute('home');
                }
            }
        }
        return new ViewModel([
                'form' => $form,
                'message' => $result['message'],
        ]);
    }

    public function logoutAction()
    {
        $this->service->logout();
        $this->redirect()->toRoute('home');
    }
}