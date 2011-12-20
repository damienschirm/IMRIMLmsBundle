<?php

namespace IMRIM\Bundle\LmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IMRIM\Bundle\LmsBundle\Entity\Answer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IMRIM\Bundle\LmsBundle\Entity\AnswerRepository")
 */
class Answer
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
     * @var boolean $isExpected
     *
     * @ORM\Column(name = "isExpected", type = "boolean", nullable = false) 
     * @Assert\NotNull()
     */
    private $isExpected = false;

    /**
     * @var Question $question
     * 
     * @ORM\ManyToOne(targetEntity = "Question", inversedBy = "answers")
     * @ORM\JoinColumn(name = "question_id", referencedColumnName = "id") 
     */
    private $question;

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

    /**
     * Set isExpected
     *
     * @param boolean $isExpected
     */
    public function setIsExpected($isExpected)
    {
        $this->isExpected = $isExpected;
    }

    /**
     * Get isExpected
     *
     * @return boolean 
     */
    public function getIsExpected()
    {
        return $this->isExpected;
    }

    /**
     * Set question
     *
     * @param IMRIM\Bundle\LmsBundle\Entity\Question $question
     */
    public function setQuestion(\IMRIM\Bundle\LmsBundle\Entity\Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get question
     *
     * @return IMRIM\Bundle\LmsBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
    
    /**
     * Sets the update time
     */
    public function setUpdated()
    {
        if ($this->getQuestion()!=NULL)
        {
            $this->getQuestion()->setUpdated();
        }
    }
}