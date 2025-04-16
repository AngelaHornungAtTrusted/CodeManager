<?php

namespace Util;

class DbTableManager {

	public $dpdb;

	public function __construct($wpdb) {
		$this->dpdb = $wpdb;
	}

	public function initTables(): void {
		$charset_collate = $this->dpdb->get_charset_collate();

		$codeTable = "CREATE TABLE cm_codes (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        code varchar(255) DEFAULT '' NOT NULL,
        message varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        winner tinyint(1) DEFAULT '0',
        expiration datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

		//require once is for dbDelta
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($codeTable);
	}

	public function exportTables(): void {
		$tables_to_export = array(
			'cm_codes',
		);

		$sql = '';
		foreach ( $tables_to_export as $table ) {
			$create_table = $this->dpdb->get_row( "SHOW CREATE TABLE $table", ARRAY_A );
			$sql          .= "-- Table structure for table `$table`\n";

			$sql .= $create_table["Create Table"] . ";\n\n";

			// Get the table data
			$rows = $this->dpdb->get_results( "SELECT * FROM $table", ARRAY_A );
			if ( $rows ) {
				$column_names = array_keys( $rows[0] );
				$sql          .= "-- Dumping data for table `$table`\n";
				$sql          .= "INSERT INTO `$table` (`" . implode( "`,`", $column_names ) . "`) VALUES\n";
				$values       = array();
				foreach ( $rows as $row ) {
					$value_str   = "(";
					$value_parts = array();
					foreach ( $row as $value ) {
						$value_parts[] = $this->dpdb->_real_escape( $value );
					}
					$value_str .= "'" . implode( "','", $value_parts ) . "')";
					$values[]  = $value_str;
				}
				$sql .= implode( ",\n", $values ) . ";\n\n";
			}
		}

		// Set headers for download
		header( 'Content-Type: application/sql' );
		header( 'Content-Disposition: attachment; filename="docport_tables_export.sql"' );

		echo $sql;
		exit;
	}

	public function delTables(): void {
		global $wpdb;

		$wpdb->query( "DROP TABLE IF EXISTS cm_codes" );
	}

	function insertCode($code, $message = null, $active = null, $winner = null, $exp = null): void {
		$this->dpdb->insert(
			'cm_codes',
			array(
				'code'        => $code,
				'message'     => ($message !== null) ? $message : 'Loser',
				'active'      => ($active === null) ? 1 : $active,
				'winner'      => ($winner === null) ? 0 : $winner,
				'expiration'  => ($exp === null) ? gmdate('Y-m-d H:i:s', strtotime('+ 1 MONTH')) : $exp,
				'create_date' => gmdate( 'Y-m-d H:i:s' ),
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			)
		);
	}

	function getCode($code = false) {
		if (!$code) {
			//grab all
			return $this->dpdb->get_results("SELECT * FROM cm_codes");
		} else {
			//grab specific
			return $this->dpdb->get_row("SELECT * FROM cm_codes WHERE code = '$code' AND active = 1 AND expiration < NOW()");
		}
	}

	//no deleting codes, just changing active status
	function updateCodeStatus($codeId, $checked): void {
		$this->dpdb->update(
			'cm_codes',
			array(
				'active'      => ($checked === "true" ? 1 : 0),
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			),
			array('id' => $codeId)
		);
	}

	function updateWinnerStatus($codeId, $checked): void {
		$this->dpdb->update(
			'cm_codes',
			array(
				'winner'      => ($checked === "true" ? 1 : 0),
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			),
			array('id' => $codeId)
		);
	}

	public function updateCode($codeId, $code): void {
		$this->dpdb->update(
			'cm_codes',
			array(
				'code'        => $code,
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			),
			array('id' => $codeId)
		);
	}

	public function updateCodeMessage($codeId, $message): void {
		$this->dpdb->update(
			'cm_codes',
			array(
				'message'        => $message,
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			),
			array('id' => $codeId)
		);
	}

	public function updateCodeExpiration($codeId, $exp): void {
		$this->dpdb->update(
			'cm_codes',
			array(
				'expiration'        => $exp,
				'update_date' => gmdate( 'Y-m-d H:i:s' )
			),
			array('id' => $codeId)
		);
	}
}

?>