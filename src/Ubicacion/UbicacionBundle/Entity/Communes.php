<?php

namespace Ubicacion\UbicacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Communes
 *
 * @ORM\Table(name="communes")
 * @ORM\Entity(repositoryClass="Ubicacion\UbicacionBundle\Repository\CommunesRepository")
 */
class Communes
{
    /**
     * @ORM\ManyToOne(targetEntity="Provinces", inversedBy="communes")
     * @ORM\JoinColumn(name="province_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @var int
     *
     * @ORM\Column(name="province_id", type="integer")
     */
    private $provinceId;

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
     * Set provinceId
     *
     * @param integer $provinceId
     * @return Communes
     */
    public function setProvinceId($provinceId)
    {
        $this->provinceId = $provinceId;

        return $this;
    }

    /**
     * Get provinceId
     *
     * @return integer 
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Communes
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
     * @return Communes
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
     * Set provinces
     *
     * @param \Ubicacion\UbicacionBundle\Entity\Provinces $provinces
     * @return Communes
     */
    public function setProvinces(\Ubicacion\UbicacionBundle\Entity\Provinces $provinces = null)
    {
        $this->provinces = $provinces;

        return $this;
    }

    /**
     * Get provinces
     *
     * @return \Ubicacion\UbicacionBundle\Entity\Provinces 
     */
    public function getProvinces()
    {
        return $this->provinces;
    }
}
