<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "IMRIM\Bundle\LmsBundle\Entity\UserRepository")
 */
class User {

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
     * @ORM\Column(name = "mail", type = "string", length = 255)
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
     * @ORM\Column(name = "isSuspended", type = "boolean", nullable = false)
     * @Assert\NotNull()
     * 
     */
    private $isSuspended = false;

    /**
     * @var string $avatar
     *
     * @ORM\Column(name = "avatar", type = "string", length = 255)
     * @Assert\MaxLength(255)
     * @Assert\NotBlank()
     */
    private $avatar;

    /**
     * @var string $authType
     *
     * @ORM\Column(name = "authType", type = "string", length = 25, nullable = false)
     * @Assert\NotNull()
     * @Assert\Choice(callback = "getPossibleAuthType")
     */
    private $authType;

    /**
     * @var datetime $creationTime
     *
     * @ORM\Column(name = "creationTime", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $creationTime;

    /**
     * @var datetime $updateTime
     *
     * @ORM\Column(name = "updateTime", type = "datetime", nullable = false)
     * @Assert\DateTime()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $updateTime;

    /**
     * @var datetime $firstAccessTime
     *
     * @ORM\Column(name = "firstAccessTime", type = "datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    private $firstAccessTime;

    /**
     * @var datetime $lastLoginTime
     *
     * @ORM\Column(name = "lastLoginTime", type = "datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    private $lastLoginTime;

    /**
     * @var AdminRole $adminRole
     * 
     * @ORM\OneToOne(targetEntity = "AdminRole", mappedBy = "associatedUser")
     */
    private $adminRole;
    
    /**
     * @var TeacherRole $teacherRole
     * 
     * @ORM\OneToOne(targetEntity = "TeacherRole", mappedBy = "associatedUser")
     */
    private $teacherRole;
    
     /**
     * @var StudentRole $studentRole
     * 
     * @ORM\OneToOne(targetEntity = "StudentRole", mappedBy = "associatedUser")
     */
    private $studentRole;
   
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $groupsSubscription
     * 
     * @ORM\OneToMany(targetEntity = "GroupMembers", mappedBy = "user")
     */
    private $groupsSubscription;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $examAttempts
     * 
     * @ORM\OneToMany(targetEntity = "ExamAttempts", mappedBy = "user")
     */
    private $examAttempts;
    
    /**
     * @var Doctrine\Common\Collections\Collection $courseResponsibilities
     * 
     * @ORM\ManyToMany(targetEntity = "Course", inversedBy = "teachers")
     * @ORM\JoinTable(name="course_responsibilities")
     */
    private $courseResponsibilities;
    
    /**
     * @var Doctrine\Common\Collections\Collection $enrolments
     * 
     * @ORM\OneToMany(targetEntity = "UserEnrolment", mappedBy = "group")
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
    public static function getPossibleAuthType() 
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
}