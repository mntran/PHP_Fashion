<?php
/**
 * Request broker GuitarShop Application.
 *
 * @author jam
 * @version 180428
 */
// Non-web tree base directory for this application.
define('NON_WEB_BASE_DIR', '/Users/michelletran/cis4270/');
define('APP_NON_WEB_BASE_DIR', NON_WEB_BASE_DIR . 'ProjectFramework/');
include_once(APP_NON_WEB_BASE_DIR . 'includes/cis4270Includes.php');

//SANITIZATION
// Sanitze the routing input from links and forms - set default values if
// missing.
$post = true;
if (hRequestMethod() === 'GET') {
    $vm = null;
    $actionGET = hGET('action');
    $ctlrGET = hGET('ctlr');
    $ctlr = isset($ctlrGET) ? $ctlrGET : '';
    $actionSet = isset($actionGET) ? $actionGET : '';

    // Whitelist actions from a GET request.
    $action = hasInclusionIn($actionSet, $whiltelistGET) ? $actionSet : '';
    if (!$action !== '') {
        $post = false;
    }
} else {

    // POST request processing
    $vm = MessageVM::getErrorInstance();

    $actionPost = hPOST('action');
    $ctlrPost = hPOST('ctlr');
    $action = isset($actionPost) ? $actionPost : '';
    $ctlr = isset($ctlrPost) ? $ctlrPost : 'index';

    if ($vm->errorMsg !== '') {
        $action = '';
        $ctlr = '';
    }
}

switch ($ctlr) {
    case 'admin':
        $controller = new AdminController();
        if ($action === 'login') {
            if ($post) {
                $action = 'loginPOST';
            } else {
                $action = 'loginGET';
            }
        }
        if ($action === 'addProduct') {
            if ($post) {
                $action = 'addEditProduct';
            } else {
                $action = 'showAddProduct';
            }
        }
        break;
    case 'user' :
        $controller = new UserController();
        if ($action === 'login'){
            if ($post) {
                $action = 'loginPOST';
            }else {
                $action = 'logRegGET';
            }
        }
        if ($action === 'register'){
            if($post) {
                $action = 'registerPOST';
            }else {
                $action = 'logRegGET';
            }
        }
        break;
    case 'post':
        $controller = new PostController();
        if ($action === 'newPost') {
            if ($post) {
                $action = 'newPostPOST';
            }else {
                $action = 'newPostGET';
            }
        }
        break;
    case 'home':
        $controller = new HomeController();
        break;
    case 'cart':
        $controller = new CartController();
        break;
    default:
        $controller = new DefaultController();
}
$controller->run($action, $vm);
