<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * On doit implémenter l'interface UserInterface si on veut créer des users !
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields = {"email"},
 *  message = "L'email existe déjà"
 * )
 */
class User implements UserInterface
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string|null
     * @ORM\Column
     * @Assert\Email(message = "Respecter le schema email")
     */
    private ?string $email = null;

    /**
     * @ORM\Column
     * @Assert\Length(min = 8, minMessage = "Le mot de passe doit contenir 8 caractères minimum !")
     */
    private ?string $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe est différent !")
     *
     * @var [type]
     */
    public $confirm_password;

    /**
     * @var string|null
     * @ORM\Column
     */
    private ?string $pseudo = null;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $registeredAt;


    /**
     * user constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->registeredAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of email
     *
     * @return  string|null
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string|null  $email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

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

    /**
     * Get the value of pseudo
     *
     * @return  string|null
     */ 
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param  string|null  $pseudo
     *
     * @return  self
     */ 
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {;}

    public function getUsername()
    {
        return $this->email;
    }
    
    public function eraseCredentials()
    {
       ; 
    }

}
