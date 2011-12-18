<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IMRIM\Bundle\LmsBundle\Entity\TeacherRole
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "IMRIM\Bundle\LmsBundle\Entity\TeacherRoleRepository")
 */
class TeacherRole
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
     * @var User $associatedUser
     * 
     * @ORM\OneToOne(targetEntity = "User", inversedBy = "teacherRole")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id")
     */
    private $associatedUser;

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
     * Set associatedUser
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\User $associatedUser
     */
    public function setAssociatedUser(\IMRIM\Bundle\LmsBundle\Entity\User $associatedUser)
    {
        $this->associatedUser = $associatedUser;
    }

    /**
     * Get associatedUser
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\User 
     */
    public function getAssociatedUser()
    {
        return $this->associatedUser;
    }
}