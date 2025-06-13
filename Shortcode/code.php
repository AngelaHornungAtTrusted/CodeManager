<?php

use Util\DbTableManager;

$plugin_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/';
require_once $plugin_path . 'wp-load.php';

global $wpdb;
$dbTableManager = new DbTableManager($wpdb);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$code = $_GET["code"];
	try {
		$response = array(
			'data' => array(
				'success'  => 'success',
				'message'  => 'Checked Codes!',
				'content' => $dbTableManager->getCode($_GET["code"]) // Renamed the inner 'data' key
			)
		);
	} catch ( \Exception $e ) {
		//todo exceptions not caught or returned, fix later
		$response = array(
			'data' => array(
				'status'  => 'error',
				'message' => $e->getMessage()
			)
		);
	}

	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
        $dbTableManager->updateCodeExpiration(sanitize_text_field($_POST["cm-code-id"]), );

        $response = array(
            'data' => array(
                'success'  => 'success',
                'message'  => 'Code Updated!',
            )
        );
    }catch(\Exception $e){
        //todo exceptions not caught or returned, fix later
        $response = array(
            'data' => array(
                'success'  => 'error',
                'message'  => $e->getMessage(),
            )
        );
    }
} else {
    die ("Invalid Request");
}

?>