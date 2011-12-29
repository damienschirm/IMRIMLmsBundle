<?php
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
include('Chart' . DIRECTORY_SEPARATOR . "IChart.php");
/**
 * Description of Chart
 *
 * @author Damien
 */
class Chart implements IChart {

    public function numberOfUsers($numberOfEnrolment) {
        $scriptJS = "<div id='colchart_div'>"
                . "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
                . "<script type='text/javascript'>"
                . "google.load('visualization', '1', {packages:['corechart']});"
                . "google.setOnLoadCallback(drawChart);"
                . " function drawChart() { "
                . "   var data = new google.visualization.DataTable();"
                . "   data.addColumn('string', 'Utilisateurs inscrits');"
                . "   data.addColumn('number', 'Effectif');"
                . "   data.addRows(["
                . "     ['Utilisateurs inscrits',    " . $numberOfEnrolment . "],"
                . "   ]);"
                . "   var options = {"
                . "     width: 400, height: 240,"
                . "   };"
                . "   var chart = new google.visualization.ColumnChart(document.getElementById('colchart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "</div>";

        return $scriptJS;
    }

    public function usersEnrolledInCourse(Controller $ctrl) {
        $courses = $ctrl->getDoctrine()
                ->getRepository('IMRIMLmsBundle:Course')
                ->findAll();
        $i = 0;
        $temp="";
        foreach ($courses as $course) {
            $i++;
            $qb = $ctrl->getDoctrine()->getEntityManager()->createQueryBuilder()
                    ->select('COUNT(ue.user)') //$i[0][1] 
                    ->from('IMRIMLmsBundle:UserEnrolment', 'ue')
                    ->where('ue.course = :course')
                    ->setParameter('course',$course);
            $q = $qb->getQuery();
            $result = $q->getSingleScalarResult();
            
            $temp = $temp."['" . $course->getName() . "'," . $result . "]";
            if ($i<count($courses)){
                $temp = $temp . ",";
            }
        }

        $scriptJS = "<div id='piechart_div'>"
                . "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
                . "<script type='text/javascript'>"
                . "google.load('visualization', '1', {packages:['corechart']});"
                . "google.setOnLoadCallback(drawChart);"
                . " function drawChart() { "
                . "   var data = new google.visualization.DataTable();"
                . "   data.addColumn('string', 'Cours');"
                . "   data.addColumn('number', 'Pourcentage');"
                . "   data.addRows(["
                . $temp
                . "   ]);"
                . "   var options = {"
                . "     width: 450, height: 300,"
                . "     title: 'Pourcentage d\'utilisateurs inscrits à un cours'"
                . "   };"
                . "   var chart = new google.visualization.PieChart(document.getElementById('piechart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "</div>";

        return $scriptJS;
    }

    public function averageOfTimeSpentByCourse(Controller $ctrl) {
        $users = $ctrl->getDoctrine()
                ->getRepository('IMRIMLmsBundle:User')
                ->findAll();
        
        $temp = "";
        $i = 0;
        foreach($users as $u){
            $i++;
            $qb = $ctrl->getDoctrine()->getEntityManager()->createQueryBuilder()
                    ->select('AVG(ue.timeSpent)')  
                    ->from('IMRIMLmsBundle:UserEnrolment', 'ue')
                    ->where('ue.user = :user')
                    ->setParameter('user',$u);
            $q = $qb->getQuery();
            $result = $q->getSingleScalarResult();
            if (!isset($result)){
                $result = 0;
            }
            $temp = $temp."['" . $u->getLogin() . "'," . $result . "]";
            if ($i<count($users)){
                $temp = $temp . ",";
            }
        }
        
        $scriptJS = "<div id='barchart_div'>"
                . "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
                . "<script type='text/javascript'>"
                . "google.load('visualization', '1', {packages:['corechart']});"
                . "google.setOnLoadCallback(drawChart);"
                . " function drawChart() { "
                . "   var data = new google.visualization.DataTable();"
                . "   data.addColumn('string', 'Utilisateurs');"
                . "   data.addColumn('number', 'Temps moyen');"
                . "   data.addRows(["
                . $temp
                . "   ]);"
                . "   var options = {"
                . "     width: 400, height: 240,"
                . "   };"
                . "   var chart = new google.visualization.BarChart(document.getElementById('barchart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "</div>";
        
        return $scriptJS;
    }

    public function listOfUsersNotHavingFinishedCourse(Controller $ctrl) {
        
    }

}

?>
