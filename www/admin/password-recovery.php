<?php
/**
 * ... for Openads
 *
 * @since Openads v2.3.22-alpha - Apr 11, 2006
 * @copyright 2003-2007 Openads Ltd
 * @version $Id$
 */

// Define a constant to avoid displaying the login screen
define ('MAX_SKIP_LOGIN', 1);

// Require the initialisation file
require_once '../../init.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once MAX_PATH . '/www/admin/lib-permissions.inc.php';
require_once MAX_PATH . '/lib/max/Admin/PasswordRecovery.php';

$recovery_page = new MAX_Admin_PasswordRecovery();

if ($recovery_page->checkAccess()) {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $recovery_page->handlePost($_POST);
    } else {
        $recovery_page->handleGet($_GET);
    }
} else {
     MAX_Admin_Redirect::redirect('index.php');
}

?>
