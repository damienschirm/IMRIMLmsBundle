<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Config
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Config
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * @var string $nameParameter
     *
     * @ORM\Column(name = "nameParameter", type = "string", length = 255, nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\MaxLength(255)
     * @Assert\Regex("/^[a-zA-Z0-9._-]+$/")
     */
    private $nameParameter;

    /**
     * @var text $value
     *
     * @ORM\Column(name = "value", type = "text", nullable = false)
     * @Assert\NotNull()
     */
    private $value;

    /**
     * @var datetime $creationTime
     *
     * @ORM\Column(name = "creationTime", type = "datetime", nullable = false)
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $creationTime;

    /**
     * @var datetime $updateTime
     *
     * @ORM\Column(name = "updateTime", type = "datetime", nullable = false)
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $updateTime;

    public function __construct()
    {
        $this->creationTime(new \DateTime("now"));
        $this->updateTime(new \DateTime("now"));
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
     * Set nameParameter
     *
     * @param string $nameParameter
     */
    public function setNameParameter($nameParameter)
    {
        $this->nameParameter = $nameParameter;
        $this->setUpdated();
    }

    /**
     * Get nameParameter
     *
     * @return string 
     */
    public function getNameParameter()
    {
        return $this->nameParameter;
    }

    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->setUpdated();
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set creationTime
     *
     * @param datetime $creationTime
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * Get creationTime
     *
     * @return datetime 
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Set updateTime
     *
     * @param datetime $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * Get updateTime
     *
     * @return datetime 
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
    
    /**
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
}