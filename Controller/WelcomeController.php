<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Welcome Controller
 *
 */
class WelcomeController extends Controller {

    /**
     * Welcome view.
     *
     * @Route("/", name="welcome")
     */
    public function indexAction() {
        $user = $this->get('security.context')->getToken()->getUser();
	if(!$user) {
		$this->redirect($this->generateUrl('login'));
	}
	$OrderingRoles = array('admin', 'teacher', 'student');
        // for each role if user has role redirect to role welcome page
	foreach($OrderingRoles as $role) {
            $getter = 'get' . ucfirst($role) . 'Role';
	    if( $user->$getter() != null)
            {
                return $this->redirect($this->generateUrl('welcome_' . $role));
            }
        }
        throw new \Exception('Current user has no role');
    }

    /**
     * Welcome admin view.
     *
     * @Route("/admin", name="welcome_admin")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function welcomeAdminAction() {
    
        return array();
    }

    /**
     * Welcome teacher view.
     *
     * @Route("/teacher", name="welcome_teacher")
     * @Secure(roles="ROLE_TEACHER")
     */
    public function welcomeTeacherAction() {
        return $this->redirect($this->generateUrl("imrim_lms_course_list_teacher"));
    }

    /**
     * Welcome student view.
     *
     * @Route("/student", name="welcome_student")
     * @Secure(roles="ROLE_STUDENT")
     */
    public function welcomeStudentAction() { 
        return $this->redirect($this->generateUrl("imrim_lms_course_list"));
    }
}
