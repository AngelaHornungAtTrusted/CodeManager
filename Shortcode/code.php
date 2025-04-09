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
} else {
	// Handle cases where the PHP file is accessed directly without form submission
	echo "This page should be accessed through a form submission.";
}

?>