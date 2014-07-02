<?php 

require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {
	$dns = "uid=teste;pwd=teste;ENG=teste;commlinks=tcpip{host=127.0.0.1;port=2638}";
	$con = new PDO( $dns );

} catch (Exception $e) {
	echo $e->getMessage();
}