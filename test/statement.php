<?php 
		
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {
	$dns = "uid=teste;pwd=teste;ENG=teste;commlinks=tcpip{host=127.0.0.1;port=2638}";

	$con = new PDO( $dns );

	$sql = "SELECT * FROM Usuario WHERE Id = ?";

	// $sql = 'update "usuario" set "updated_at" = :data, "remember_token" = :token where "pkUsuario" = :pk';

	// $pk    = 2;
	// $token = "K8jIUBDWlXCFz7WzxtXMnHpcbdcEddK1zlwx8qTKewb4nwbpXxGaMBOtk5E3";
	// $data  = "2014-06-04 11:00:54";

	$stmnt = $con->prepare( $sql );
	// $stmnt->bindParam( ':pk', $pk );
	// $stmnt->bindParam( ':token', $token );
	// $stmnt->bindParam( ':data', $data );
	$stmnt->execute(array('2'));

	echo "<pre>";
	print_r($stmnt->fetchAll());
	echo "</pre>";
	exit;

} catch (Exception $e) {
	echo $e->getMessage();
}