<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//require_once('lib' . DIRECTORY_SEPARATOR . 'artichow' . DIRECTORY_SEPARATOR . 'Pie.class.php');
include('Chart' . DIRECTORY_SEPARATOR . "Chart.php");

/**
 * Description of ChartController
 *
 * @author Damien
 */
class ChartController extends Controller {

    /**
     * @Route("/chart")
     * @Template()
     */
    public function ChartAction() {
        $p = new \Chart();
        //-------- Statistic : number of users ------
        $scriptJSGeo = $p->numberOfUsers();
        
        $qb = $this->container->get('doctrine')->getEntityManager()->createQueryBuilder()
                ->select('COUNT(u.login)') //$i[0][1] 
                //->addselect('u.login') //$i[0]['login']
                ->from('IMRIMLmsBundle:User', 'u');
        $q = $qb->getQuery();
        $i = $q->getSingleScalarResult();
        //getResult(); // retourne un array d'objet.
        //$t = $this->container->get('doctrine')->getEntityManager()->find('IMRIMLmsBundle:User', 1);
        //-----------------------------------------------------
        //------- Statistic : % of users enrolled -------
        $courses = $this->getDoctrine()
                ->getRepository('IMRIMLmsBundle:Course')
                ->findAll();
        
        $scriptJSPie = $p->usersEnrolledInCourse($this);
        //-----------------------------------------------------
        return $this->render('IMRIMLmsBundle:Chart:chart.html.twig', array('nbOfUsers' => $i,
            'scriptJSGeo' => $scriptJSGeo,
            'courses' => $courses,
            'scriptJSPie' => $scriptJSPie));
    }
    
}

?>
