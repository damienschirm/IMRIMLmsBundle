<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use IMRIM\Bundle\LmsBundle\LmsToolbox;

/**
 * IMRIM\Bundle\LmsBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "IMRIM\Bundle\LmsBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable {

    /**
     * @var integer $id
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * @var string $login
     *
     * @ORM\Column(name = "login", type = "string", length = 255, nullable = false, unique = true)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(pattern = "/^[a-zA-Z0-9.-]+$/", message = "Votre login ne doit comporter que des caractères alphanumériques ou les caractères '-' et '.'.")
     * @Assert\MaxLength(255)
     */
    private $login;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name = "firstName", type = "string", length = 255, nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(
     *    pattern = "/^[Éa-zA-Z+-\sàáâãäåçèéêëìíîïðòóôõöùúûüýÿ']+$/",
     *    match = true,
     *    message = "Votre prénom semble invalide"
     * )
     * @Assert\MaxLength(255)
     */
    private $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name = "lastName", type = "string", length = 255, nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(
     *    pattern = "/^[Éa-zA-Z+-\sàáâãäåçèéêëìíîïðòóôõöùúûüýÿ']+$/",
     *    match = true,
     *    message = "Votre nom semble invalide"
     * )
     * @Assert\MaxLength(255)
     */
    private $lastName;

    /**
     * @var string $mail
     *
     * @ORM\Column(name = "mail", type = "string", length = 255, nullable = true)
     * @Assert\NotBlank()
     * @Assert\Email(message = "Vous devez fournir une adresse email valide.", checkMX = true)
     * @Assert\MaxLength(255)
     */
    private $mail;

    /**
     * @var string $password
     *
     * @ORM\Column(name = "password", type = "string", length = 40)
     * @Assert\Regex("/^[a-f0-9]{5,40}$/")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name = "salt", type = "string", length = 255)
     * @Assert\MaxLength(255)
     */
    private $salt;

    /**
     * @var boolean $isSuspended
     *
     * @ORM\Column(name = "isSuspended", type = "boolean")
     * @Assert\NotNull()
     * 
     */
    private $isSuspended = false;

    /**
     * @var string $avatar
     *
     * @ORM\Column(name = "avatar", type = "string", length = 255, nullable = false)
     * @Assert\MaxLength(255)
     */
    private $avatar="";

    /**
     * @var string $authType
     *
     * @ORM\Column(name = "authType", type = "string", length = 25)
     * @Assert\NotNull()
     * @Assert\Choice(callback = "getPossibleAuthTypes")
     */
    private $authType="internal";

    /**
     * @var datetime $creationTime
     *
     * @ORM\Column(name = "creationTime", type = "datetime")
     * @Assert\DateTime()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $creationTime;

    /**
     * @var datetime $updateTime
     *
     * @ORM\Column(name = "updateTime", type = "datetime")
     * @Assert\DateTime()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $updateTime;

    /**
     * @var datetime $firstAccessTime
     *
     * @ORM\Column(name = "firstAccessTime", type = "datetime", nullable = true)
     * @Assert\DateTime()
     */
    private $firstAccessTime;

    /**
     * @var datetime $lastLoginTime
     *
     * @ORM\Column(name = "lastLoginTime", type = "datetime", nullable = true)
     * @Assert\DateTime()
     */
    private $lastLoginTime;

    /**
     * @var AdminRole $adminRole
     * 
     * @ORM\OneToOne(targetEntity = "AdminRole", mappedBy = "associatedUser", cascade = {"persist"})
     */
    private $adminRole;
    
    /**
     * @var TeacherRole $teacherRole
     * 
     * @ORM\OneToOne(targetEntity = "TeacherRole", mappedBy = "associatedUser", cascade = {"persist"})
     */
    private $teacherRole;
    
     /**
     * @var StudentRole $studentRole
     * 
     * @ORM\OneToOne(targetEntity = "StudentRole", mappedBy = "associatedUser", cascade = {"persist"})
     */
    private $studentRole;
   
    /**
     * @var Doctrine\Common\Collections\Collection $groupsSubscription
     * 
     * @ORM\OneToMany(targetEntity = "GroupMembers", mappedBy = "user")
     */
    private $groupsSubscription;
    
    /**
     * @var Doctrine\Common\Collections\Collection $examAttempts
     * 
     * @ORM\OneToMany(targetEntity = "ExamAttempts", mappedBy = "user")
     */
    private $examAttempts;
    
    /**
     * @var Doctrine\Common\Collections\Collection $courseResponsibilities
     * 
     * @ORM\ManyToMany(targetEntity = "Course", inversedBy = "teachers", cascade = {"persist"})
     * @ORM\JoinTable(name="course_responsibilities")
     */
    private $courseResponsibilities;
    
    /**
     * @var Doctrine\Common\Collections\Collection $enrolments
     * 
     * @ORM\OneToMany(targetEntity = "UserEnrolment", mappedBy = "group", cascade = {"persist"})
     */
    private $enrolments;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
        $this->setUpdated();
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        $this->setUpdated();
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        $this->setUpdated();
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set mail
     *
     * @param string $mail
     */
    public function setMail($mail) {
        $this->mail = $mail;
        $this->setUpdated();
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail() {
        return $this->mail;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
        $this->setUpdated();
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        $this->setUpdated();
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set isSuspended
     *
     * @param boolean $isSuspended
     */
    public function setIsSuspended($isSuspended) {
        $this->isSuspended = $isSuspended;
        $this->setUpdated();
    }

    /**
     * Get isSuspended
     *
     * @return boolean 
     */
    public function getIsSuspended() {
        return $this->isSuspended;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        $this->setUpdated();
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set authType
     *
     * @param string $authType
     */
    public function setAuthType($authType) {
        $this->authType = $authType;
        $this->setUpdated();
    }

    /**
     * Get authType
     *
     * @return string 
     */
    public function getAuthType() {
        return $this->authType;
    }

    /**
     * Set creationTime
     *
     * @param datetime $creationTime
     */
    public function setCreationTime($creationTime) {
        $this->creationTime = $creationTime;
    }

    /**
     * Get creationTime
     *
     * @return datetime 
     */
    public function getCreationTime() {
        return $this->creationTime;
    }

    /**
     * Set updateTime
     *
     * @param datetime $updateTime
     */
    public function setUpdateTime($updateTime) {
        $this->updateTime = $updateTime;
    }

    /**
     * Get updateTime
     *
     * @return datetime 
     */
    public function getUpdateTime() {
        return $this->updateTime;
    }

    /**
     * Set firstAccessTime
     *
     * @param datetime $firstAccessTime
     */
    public function setFirstAccessTime($firstAccessTime) {
        $this->firstAccessTime = $firstAccessTime;
    }

    /**
     * Get firstAccessTime
     *
     * @return datetime 
     */
    public function getFirstAccessTime() {
        return $this->firstAccessTime;
    }

    /**
     * Set lastLoginTime
     *
     * @param datetime $lastLoginTime
     */
    public function setLastLoginTime($lastLoginTime) {
        $this->lastLoginTime = $lastLoginTime;
        $this->setUpdated();
    }

    /**
     * Get lastLoginTime
     *
     * @return datetime 
     */
    public function getLastLoginTime() {
        return $this->lastLoginTime;
    }
    
    /**
     * Get possible authentication types (LDAP, internal...) 
     * 
     * @return array
     */
    public static function getPossibleAuthTypes() 
    {
        return array(
            'ldap',
            'internal',
        );
    }

    /**
     * Set adminRole
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\AdminRole $adminRole
     */
    public function setAdminRole(\IMRIM\Bundle\LmsBundle\Entity\AdminRole $adminRole)
    {
        $this->adminRole = $adminRole;
        $adminRole->setAssociatedUser($this);
        $this->setUpdated();
    }

    /**
     * Get adminRole
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\AdminRole 
     */
    public function getAdminRole()
    {
        return $this->adminRole;
    }

    /**
     * Set teacherRole
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\TeacherRole $teacherRole
     */
    public function setTeacherRole(\IMRIM\Bundle\LmsBundle\Entity\TeacherRole $teacherRole)
    {
        $this->teacherRole = $teacherRole;
        $teacherRole->setAssociatedUser($this);
        $this->setUpdated();
    }

    /**
     * Get teacherRole
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\TeacherRole 
     */
    public function getTeacherRole()
    {
        return $this->teacherRole;
    }

    /**
     * Set studentRole
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\StudentRole $studentRole
     */
    public function setStudentRole(\IMRIM\Bundle\LmsBundle\Entity\StudentRole $studentRole)
    {
        $this->studentRole = $studentRole;
        $studentRole->setAssociatedUser($this);
        $this->setUpdated();
    }

    /**
     * Get studentRole
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\StudentRole 
     */
    public function getStudentRole()
    {
        return $this->studentRole;
    }
    
    public function __construct()
    {
        $this->groupsSubscription = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationTime = new \DateTime("now");
        $this->updateTime = new \DateTime("now");
        $this->salt = LmsToolbox::generateRandomString();
        $encoder = new MessageDigestPasswordEncoder('sha1', false, 1);
        $this->password = $encoder->encodePassword(LmsToolbox::generateRandomString(),$this->salt);
    }
    
    /**
     * Add groupsSubscription
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\GroupMembers $groupsSubscription
     */
    public function addGroupMembers(\IMRIM\Bundle\LmsBundle\Entity\GroupMembers $groupsSubscription)
    {
        $this->groupsSubscription[] = $groupsSubscription;
    }

    /**
     * Get groupsSubscription
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGroupsSubscription()
    {
        return $this->groupsSubscription;
    }

    /**
     * Add examAttempts
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\ExamAttempts $examAttempts
     */
    public function addExamAttempts(\IMRIM\Bundle\LmsBundle\Entity\ExamAttempts $examAttempts)
    {
        $this->examAttempts[] = $examAttempts;
    }

    /**
     * Get examAttempts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getExamAttempts()
    {
        return $this->examAttempts;
    }

    /**
     * Add courseResponsibilities
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Course $courseResponsibilities
     */
    public function addCourse(\IMRIM\Bundle\LmsBundle\Entity\Course $courseResponsibilities)
    {
        $this->courseResponsibilities[] = $courseResponsibilities;
    }

    /**
     * Get courseResponsibilities
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCourseResponsibilities()
    {
        return $this->courseResponsibilities;
    }

    /**
     * Add enrolments
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\UserEnrolment $enrolments
     */
    public function addUserEnrolment(\IMRIM\Bundle\LmsBundle\Entity\UserEnrolment $enrolments)
    {
        $this->enrolments[] = $enrolments;
    }

    /**
     * Get enrolments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEnrolments()
    {
        return $this->enrolments;
    }
    
    /**
     * Set the updateTime to now
     */
    public function setUpdated()
    {
        return $this->setUpdateTime(new \DateTime("now"));
    }
    
    /**
     * Determines if two users are equal
     * @param UserInterface $user
     * @return boolean 
     */
    public function equals(UserInterface $user)
    {
        return ($this->getId() == $user->getId());
    }
    
    /**
     * Removes sensitive data from the user 
     */
    public function eraseCredentials()
    {
        $this->password = '';
    }
    
    /**
    * Returns the roles granted to the user
    * @return array() 
    */
    public function getRoles()
    {
        $roles = array();
        $methods = get_class_methods($this);
        foreach ($methods as $method)
        {
            /* find all role getters */
            if(preg_match('/^get(.*)Role$/', $method, $roleMatch))
            {
                $getter = $method;
                /* Test if user has the role*/
                if($this->$getter() != null)
                {
                    /* $roleMatch[1] is the role name like Student, Teacher, ...  */
                    /* So now we will had the role*/
                    $roles[] = 'ROLE_'.strtoupper($roleMatch[1]);
                }
            }
        }
        return $roles;
    }
    
    /**
     * Returns the username used to authenticate the user 
     * @return string
     */
    public function getUsername()
    {
        return $this->getLogin();
    }
    
    /**
     * Returns the login
     * @return string 
     */
    public function __toString()
    {
        return $this->getLogin();
    }
    
    /**
     * Returns true if the current user is responsible for the course
     * @param Course $course
     * @return boolean 
     */
    public function isResponsibleFor(Course $course){
        // If the user is not a teacher, return false
        if($this->getTeacherRole() == null)
        {
            return false;
        }
        foreach($this->getCourseResponsibilities() as $c) 
        {
            if($c->equals($course))
            {
                return true;
            }     
        }
      return false;  
    }
    
    // TODO: improve to serialize all variables
    /**
     * Returns a string which represents the current user.
     * @return string 
     */
    public function serialize() {
        $data = array();
        
        $data['id'] = $this->getId();
        $data['login'] = $this->getLogin();
        $data['firstName'] = $this->getFirstName();
        $data['lastName'] = $this->getLastName();        
        
        return serialize($data);
    }

    /**
     * Unserializes a string. 
     * @param string $serialized 
     */
    public function unserialize($serialized) {
        $data = unserialize($serialized);
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
    }
}