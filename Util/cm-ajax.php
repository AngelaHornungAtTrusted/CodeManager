<?php

//for admin
function wp_ajax_cm_new_code()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if (!isset($_POST["code"]["cm-code"])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Required Field';

        wp_send_json($response);
    }

    $code = sanitize_text_field($_POST["code"]["cm-code"]);
    $message = (isset($_POST["code"]["message"])) ? sanitize_text_field($_POST["code"]["message"]) : CM_DEFAULT_MESSAGE;
    $active = (isset($_POST["code"]["active"])) ? intval($_POST["code"]["active"]) : CM_CODE_INACTIVE;
    $winner = (isset($_POST["code"]["winner"])) ? intval($_POST["code"]["winner"]) : 0;
    $exp = (isset($_POST["code"]["exp"])) ? sanitize_text_field($_POST["code"]["exp"]) : gmdate('Y-m-d H:i:s', mktime(0, 0, 0, date("m", strtotime("+1 month")), 1, date("Y")));

    try {
        $wpdb->insert(
            CM_TABLE_CODES,
            array(
                'code' => $code,
                'message' => $message,
                'active' => $active,
                'winner' => $winner,
                'expiration' => $exp,
                'create_date' => gmdate('Y-m-d H:i:s'),
                'update_date' => gmdate('Y-m-d H:i:s')
            )
        );

        $response->code = 200;
        $response->status = 'success';
        $response->message = 'Code Added!';
    } catch (Exception $e) {
        $response->code = 500;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

function wp_ajax_cm_update_code()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    /*
     * Each update requests updates the entire row, this reduces js required
     * on the front end and php required on the back end, but makes the requests larger
     */

    if (!isset($_POST["code"]["id"]) || !isset($_POST["code"]["code"]) || !isset($_POST["code"]["message"]) || !isset($_POST["code"]["active"]) || !isset($_POST["code"]["winner"]) || !isset($_POST["code"]["expiration"])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Required Fields';

        wp_send_json($response);
    }

    //sanitize input for security
    $code = sanitize_text_field($_POST["code"]["code"]);
    $message = sanitize_text_field($_POST["code"]["message"]);
    $active = (intval($_POST["code"]["active"] === "true") ? CM_CODE_ACTIVE : CM_CODE_INACTIVE);
    $winner = (intval($_POST["code"]["winner"] === "true") ? CM_CODE_ACTIVE : CM_CODE_INACTIVE);
    $exp = sanitize_text_field($_POST["code"]["expiration"]);

    try {
        $wpdb->update(
            CM_TABLE_CODES,
            array(
                'code' => $code,
                'message' => $message,
                'active' => $active,
                'winner' => $winner,
                'expiration' => $exp,
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            array('id' => $_POST["code"]["id"])
        );

        $response->code = 200;
        $response->status = 'success';
        $response->message = 'Code Updated Successfully';
    } catch (Exception $e) {
        $response->code = 500;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

function wp_ajax_cm_get_codes()
{
    global $wpdb;
    $response = new stdClass();
    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';
        wp_send_json($response);
    }

    try {
        if (!isset($_POST["code"]["cm-code"])) {
            //grab all
            $data = $wpdb->get_results("SELECT * FROM " . CM_TABLE_CODES);
        } else {
            //grab specific
            $code = sanitize_text_field($_POST["code"]["cm-code"]);
            $data = $wpdb->get_results("SELECT * FROM " . CM_TABLE_CODES . " WHERE code = '" . $code . "'");
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = (count($data) > 0) ? 'Codes Acquired!' : 'No Codes Found!';
        $response->data = $data;
    } catch (Exception $e) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

function wp_ajax_cm_delete_code()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    //verify we have all the data we need
    if (!isset($_POST["code"])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Required Fields';

        wp_send_json($response);
    }

    //sanitize input for security
    $id = intval($_POST["code"]);

    try {
        $wpdb->delete(
            CM_TABLE_CODES,
            array('id' => $id),
        );

        $response->code = 200;
        $response->status = 'success';
        $response->message = 'Code Deleted Successfully';
    } catch (Exception $e) {
        $response->code = 500;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

function wp_ajax_cm_post_settings()
{
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    if (!isset($_POST["setting"])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Required Fields';

        wp_send_json($response);
    }

    //sanitize input
    $status = ($_POST["checked"] === "true") ? CM_CODE_ACTIVE : CM_CODE_INACTIVE;

    //update auto delete
    try {
        $wpdb->update(
            CM_TABLE_SETTINGS,
            array(
                'active' => $status,
                'update_date' => gmdate('Y-m-d H:i:s')
            ),
            //id changes depending on setting update requested
            array('id' => (($_POST["setting"] === "cm-autoinactive") ? 1 : 2))
        );

        $response->code = 200;
        $response->status = 'success';
        $response->message = 'Settings Updated Successfully';
    } catch (Exception $e) {
        $response->code = 500;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

//todo implement setting actions into code gets & posts, management of setting status is complete
function wp_ajax_cm_get_settings() {
    global $wpdb;
    $response = new stdClass();

    if (!current_user_can('administrator')) {
        $response->code = 403;
        $response->status = 'error';
        $response->message = 'Unauthorized User';

        wp_send_json($response);
    }

    try {
        $data = $wpdb->get_results("SELECT * FROM " . CM_TABLE_SETTINGS);

        $response->code = 200;
        $response->status = 'success';
        $response->message = 'Settings Fetched Successfully';
        $response->data = $data;
    } catch (Exception $e) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}

//for shortcode
function wp_ajax_cm_code_exists()
{
    global $wpdb;
    $response = new stdClass();

    if (!isset($_POST["code"]["code"])) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = 'Missing Required Fields';

        wp_send_json($response);
    }

    $code = sanitize_text_field($_POST["code"]["code"]);

    try {
        $data = $wpdb->get_results("SELECT * FROM " . CM_TABLE_CODES . " WHERE code = '" . $code . "' AND active = 1");

        //if expired override code message
        if (count($data) > 0) {
            $data[0]->message = (gmdate($data[0]->expiration) <= gmdate('Y-m-d H:i:s')) ? 'Code Expired!' : $data[0]->message;
        }

        $response->code = 200;
        $response->status = 'success';
        $response->message = (count($data) > 0) ? 'Code Exists!' : 'No Code Found!';
        $response->data = $data;
    } catch (Exception $e) {
        $response->code = 400;
        $response->status = 'error';
        $response->message = $e->getMessage();
    }

    wp_send_json($response);
}