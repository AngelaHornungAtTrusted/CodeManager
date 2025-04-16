<?php

//namespace Admin;
namespace Old;

use Util\DbTableManager;

require_once( __DIR__ . '/../Util/DbTableManager.php' );

class CodeManager {
	public $dbTableManager;

	public function __construct() {
		global $wpdb;
		$this->dbTableManager = new DbTableManager( $wpdb );
	}

	public function post() {
		if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
			if ( $_POST["cm-post-type"] == "1" ) {
				/* change code status */
				try {
					$this->dbTableManager->updateCodeStatus( $_POST["cm-code-id"], $_POST["cm-code-status"] );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Updated!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			} elseif ( $_POST["cm-post-type"] == "2" ) {
				/* change code */
				try {
					$this->dbTableManager->updateCode( $_POST["cm-code-id"], $_POST["cm-code-title"] );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Updated!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			} elseif ( $_POST["cm-post-type"] == "3" ) {
				try {
					$this->dbTableManager->updateWinnerStatus( $_POST["cm-code-id"], $_POST["cm-code-winner"] );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Updated!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			} elseif ( $_POST["cm-post-type"] == "4" ) {
				try {
					$this->dbTableManager->updateCodeMessage( $_POST["cm-code-id"], $_POST["cm-code-message"] );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Updated!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			} elseif ( $_POST["cm-post-type"] == "5" ) {
				try {
					$this->dbTableManager->updateCodeExpiration( $_POST["cm-code-id"], $_POST["cm-code-expiration"] );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Updated!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			} else {
				/* insert new code */
				try {
					$this->dbTableManager->insertCode( $_POST['cm-code'], ( $_POST['cm-code-message'] ?? null ), ( $_POST['cm-code-active'] ?? null ), ( $_POST['cm-code-winner'] ?? null ), ( $_POST['cm-code-exp'] ?? null ) );

					$response = array(
						'data' => array(
							'success' => 'success',
							'message' => 'Code Added!',
						)
					);
				} catch ( \Exception $e ) {
					//todo exceptions not caught or returned, fix later
					$response = array(
						'data' => array(
							'success' => 'error',
							'message' => $e->getMessage(),
						)
					);
				}
			}
		} else {
			$response = array(
				'data' => array(
					'success' => 'error',
					'message' => 'Wrong Call Type',
				)
			);
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit();
	}

	public function get() {
		if ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
			//todo implement multiple get method types
			try {
				$response = array(
					'data' => array(
						'success' => 'success',
						'message' => 'Codes Acquired!',
						'content' => $this->dbTableManager->getCode()
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
		} else {
			$response = array(
				'data' => array(
					'success' => 'error',
					'message' => 'Wrong Call Type',
				)
			);
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit();
	}

}