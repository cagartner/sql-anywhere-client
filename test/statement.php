<?php 
		
require '../vendor/autoload.php';

use Cagartner\SQLAnywhereClient AS PDO;

try {

	// $dns = "uid=digitalto;pwd=123sql;ENG=CDTESTE;commlinks=tcpip{host=192.168.1.159;port=9505}";
	$dns = "uid=eplacas;pwd=blu321data;ENG=eplacas;commlinks=tcpip{host=Carlos.bludata.local;port=2638}";
	$con = new PDO( $dns );

	$sql = "select * from usuario";

	// $sql = 'update "usuario" set "updated_at" = :data, "remember_token" = :token where "pkUsuario" = :pk';

	// $pk    = 2;
	// $token = "K8jIUBDWlXCFz7WzxtXMnHpcbdcEddK1zlwx8qTKewb4nwbpXxGaMBOtk5E3";
	// $data  = "2014-06-04 11:00:54";

	$stmnt = $con->prepare( $sql );
	// $stmnt->bindParam( ':pk', $pk );
	// $stmnt->bindParam( ':token', $token );
	// $stmnt->bindParam( ':data', $data );
	$stmnt->execute();

	echo "<pre>";
	print_r($stmnt->fetchAll());
	echo "</pre>";
	exit;

} catch (Exception $e) {
	echo $e->getMessage();
}