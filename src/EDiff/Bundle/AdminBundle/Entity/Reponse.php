<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\Reponse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\ReponseRepository")
 */
class Reponse
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string $bonne_ou_mauvaise
     *
     * @ORM\Column(name="bonne_ou_mauvaise", type="string", length=255)
     */
    private $bonne_ou_mauvaise;

	/**
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Question")
     */
    private $question;
    
    
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
     * Set libelle
     *
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set bonne_ou_mauvaise
     *
     * @param string $bonneOuMauvaise
     */
    public function setBonneOuMauvaise($bonneOuMauvaise)
    {
        $this->bonne_ou_mauvaise = $bonneOuMauvaise;
    }

    /**
     * Get bonne_ou_mauvaise
     *
     * @return string 
     */
    public function getBonneOuMauvaise()
    {
        return $this->bonne_ou_mauvaise;
    }
    
    // On définit le getter et le setter associé à la relation Question.
    public function getQuestion()
    {
        return $this->question;
    }

    // Ici, on force le type de l'argument à être une instance de notre entité Question.
    public function setQuestion(\EDiff\Bundle\AdminBundle\Entity\Question $question)
    {
        $this->question = $question;
    }
}