<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="User\Repository\UserRepository")
 */
class User
{
    const USER_ENTITY = 'User\Entity\User';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(name="password_salt", length=32, type="string", nullable=true)
     */
    protected $passwordSalt;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="about_me", type="text")
     */
    protected $aboutMe;

    /**
     * @return mixed
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * @param mixed $aboutMe
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = self::hashPassword($this, $password, true);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * @param bool $generateSaltIfEmpty
     * @return mixed
     */
    public function getPasswordSalt($generateSaltIfEmpty = false)
    {
        if($generateSaltIfEmpty && empty($this->getPasswordSalt())){
            $this->setPasswordSalt(md5(uniqid()));
        }
        return $this->passwordSalt;
    }

    /**
     * @param mixed $passwordSalt
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    }

    /**
     * @param $user
     * @param $password
     * @param $generateSaltIfEmpty
     * @return string
     */
    public function hashPassword(User $user, $password, $generateSaltIfEmpty = false)
    {
        $salt = $user->getPasswordSalt($generateSaltIfEmpty);
        if(!empty($salt)){
            $bcrypt = new Bcrypt(['salt' => $salt, 'cost' => 8]);
            return $bcrypt->create($password, $salt);
        }

        return md5(md5($password));
    }
}