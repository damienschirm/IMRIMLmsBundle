<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 *
 * @author Damien
 */
interface IChartController {
    
    /**
     * @Route("/chart/pie", name="pieChart")
     * @Template()
     */
    public function drawPieAction() ;
    
    /**
     * @Route("/chart")
     * @Template()
     */
    public function ChartAction() ;
}

?>
