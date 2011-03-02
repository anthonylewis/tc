<?php

include_once( SOFA_DIR . '/session.php' );
include_once( ROOT_DIR . '/models/user.php' );

class Controller {
  var $action, $title, $user, $session;

  function Controller() {
    $this->session = new Session();
    $this->user = new User();
  }

  function build_query_str( $where = array() ) {
    $str = '';
    $keys = array_keys( $where );

    if ( count( $keys ) > 0 ) {
      $key = $keys[0];
      $str .= '?' . $key . '=' . rawurlencode( $where[$key] );
      for ( $i = 1; $i < count( $keys ); $i++ ) {
	$key = $keys[$i];
	$str .= '&' . $key . '=' . rawurlencode( $where[$key] );
      }
    }
    return $str;
  }

  function redirect_to( $where = array() ) {
    $loc = 'Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $loc .= $this->build_query_str( $where );

    header( $loc );
    exit();
  }

  function link_to( $str, $where = array() ) {
    $link  = '<a href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $link .= $this->build_query_str( $where );
    $link .= '">' . $str . '</a>';

    return $link;
  }

  function render( $view = 'index', $layout = 'layout' ) {
    if ( empty( $this->title ) ) {
      $this->title = ucwords( $view );
    }
    // include the view first so variables
    // will be available in the layout...

    $controller = strtolower( get_class( $this ) );

    if ( !file_exists( ROOT_DIR . "/views/$controller/$view.php" ) ) {
      $view = 'index';
    }

    ob_start();

    include( ROOT_DIR . "/views/$controller/$view.php" );

    $page_content = ob_get_contents();

    ob_end_clean();

    include( ROOT_DIR . "/views/$controller/$layout.php" );
  }

  function do_login() {
    if ( !empty( $_POST ) ) {
      //      $this->user = new User();
      $username = $_POST['user_name'];
      $password = $_POST['password'];
      if ( $this->user->get_where( "user_name='$username'" ) ) {
	if ( crypt( $password, $this->user->password ) == $this->user->password ) {
	  $this->session->set( 'username', $username );
	  $this->session->set( 'password', $password );
	  return 1;
	  //	  $this->redirect_to( array( 'page' => $_REQUEST['page'] ) );
	}
      }
    }
    return 0;
  }

  function do_logout() {
    $this->session->del( 'username' );
    $this->session->del( 'password' );
    //    $this->redirect_to( array( 'page' => $_REQUEST['page'] ) );
  }

  // TODO: Should this really check the DB every time?
  function check_login() {
    $logged_in = 0;

    if ( !$this->session->get( 'username' ) or !$this->session->get( 'password' ) ) {
      $this->session->del( 'username' );
      $this->session->del( 'password' );
    }
    else {
      $username = $this->session->get( 'username' );
      $password = $this->session->get( 'password' );

      //      $this->user = new User();
      if ( $this->user->get_where( "user_name='$username'" ) ) {
	if ( crypt( $password, $this->user->password ) == $this->user->password ) {
	  $logged_in = 1;
	}
	else {
	  $this->session->del( 'username' );
	  $this->session->del( 'password' );
	}
      }
    }
    return $logged_in;
  }

}

?>
