<?php
/*
 * This work is hereby released into the Public Domain.
 * To view a copy of the public domain dedication,
 * visit http://creativecommons.org/licenses/publicdomain/ or send a letter to
 * Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
 *
 */

require_once "../Pie.class.php";


$graph = new Graph(400, 250);

$graph->title->set("Camembert (exemple 1)");

$graph->setAntiAliasing(TRUE);

$values = array(20, 5, 13, 18, 10, 6, 11);

$plot = new Pie($values, Pie::EARTH);
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

?>