<?php 
	
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	// $dns = "uid=digitalto;pwd=123sql;ENG=CDTESTE;commlinks=tcpip{host=192.168.1.159;port=9505}";
	$dns = "uid=teste-conexao;pwd=teste;ENG=teste-conexao;commlinks=tcpip{host=Carlos.bludata.local;port=2638}";
	$con = new PDO( $dns );

	$sql = "INSERT INTO usuario (name, email, status) VALUES ('Carlos', 'contato@carlosgartner.com.br', 1) ";

	$result = $con->exec($sql);

	// foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $resultado) {
	// 	 print_r($resultado)
	// }

	var_dump($result);
	exit;

} catch (Exception $e) {
	echo $e->getMessage();
}

// $sql = 'SELECT * FROM Usuarios WHERE Id = ?';

// $result = $con->exec($sql);

// echo "<pre>";
// print_r($result->fetchAll());
// echo "</pre>";
// exit;	
// 
// $Id = null;



// $stmnt = $con->prepare($sql);

// @$stmnt->bindParam( 'i', $Id );
// // sasql_stmt_bind_param(  )

// $Id = 1;

// $stmnt->execute();

// $data = $stmnt->fetch();

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// exit;

// $stmnt->execute();

// echo 'Total de registros: ' . $data->count();

// echo "<pre>";
// print_r($data->fetch());
// echo "</pre>";
// exit;