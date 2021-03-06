<?php

namespace Ubicacion\UbicacionBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provinces
 *
 * @ORM\Table(name="provinces")
 * @ORM\Entity(repositoryClass="Ubicacion\UbicacionBundle\Repository\ProvincesRepository")
 */
class Provinces
{
    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="provinces")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", onDelete="CASCADE")
    */ 
    protected $region;
    
    /**
     * @ORM\OneToMany(targetEntity="Communes", mappedBy="provinces")
     */  
    protected $communes;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="region_id", type="integer")
     */
    private $regionId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=90)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=15)
     */
    private $status;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
    }
    
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
     * Set regionId
     *
     * @param integer $regionId
     * @return Provinces
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;

        return $this;
    }

    /**
     * Get regionId
     *
     * @return integer 
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Provinces
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Provinces
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set region
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Region $region
     * @return Provinces
     */
    public function setRegion(\Ubicacion\UbicacionBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Ubicacion\UbicacionBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Add communes
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Communes $communes
     * @return Provinces
     */
    public function addCommune(\Ubicacion\UbicacionBundle\Entity\Communes $communes)
    {
        $this->communes[] = $communes;

        return $this;
    }

    /**
     * Remove communes
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Communes $communes
     */
    public function removeCommune(\Ubicacion\UbicacionBundle\Entity\Communes $communes)
    {
        $this->communes->removeElement($communes);
    }

    /**
     * Get communes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommunes()
    {
        return $this->communes;
    }
}
