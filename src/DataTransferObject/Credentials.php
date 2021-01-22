<?php

namespace App\DataTransferObject;

use symfony\component\Validator\Constraints as Assert;

class Credentials 
{
    /**
     * Assert\NotBlank
     */
    private ?string $username = null;

    /**
     * Assert\NotBlank
     */
    private ?string $password = null;

    /**
     * Undocumented function
     *
     * @param string|null $username
     */
    public function __construct(?string $username = null)
    {
        $this->username = $username;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}