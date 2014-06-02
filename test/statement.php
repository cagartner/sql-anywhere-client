<?php 
		
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	// $dns = "uid=digitalto;pwd=123sql;ENG=CDTESTE;commlinks=tcpip{host=192.168.1.159;port=9505}";
	$dns = "uid=teste-conexao;pwd=teste;ENG=teste-conexao;commlinks=tcpip{host=Carlos.bludata.local;port=2638}";
	$con = new PDO( $dns );

	$sql = "SELECT * FROM usuario WHERE email LIKE :email ";

	$email = 'contato%';

	$stmnt = $con->prepare( $sql );
	$stmnt->bindParam( 'email', $email );
	$stmnt->execute();

} catch (Exception $e) {
	echo $e->getMessage();
}