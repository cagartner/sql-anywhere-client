<?php 
	
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	// $dns = "uid=digitalto;pwd=123sql;ENG=CDTESTE;commlinks=tcpip{host=192.168.1.159;port=9505}";
	$dns = "uid=teste-conexao;pwd=teste;ENG=teste-conexao;commlinks=tcpip{host=Carlos.bludata.local;port=2638}";
	$con = new PDO( $dns );

	$sql = "INSERT INTO usuario (name, email, status) VALUES ('Carlos', 'contato@carlosgartner.com.br', 1) ";

	$result = $con->exec($sql);

	var_dump($result);
	exit;

} catch (Exception $e) {
	echo $e->getMessage();
}