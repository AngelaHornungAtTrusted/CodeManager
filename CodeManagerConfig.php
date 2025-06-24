<?php
/* Global General Config */
define('CM_PLUGIN_NAME', 'Code Manager');
define('CM_PLUGIN_SLAG', 'code-manager');
define('CM_PLUGIN_VERSION', '1.0.0');

/* Global File Paths */
define('CM_ROOT_DIR_NAME', 'CodeManager');
define('CM_ROOT_DIR_PATH', plugin_dir_path(__FILE__));
define('CM_ADMIN_DIR_PATH',  CM_ROOT_DIR_PATH . 'Admin');
define('CM_ASSETS_DIR_PATH',  CM_ROOT_DIR_PATH . 'Assets');
define('CM_SHORTCODE_DIR_PATH',  CM_ROOT_DIR_PATH . 'Shortcode');
define('CM_UTIL_DIR_PATH',  CM_ROOT_DIR_PATH . 'Util');

/* Global File Urls */
define('CM_ROOT_DIR_URL', plugin_dir_url(__FILE__));
define('CM_ADMIN_URL', CM_ROOT_DIR_URL . 'Admin');
define('CM_ASSETS_URL', CM_ROOT_DIR_URL . 'Assets');
define('CM_SHORTCODE_URL', CM_ROOT_DIR_URL . 'Shortcode');
define('CM_UTIL_URL', CM_ROOT_DIR_URL . 'Util');

/* Global Database Details */
global $wpdb;
define('CM_PLUGIN_PREFIX', 'cm');
define('CM_DB_PREFIX', 'cm_');

//primary tables
define('CM_TABLE_CODES', CM_DB_PREFIX . 'codes');

//data constants
define('CM_CODE_ACTIVE', 1);
define('CM_CODE_INACTIVE', 0);
define('CM_DEFAULT_MESSAGE', 'Default Message');