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
        $qb = $this->container->get('doctrine')->getEntityManager()->createQueryBuilder()
                ->select('COUNT(u.login)') //$i[0][1] 
                //->addselect('u.login') //$i[0]['login']
                ->from('IMRIMLmsBundle:User', 'u');
        $q = $qb->getQuery();
        $i = $q->getSingleScalarResult();
        //getResult(); // retourne un array d'objet.
        //$t = $this->container->get('doctrine')->getEntityManager()->find('IMRIMLmsBundle:User', 1);
        $scriptJSCol = $p->numberOfUsers($i);
        //-----------------------------------------------------
        //------- Statistic : % of users enrolled -------        
        $scriptJSPie = $p->usersEnrolledInCourse($this);
        //-----------------------------------------------------
        //------- Statistic : average of the time spent by user by course ---- -------
        $scriptJSBar = $p->averageOfTimeSpentByUser($this);
        $scriptJSBar2 = $p->averageOfTimeSpentByCourse($this);
        //--------------------------------------------------------
        //------- Statistic : list of users who have not finished the course before the expiration date -----
        $scriptJSTable = $p->listOfUsersNotHavingFinishedCourse($this);
        //------------------------------------------------------------------
        
        return $this->render('IMRIMLmsBundle:Chart:chart.html.twig', array('nbOfUsers' => $i,
            'scriptJSCol' => $scriptJSCol,
            'scriptJSPie' => $scriptJSPie,
            'scriptJSBar' => $scriptJSBar,
            'scriptJSBar2' => $scriptJSBar2,
            'scriptJSTable' => $scriptJSTable));
    }
    
}

?>
