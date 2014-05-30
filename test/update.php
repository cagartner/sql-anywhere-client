<?php 
		
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	// $dns = "uid=digitalto;pwd=123sql;ENG=CDTESTE;commlinks=tcpip{host=192.168.1.159;port=9505}";
	$dns = "uid=teste-conexao;pwd=teste;ENG=teste-conexao;commlinks=tcpip{host=Carlos.bludata.local;port=2638}";
	$con = new PDO( $dns );

} catch (Exception $e) {
	echo $e->getMessage();
}

$sql = "SELECT id FROM usuario WHERE id = 1";

// SQL
$sql = "INSERT INTO usuario (name, email, password, status) VALUES ('Carlos', 'contato@carlosgartner.com.br', '".md5('teste')."', 1) ";
$result = $con->exec($sql);

$con->commit();
	
if ($result)
	echo "Novo registro inserido: " . $con->lastInsertId();
else 
	$con->rollback();