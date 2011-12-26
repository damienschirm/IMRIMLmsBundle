<?php

namespace IMRIM\Bundle\LmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

require_once('lib' . DIRECTORY_SEPARATOR . 'artichow' . DIRECTORY_SEPARATOR . 'Pie.class.php');
/**
 * Description of ChartController
 *
 * @author Damien
 */
class ChartController extends Controller implements IChartController {

    /**
     * @Route("/chart/pie", name="pieChart")
     * @Template()
     */
    public function drawPieAction() {
        
        $graph = new \Graph(400, 250);

        $graph->title->set("Camembert (exemple 1)");

        $graph->setAntiAliasing(TRUE);

        $values = array(20, 5, 13, 18, 10, 6, 11);

        $plot = new \Pie($values, \Pie::EARTH);
        $plot->setCenter(0.4, 0.55);
        $plot->setSize(0.7, 0.6);
        $plot->set3D(10);

        $plot->setLegend(array(
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        ));

        $plot->legend->setPosition(1.3);
        $plot->legend->shadow->setSize(0);

        $graph->add($plot);
        $graph->draw();
    }

    /**
     * @Route("/chart")
     * @Template()
     */
    public function ChartAction() {
        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
    . "<script type='text/javascript'>"
    . "google.load('visualization', '1', {packages:['corechart']});"
    . "google.setOnLoadCallback(drawChart);"
    . " function drawChart() { "
    . "   var data = new google.visualization.DataTable();"
    . "   data.addColumn('string', 'Tâches');"
    . "   data.addColumn('number', 'Heures par jour');"
    . "   data.addRows(["
    . "     ['Travail',    11],"
    . "     ['Manger',      2],"
    . "     ['Trajet',  2],"
    . "     ['TV', 2],"
    . "     ['Dormir',    7]"
    . "   ]);"

    . "   var options = {"
    . "     width: 450, height: 300,"
    . "     title: 'Mes activités journalières'"
    . "   };"

    . "   var chart = new google.visualization.PieChart(document.getElementById('chart_div'));"
    . "   chart.draw(data, options);"
    . " }"
    ."</script>";
        
        return $this->render('IMRIMLmsBundle:Chart:chart.html.twig', array('scriptJS' => $scriptJS));
    }
    
}

?>
