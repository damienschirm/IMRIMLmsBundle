<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use IMRIM\Bundle\LmsBundle\Entity\Lesson;
use IMRIM\Bundle\LmsBundle\Form\LessonType;
use IMRIM\Bundle\LmsBundle\Form\LessonHtmlType;

class LessonController extends Controller {

    /**
     * Creates a lesson into a specific course
     * @Route("course/{courseId}/lesson/create", name = "imrim_lms_lesson_create")
     * @Template()
     * @Secure(roles="ROLE_TEACHER")
     * @Method({"GET","POST"})
     * @param integer $courseId
     * @return Array()
     * @throws AccessDeniedException 
     */
    public function createAction($courseId) {
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $course = $this->findCheckedCourse($courseId, $user);

        // Creation of the lesson
        $lesson = new Lesson();
        $lesson->setCoursePosition($course->getNextPosition());
        $lesson->setCourse($course);
        $lesson->setContent('Le&ccedil;on en construction');

        // Creation of the associated form
        $form = $this->createForm(new LessonType(), $lesson);

        if ($request->getMethod() == 'POST') {
            // Fills the form with what was in the parameters
            $form->bindRequest($request);

            if ($form->isValid()) {
                if ($lesson->getType() == 'video') {
                    $this->setContent('<video width="400" height="222" controls="controls"> <source src="___WEB_PATH___" type="___MIME_TYPE___" /> </video> ');
                }
                $em->persist($lesson);
                $em->flush();
                return $this->redirect($this->generateUrl('imrim_lms_lesson_edit', array(
                                    'lessonId' => $lesson->getId(),
                                    'courseId' => $courseId,
                                )));
            }
        }
        return array('form' => $form->createView(),
            'id' => $courseId,
            'type' => null,
        );
    }

    /**
     * Edit a lesson into a specific course
     * @Route("course/{courseId}/lesson/{lessonId}/edit", name = "imrim_lms_lesson_edit")
     * @Secure(roles="ROLE_TEACHER")
     * @Method({"GET","POST"})
     * @param integer $courseId
     * @param integer $lessonId
     * @return Array()
     * @throws AccessDeniedException 
     */
    public function editAction($courseId, $lessonId) {
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $course = $this->findCheckedCourse($courseId, $user);

        // Creation of the lesson
        $lesson = $this->findCheckedLesson($lessonId, $course);

        // Creation of the associated form
        $formType = $this->createLessonFormType($lesson->getType());

        $form = $this->createForm($formType, $lesson);

        if ($request->getMethod() == 'POST') {
            // Fills the form with what was in the parameters
            $form->bindRequest($request);

            if ($form->isValid()) {
                $lesson->setUpdated();
                $em->persist($lesson);
                $em->flush();
            }
        }

        if ($formType instanceof LessonType) {
            $template = 'edit';
        } else {
            $template = 'edit' . ucfirst(strtolower($lesson->getType()));
        }

        return $this->render('IMRIMLmsBundle:Lesson:' . $template . '.html.twig', array('form' => $form->createView(),
                    'courseId' => $courseId,
                    'lessonId' => $lessonId,
                    'deleteForm' => $this->createDeleteForm($lessonId)->createView(),
                ));
    }

    /**
     * Deletes the lesson.
     * @Route("course/{courseId}/lesson/{lessonId}/delete", name = "imrim_lms_lesson_delete")
     * @Secure(roles="ROLE_TEACHER")
     * @Method({"POST"})
     * @param integer $courseId
     * @param integer $lessonId
     * @throws AccessDeniedException 
     */
    public function deleteAction($courseId, $lessonId) {
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();

        // Creation of the associated form
        $form = $this->createDeleteForm($lessonId);

        // Fills the form with what was in the parameters
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $course = $this->findCheckedCourse($courseId, $user);

            $lesson = $this->findCheckedLesson($lessonId, $course);
            // Deletes the lesson
            $em->remove($lesson);
            $em->flush();
            return $this->redirect($this->generateUrl('imrim_lms_course_edit', array(
                                'id' => $courseId
                            )));
        }

        throw new AccessDeniedException();
    }

    /**
     * Return the view of the lesson
     * @Route("course/{courseId}/lesson/{lessonId}/view", name = "imrim_lms_lesson_view")
     * @Secure(roles="ROLE_STUDENT")
     * @Template()
     * @Method({"GET"})
     * @param type $courseId
     * @param type $lessonId
     * @return Array() 
     */
    public function viewAction($courseId, $lessonId) {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $course = $this->findCourse($courseId, $user);

        // Creation of the lesson
        $lesson = $this->findCheckedLesson($lessonId, $course);

        return array('lesson' => $lesson);
    }

    /**
     * Returns a new instance of corresponding form to edit a lesson.
     * @param type $lessonType
     * @return \IMRIM\Bundle\LmsBundle\Form\LessonHtmlType| \IMRIM\Bundle\LmsBundle\Form\LessonType
     */
    private function createLessonFormType($lessonType) {
        $base = "\\IMRIM\\Bundle\\LmsBundle\\Form\\";
        $className = $base . 'Lesson' . ucfirst(strtolower($lessonType)) . 'Type';
        if (class_exists($className)) {
            return new $className();
        } else {
            return new LessonType();
        }
    }

    /**
     * Creates a form for lesson's deletion.
     * @param integer $lessonId
     * @return FormBuilder 
     */
    private function createDeleteForm($lessonId) {
        return $this->createFormBuilder(array('$lessonId' => $lessonId))
                        ->add('lessonId', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Find a lesson and checks the associated course.
     * @param integer $lessonId
     * @param Course $course
     * @return Lesson
     * @throws AccessDeniedException 
     */
    private function findCheckedLesson($lessonId, $course) {
        $em = $this->getDoctrine()->getEntityManager();
        $lesson = $em->getRepository('IMRIMLmsBundle:Lesson')->find($lessonId);

        // Verifications
        if ($lesson == null) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
        if ($lesson->getCourse()->equals($course)) {
            return $lesson;
        } else {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
    }

    /**
     * Find a course and checks the related responsibilities.
     * @param integer $courseId
     * @param User $user
     * @return Course
     * @throws AccessDeniedException 
     */
    private function findCheckedCourse($courseId, $user) {
        $em = $this->getDoctrine()->getEntityManager();
        $course = $em->getRepository('IMRIMLmsBundle:Course')->find($courseId);

        // Verifications
        if ($course == null) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
        if (!$user->isResponsibleFor($course)) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation d'éditer ce cours.");
        }
        return $course;
    }

    /**
     * Find a course and check if the user is able to consult it. 
     * @param integer $courseId
     * @param User $user
     * @return Course
     * @throws AccessDeniedException 
     */
    private function findCourse($courseId, $user) {
        $em = $this->getDoctrine()->getEntityManager();
        $course = $em->getRepository('IMRIMLmsBundle:Course')->find($courseId);

        // Verifications
        if ($course == null) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation de consulter ce cours.");
        }
        if (!$em->getRepository('IMRIMLmsBundle:UserEnrolment')->findOneByUserAndCourse($user, $course)) {
            throw new AccessDeniedException("Vous n'avez pas l'autorisation de consulter ce cours.");
        }
        return $course;
    }

}