<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EDiff\Bundle\AdminBundle\Utils;

/**
 * EDiff\Bundle\AdminBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $login
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string $droits
     *
     * @ORM\Column(name="droits", type="string", length=255)
     */
    private $droits;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var datetime $date_naissance
     *
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    private $date_naissance;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Utils::encrypt_password($password);
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return Utils::decrypt_password($this->password);
    }

    /**
     * Set droits
     *
     * @param string $droits
     */
    public function setDroits($droits)
    {
        $this->droits = $droits;
    }

    /**
     * Get droits
     *
     * @return string 
     */
    public function getDroits()
    {
        return $this->droits;
    }

    /**
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set date_naissance
     *
     * @param datetime $dateNaissance
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;
    }

    /**
     * Get date_naissance
     *
     * @return datetime 
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }
    
    public function __toString()
    {
    	return $this->prenom." ".$this->nom;
    }
    
	/**
     * Get encrypted password
     *
     * @return string 
     */
    public function getEncryptedPassword()
    {
        return $this->password;
    }
}