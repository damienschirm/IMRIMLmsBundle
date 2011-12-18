<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\GroupMembers
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "IMRIM\Bundle\LmsBundle\Entity\GroupMembersRepository")
 */
class GroupMembers
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
     * @var datetime $addTime
     *
     * @ORM\Column(name = "addTime", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     */
    private $addTime;

    /**
     * @var User $user
     * 
     * @ORM\ManyToOne(targetEntity = "User", inversedBy = "groupsSubscription")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id") 
     */
    private $user;
    
    /**
     * @var UserGroup $group
     * 
     * @ORM\ManyToOne(targetEntity = "UserGroup", inversedBy = "members")
     * @ORM\JoinColumn(name = "group_id", referencedColumnName = "id") 
     */
    private $group;

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
     * Set addTime
     *
     * @param datetime $addTime
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;
    }

    /**
     * Get addTime
     *
     * @return datetime 
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * Set user
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $user
     */
    public function setUser(\IMRIM\Bundle\LmsBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\UserGroup $group
     */
    public function setGroup(\IMRIM\Bundle\LmsBundle\Entity\UserGroup $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\UserGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }
}