<?php

namespace User\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\NoObjectExists;
use User\Entity\User;
use Zend\Form\Form;
use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Validator\EmailAddress;
use Zend\Validator\Regex;

/**
 * Class Registration
 * @package User\Form
 */
class Registration extends Form implements InputFilterProviderInterface
{
    /**
     * @var CaptchaAdapter
     */
    protected $captcha;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    private $filters = [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
        ['name' => 'HtmlEntities']
    ];

    /**
     * @param CaptchaAdapter $captcha
     */
    public function setCaptcha(CaptchaAdapter $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * @param int|null|string $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct('register');
        $this->setAttribute('method', 'post');

        $captchaImage = new CaptchaImage([
            'font' => APPLICATION_PATH . '/fonts/Loma.ttf',
            'width' => 150,
            'height' => 50,
            'wordLen' => 5,
            'dotNoiseLevel' => 5,
            'lineNoiseLevel' => 5,
            'fontSize' => 25
        ]);
        $captchaImage->setGcFreq(3);
        $captchaImage->setImgDir(APPLICATION_PATH . '/img/captcha/');
        $captchaImage->setImgUrl('/img/captcha/');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email'
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
            )
        ));
        $this->add([
            'name' => 'confirmPassword',
            'type' => 'Password',
            'options' => [
                'label' => 'Confirm password'
            ]
        ]);
        $this->add([
            'name' => 'firstName',
            'type' => 'Text',
            'options' => [
                'label' => 'First Name'
            ]
        ]);
        $this->add([
            'name' => 'lastName',
            'type' => 'Text',
            'options' => [
                'label' => 'Last Name'
            ]
        ]);
        $this->add([
            'name' => 'aboutMe',
            'type' => 'Textarea',
            'options' => [
                'label' => 'About me',
                'size' => 30
            ]
        ]);
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human.',
                'captcha' => $captchaImage
            ),
        ));
        $this->add(array(
            'type' => 'Csrf',
            'name' => 'csrfReg',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => null
                )
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit'
            ),
        ));
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
                        'options' => [
                            'message' => 'Enter Username.'
                        ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'message' => 'Username mast be at least 6 characters.'
                        ]
                    ],
                    [
                        'name' => '\DoctrineModule\Validator\NoObjectExists',
                        'options' => [
                            'object_repository' => $this->entityManager->getRepository(User::USER_ENTITY),
                            'fields' => 'username',
                            'message' => [
                                NoObjectExists::ERROR_NO_OBJECT_FOUND => 'Username has already been registered, try another'
                            ]
                        ],
                        'break_chain_on_failure' => true
                    ]
                ]
            ],
            'password' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'message' => 'Password wast be longer then 5 chars'
                        ],
                    ],
                    [
                        'name' => 'Regex',
                        'options' => [
                            'pattern' => '((?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z]).*$)',
                            'message' => 'At least 6 chars length, with one uppercase letter, number and special character'
                        ],
                        'break_chain_on_failure' => true
                    ],
                ]
            ],
            'confirmPassword' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password',
                            'message' => 'Must be the same as password'
                        ],
                        'break_chain_on_failure' => true
                    ]
                ]
            ],
            'email' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => 'Email is required.'
                        ],
                    ],
                    [
                        'name' => 'Regex',
                        'options' => [
                            'pattern' => '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/',
                            'messages' => [
                                Regex::NOT_MATCH => 'Please provide a valid email address.',
                            ],
                        ],
                        'break_chain_on_failure' => true
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'message' => [
                                EmailAddress::INVALID_FORMAT => 'Please provide a valid email address.',
                            ]
                        ],
                        'break_chain_on_failure' => true
                    ]
                ]
            ],
            [
                'firstName' => ['filters' => $this->filters]
            ],
            [
                'lastName' => ['filters' => $this->filters]
            ],
            [
                'aboutMe' => ['filters' => $this->filters]
            ]
        ];
    }
}