<?php

namespace Util;

class DbTableManager {

	public $dpdb;

	public function __construct($wpdb) {
		$this->dpdb = $wpdb;
	}

	public function initTables(): void {
		$charset_collate = $this->dpdb->get_charset_collate();

		$codeTable = "CREATE TABLE " . CM_TABLE_CODES . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        code varchar(255) DEFAULT '' NOT NULL,
        message varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        winner tinyint(1) DEFAULT '0',
        expiration datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;" . "CREATE TABLE " . CM_TABLE_SETTINGS . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) DEFAULT '' NOT NULL,
        active tinyint(1) DEFAULT '0',
        description varchar(255) DEFAULT '' NOT NULL,
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

		//require once is for dbDelta
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($codeTable);
	}

    public function initSettings(): void {
        //codes table example code
        $this->dpdb->insert('cm_codes', array(
            'code' => CM_DEFAULT_EXAMPLE_CODE,
            'message' => CM_DEFAULT_MESSAGE,
            'active' => CM_CODE_ACTIVE,
            'winner' => CM_CODE_ACTIVE,
            'expiration' => gmdate('Y-m-d H:i:s', mktime(0,0,0,date("m", strtotime("+1 month")),1,date("Y"))),
            'create_date' => gmdate('Y-m-d H:i:s'),
            'update_date' => gmdate('Y-m-d H:i:s'),
        ));

        //settings table
        foreach (CM_SETTINGS_DATA as $setting) {
            $this->dpdb->insert('cm_settings', array(
                'name' => $setting['name'],
                'active' => CM_CODE_INACTIVE,
                'description' => $setting['description'],
                'create_date' => gmdate('Y-m-d H:i:s'),
                'update_date' => gmdate('Y-m-d H:i:s')
            ));
        }
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
}

?>