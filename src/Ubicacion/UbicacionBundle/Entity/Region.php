<?php

namespace Ubicacion\UbicacionBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Ubicacion\UbicacionBundle\Repository\RegionRepository")
 */
class Region
{
    /**
     * @ORM\OneToMany(targetEntity="Provinces", mappedBy="region")
     */ 
    protected $provinces;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
        $this->provinces = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Region
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
     * @return Region
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
     * Add provinces
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Provinces $provinces
     * @return Region
     */
    public function addProvince(\Ubicacion\UbicacionBundle\Entity\Provinces $provinces)
    {
        $this->provinces[] = $provinces;

        return $this;
    }

    /**
     * Remove provinces
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Provinces $provinces
     */
    public function removeProvince(\Ubicacion\UbicacionBundle\Entity\Provinces $provinces)
    {
        $this->provinces->removeElement($provinces);
    }

    /**
     * Get provinces
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProvinces()
    {
        return $this->provinces;
    }
}
