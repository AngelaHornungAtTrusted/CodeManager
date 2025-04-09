<?php

use Util\DbTableManager;

$plugin_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/';
require_once $plugin_path . 'wp-load.php';

global $wpdb;
$dbTableManager = new DbTableManager($wpdb);

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	if ($_POST["cm-post-type"] == "1") {
		/* change code status */
		try{
			$dbTableManager->updateCodeStatus($_POST["cm-code-id"], $_POST["cm-code-status"]);

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
	} elseif ($_POST["cm-post-type"] == "2") {
		/* change code */
		try{
			$dbTableManager->updateCode($_POST["cm-code-id"], $_POST["cm-code-title"]);

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
	} elseif ($_POST["cm-post-type"] == "3") {
		try{
			$dbTableManager->updateWinnerStatus($_POST["cm-code-id"], $_POST["cm-code-winner"]);

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
	} elseif ($_POST["cm-post-type"] == "4") {
		try{
			$dbTableManager->updateCodeMessage($_POST["cm-code-id"], $_POST["cm-code-message"]);

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
		/* insert new code */
		try {
			$catName = $_POST['cm-code'];
			$dbTableManager->insertCode($_POST['cm-code']);

			$response = array(
				'data' => array(
					'success'  => 'success',
					'message'  => 'Code Added!',
				)
			);
		} catch ( \Exception $e ) {
			//todo exceptions not caught or returned, fix later
			$response = array(
				'data' => array(
					'success'  => 'error',
					'message'  => $e->getMessage(),
				)
			);
		}
	}

	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
} elseif ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
	//todo implement multiple get method types
	try {
		$response = array(
			'data' => array(
				'success'  => 'success',
				'message'  => 'Codes Acquired!',
				'content' => $dbTableManager->getCode() // Renamed the inner 'data' key
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

	// To send the JSON response:
	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
} else {
	// Handle cases where the PHP file is accessed directly without form submission
	echo "This page should be accessed through a form submission.";

}

?>