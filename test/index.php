<?php 
	
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	$dns = "uid=teste;pwd=teste;ENG=teste;commlinks=tcpip{host=127.0.0.1;port=2638}";
	$con = new PDO( $dns );

	$sql = "INSERT INTO usuario (name, email, status) VALUES ('Carlos', 'contato@carlosgartner.com.br', 1) ";

	$result = $con->exec($sql);

	var_dump($result);
	exit;

} catch (Exception $e) {
	echo $e->getMessage();
}