<?php

namespace EDiff\Bundle\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDiff\Bundle\AdminBundle\Entity\Classe_Eleve_Annee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EDiff\Bundle\AdminBundle\Entity\Classe_Eleve_AnneeRepository")
 */
class Classe_Eleve_Annee
{

    
	/**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\Classe")
     */
    private $classe;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EDiff\Bundle\AdminBundle\Entity\AnneeScolaire")
     */
    private $annee;
    
    // On définit le getter et le setter pour Classe
    public function getClasse()
    {
        return $this->classe;
    }
    public function setClasse(\EDiff\Bundle\AdminBundle\Entity\Classe $classe)
    {
        $this->classe = $classe;
    }

    // On définit le getter et le setter pour User
    public function getUser()
    {
        return $this->user;
    }
    public function setUser(\EDiff\Bundle\AdminBundle\Entity\User $user)
    {
        $this->user = $user;
    }
    
	// On définit le getter et le setter pour Annee
    public function getAnnee()
    {
        return $this->annee;
    }
    public function setAnnee(\EDiff\Bundle\AdminBundle\Entity\AnneeScolaire $annee)
    {
        $this->annee = $annee;
    }   
}