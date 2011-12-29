<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\QueryBuilder;

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
        $scriptJSGeo = $p->numberOfUsers();
        
        $qb = $this->container->get('doctrine')->getEntityManager()->createQueryBuilder()
                ->select('COUNT(u.login)')
                ->from('IMRIMLmsBundle:User', 'u');
        $q = $qb->getQuery();
        $i = $q->getSingleScalarResult();
        //getResult()
        //$t = $this->container->get('doctrine')->getEntityManager()->find('IMRIMLmsBundle:User', 1);

        return $this->render('IMRIMLmsBundle:Chart:chart.html.twig', array('nbOfUsers' => $i, 'scriptJSGeo' => $scriptJSGeo));
    }
    
}

?>
