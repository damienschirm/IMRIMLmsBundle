<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var text $text
     *
     * @ORM\Column(name = "text", type = "text", nullable = false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @var Doctrine\Common\Collections\Collection $answers
     * 
     * @ORM\OneToMany(targetEntity = "Answer", mappedBy = "question")
    */
    private $answers;
    
    /**
     * @var Exam $exam
     * 
     * @ORM\ManyToOne(targetEntity = "Exam", inversedBy = "questions")
     * @ORM\JoinColumn(name = "exam_id", referencedColumnName = "id") 
     */
    private $exam;
    
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
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add answers
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Answer $answers
     */
    public function addAnswer(\IMRIM\Bundle\LmsBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
    }

    /**
     * Get answers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set exam
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Exam $exam
     */
    public function setExam(\IMRIM\Bundle\LmsBundle\Entity\Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get exam
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\Exam 
     */
    public function getExam()
    {
        return $this->exam;
    }
    
    /**
     * Sets the update time
     */
    public function setUpdated()
    {
        if ($this->getExam()!=NULL) // TODO verifier la condition
        {
            $this->getExam()->setUpdated();
        }
    }
}