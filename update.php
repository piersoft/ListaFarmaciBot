<?php
$indirizzo ="https://docs.google.com/spreadsheets/d/1b4-93cKOzJvwHzkeMcwAgNC5pVa8QlCrHu6zYy9cvzk/pub?gid=473365486&single=true&output=csv";
$inizio=1;
$homepage ="";
//  echo $url;
$csv1 = array_map('str_getcsv', file($indirizzo));
	$url ="http://www.agenziafarmaco.gov.it/".$csv1[0][0];

  $homepage1 = file_get_contents($url);
	$homepage1=str_replace(",",".",$homepage1);
	$homepage1=str_replace("/","-",$homepage1);
	$homepage1=str_replace("\"","-",$homepage1);
	$homepage1=str_replace("*","-",$homepage1);
  $homepage1=str_replace(";",",",$homepage1);

  $file = '/usr/www/piersoft/listafarmacibot/db/farmaci_a.csv';

// Write the contents back to the file
  file_put_contents($file, $homepage1);
	echo "finito farmaci A";

//$csv11 = array_map('str_getcsv', file($indirizzo));
	$url1 ="http://www.agenziafarmaco.gov.it/".$csv1[0][1];

  $homepage11 = file_get_contents($url1);
	$homepage11=str_replace(",",".",$homepage11);
	$homepage11=str_replace("/","-",$homepage11);
	$homepage11=str_replace("\"","-",$homepage11);
	$homepage11=str_replace("*","-",$homepage11);
  $homepage11=str_replace(";",",",$homepage11);
//  echo $homepage11;
  $file1 = '/usr/www/piersoft/listafarmacibot/db/farmaci_h.csv';
//print_r($homepage11);
// Write the contents back to the file
  file_put_contents($file1, $homepage11);
	echo "finito farmaci H";

//	$csv111 = array_map('str_getcsv', file($indirizzo));
	$url11 ="http://www.agenziafarmaco.gov.it/".$csv1[0][2];

  $homepage111 = file_get_contents($url11);
	$homepage111=str_replace(",",".",$homepage111);
	$homepage111=str_replace("/","-",$homepage111);
	$homepage111=str_replace("\"","-",$homepage111);
	$homepage111=str_replace("*","-",$homepage111);
  $homepage111=str_replace(";",",",$homepage111);
//  echo $homepage111;
  $file11 = '/usr/www/piersoft/listafarmacibot/db/farmaci_c.csv';

// Write the contents back to the file
  file_put_contents($file11, $homepage111);
	echo "finito farmaci C";
?>
