<?php

namespace IMRIM\Bundle\LmsBundle\Chart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of Chart
 *
 * @author Damien
 */
class Chart implements IChart {

    public function numberOfUsers($numberOfEnrolment) {
        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
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
                . "<div id='colchart_div'>"
                . "</div>";

        return $scriptJS;
    }

    public function usersEnrolledInCourse(Controller $ctrl) {
        $courses = $ctrl->getDoctrine()
                ->getRepository('IMRIMLmsBundle:Course')
                ->findAll();
        $i = 0;
        $temp = "";
        foreach ($courses as $course) {
            $i++;
            $qb = $ctrl->getDoctrine()->getEntityManager()->createQueryBuilder()
                    ->select('COUNT(ue.user)') //$i[0][1] 
                    ->from('IMRIMLmsBundle:UserEnrolment', 'ue')
                    ->where('ue.course = :course')
                    ->setParameter('course', $course);
            $q = $qb->getQuery();
            $result = $q->getSingleScalarResult();

            $temp = $temp . "['Effectif: " . $course->getName() . "'," . $result . "]";
            if ($i < count($courses)) {
                $temp = $temp . ",";
            }
        }

        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
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
                . "<div id='piechart_div'>"
                . "</div>";

        return $scriptJS;
    }

    public function averageOfTimeSpentByUser(Controller $ctrl) {
        $users = $ctrl->getDoctrine()
                ->getRepository('IMRIMLmsBundle:User')
                ->findAll();

        $temp = "";
        $i = 0;
        foreach ($users as $u) {
            $i++;
            $qb = $ctrl->getDoctrine()->getEntityManager()->createQueryBuilder()
                    ->select('AVG(ue.timeSpent)')
                    ->from('IMRIMLmsBundle:UserEnrolment', 'ue')
                    ->where('ue.user = :user')
                    ->setParameter('user', $u);
            $q = $qb->getQuery();
            $result = $q->getSingleScalarResult();
            if (!isset($result)) {
                $result = 0;
            }
            $temp = $temp . "['" . $u->getFirstName() . " " . $u->getLastName() . "'," . $result . "]";
            if ($i < count($users)) {
                $temp = $temp . ",";
            }
        }

        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
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
                . "     title: 'Temps moyen passé par un utilisateur sur tous ses cours'"
                . "   };"
                . "   var chart = new google.visualization.BarChart(document.getElementById('barchart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "<div id='barchart_div'>"
                . "</div>";

        return $scriptJS;
    }

    public function averageOfTimeSpentByCourse(Controller $ctrl) {
        $courses = $ctrl->getDoctrine()
                ->getRepository('IMRIMLmsBundle:Course')
                ->findAll();

        $temp = "";
        $i = 0;
        foreach ($courses as $c) {
            $i++;
            $qb = $ctrl->getDoctrine()->getEntityManager()->createQueryBuilder()
                    ->select('AVG(ue.timeSpent)')
                    ->from('IMRIMLmsBundle:UserEnrolment', 'ue')
                    ->where('ue.course = :course')
                    ->setParameter('course', $c);
            $q = $qb->getQuery();
            $result = $q->getSingleScalarResult();
            if (!isset($result)) {
                $result = 0;
            }
            $temp = $temp . "['" . $c->getName() . "'," . $result . "]";
            if ($i < count($courses)) {
                $temp = $temp . ",";
            }
        }

        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
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
                . "     title: 'Temps moyen passé par tous les utilisateurs sur un cours'"
                . "   };"
                . "   var chart = new google.visualization.BarChart(document.getElementById('bar2chart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "<div id='bar2chart_div'>"
                . "</div>";

        return $scriptJS;
    }

    public function listOfUsersNotHavingFinishedCourse(Controller $ctrl) {
        $date = new DateTime('now');
        
        $q = $ctrl->getDoctrine()->getEntityManager()
                ->createQuery('SELECT ue, u, c FROM IMRIMLmsBundle:UserEnrolment ue JOIN ue.course c JOIN ue.user u WHERE NOT(UPPER(ue.courseStatus) LIKE \'FINISHED\') AND c IN (SELECT cc FROM IMRIMLmsBundle:Course cc WHERE cc.expirationDate <= :date)')
                ->setParameter('date',$date);
        $result = $q->getResult();
        if (!isset($result) || $result == null) {
            return "NULL: list of users who have not finished a course before its expiration date";
        }

        $temp = "";
        $i = 0;
        foreach ($result as $ue) {
            $i++;            
            $temp = $temp . "['" . $ue->getUser()->getFirstName() . " " . $ue->getUser()->getLastName() . "','" . $ue->getCourse()->getName() . "','" . $ue->getCourse()->getExpirationDate()->format('d/m/Y') . "',false]";
            if ($i < count($result)) {
                $temp = $temp . ",";
            }
        }

        $scriptJS = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
                . "<script type='text/javascript'>"
                . "google.load('visualization', '1', {packages:['table']});"
                . "google.setOnLoadCallback(drawChart);"
                . " function drawChart() { "
                . "   var data = new google.visualization.DataTable();"
                . "   data.addColumn('string', 'Utilisateurs');"
                . "   data.addColumn('string', 'Cours');"
                . "   data.addColumn('string', 'Date d\'expiration');"
                . "   data.addColumn('boolean', 'Terminé');"
                . "   data.addRows(["
                . $temp
                . "   ]);"
                . "   var options = {"
                . "     width: 400, height: 240,"
                . "     title: 'Liste des utilisateurs',"
                . "     showRowNumber: true"
                . "   };"
                . "   var chart = new google.visualization.Table(document.getElementById('tablechart_div'));"
                . "   chart.draw(data, options);"
                . " }"
                . "</script>"
                . "<div id='tablechart_div'>"
                . "</div>";

        return $scriptJS;
    }

}
