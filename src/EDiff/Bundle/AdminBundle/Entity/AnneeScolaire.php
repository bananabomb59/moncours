<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\AnneeScolaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\AnneeScolaireRepository")
 */
class AnneeScolaire
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
     * @var smallint $annee_debut
     *
     * @ORM\Column(name="annee_debut", type="smallint")
     */
    private $annee_debut;

    /**
     * @var smallint $annee_fin
     *
     * @ORM\Column(name="annee_fin", type="smallint")
     */
    private $annee_fin;


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
     * Set annee_debut
     *
     * @param smallint $anneeDebut
     */
    public function setAnneeDebut($anneeDebut)
    {
        $this->annee_debut = $anneeDebut;
    }

    /**
     * Get annee_debut
     *
     * @return smallint 
     */
    public function getAnneeDebut()
    {
        return $this->annee_debut;
    }

    /**
     * Set annee_fin
     *
     * @param smallint $anneeFin
     */
    public function setAnneeFin($anneeFin)
    {
        $this->annee_fin = $anneeFin;
    }

    /**
     * Get annee_fin
     *
     * @return smallint 
     */
    public function getAnneeFin()
    {
        return $this->annee_fin;
    }
    
    public function __toString()
    {
    	return "".$this->annee_debut." / ".$this->annee_fin;
    }
}