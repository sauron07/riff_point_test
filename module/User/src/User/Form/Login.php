<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class Login
 * @package User\Form
 */
class Login extends Form implements InputFilterProviderInterface
{
    /**
     * @var array
     */
    private $filters = [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
        ['name' => 'HtmlEntities']
    ];

    public function __construct()
    {
        parent::__construct('login');

        $this->setAttributes([
                'method' => 'post',
            ]
        );

        $this->add([
            'name' => 'username',
            'type' => 'Text',
            'options' => [
                'label' => 'Username'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
            ]
        ]);

        $this->add([
            'name' => 'rememberMe',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => [
                'label' => 'Remember me',
                'checked_value' => true,
                'unchecked_value' => false,
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Login',
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'username' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                ],
                'break_chain_on_failure' => true,
            ],
            'password' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                ],
                'break_chain_on_failure' => true,
            ],
            'rememberMe' => [
                'required' => false,
                'break_chain_on_failure' => true,
            ],
        ];
    }
}