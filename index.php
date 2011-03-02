<?php
/**
 * Route requests to controllers and actions...
 */

require_once( 'config.php' );

$controller = null;

// get the controller
if ( isset( $_REQUEST['controller'] ) && $_REQUEST['controller'] == 'useradmin' ) {
  require_once( ROOT_DIR . '/controllers/useradmin.php' );
  $controller = new UserAdmin();
}
else {
  require_once( ROOT_DIR . '/controllers/wiki.php' );
  $controller = new Wiki();
}

// get the action
if ( !empty( $_REQUEST['action'] ) ) {
  $controller->action = $_REQUEST['action'];
}
else {
  $controller->action = 'index';
}

// perform the action
if ( method_exists( $controller, $controller->action ) ) {
  call_user_func( array( &$controller, $controller->action ) );
}
else {
  // oops, some tried to call a nonexisting action...
  call_user_func( array( &$controller, 'index' ) );
}

// render the view
$controller->render( $controller->action );

?>
