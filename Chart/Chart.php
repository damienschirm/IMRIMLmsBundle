<?php

include('Chart' . DIRECTORY_SEPARATOR . "IChart.php");

/**
 * Description of Chart
 *
 * @author Damien
 */
class Chart implements IChart{

    function pieChartJS() {
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
         
         return $scriptJS;
    }

    public function numberOfUsers() {
        $scriptJS = "<div id='geochart_div'>"
    . "<script type='text/javascript' src='https://www.google.com/jsapi'></script>"
    . "<script type='text/javascript'>"
    . "google.load('visualization', '1', {packages:['geochart']});"
    . "google.setOnLoadCallback(drawChart);"
    . " function drawChart() { "
    . "   var data = new google.visualization.DataTable();"
    . "   data.addColumn('string', 'Pays');"
    . "   data.addColumn('number', 'Utilisateurs inscrits');"
    . "   data.addRows(["
    . "     ['France',    1],"
    . "     ['Allemagne',      1]"
    . "   ]);"

    . "   var options = {"
    . "     width: 700, height: 400,"
    . "     title: 'Géolocalisation des personnes inscrites'"
    . "   };"
                
    . "   var chart = new google.visualization.GeoChart(document.getElementById('geochart_div'));"
    . "   chart.draw(data, options);"
    . " }"
    . "</script>"
    . "</div>";
        
         return $scriptJS;
    }
    
    
}

?>
