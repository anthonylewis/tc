<?php

include_once( ROOT_DIR . '/models/user.php' );
include_once( 'controller.php' );

class UserAdmin extends Controller {
  var $edit_user;

  function UserAdmin() {
    $this->Controller();
    $this->edit_user = new User();
  }

  function index() {
    $this->edit_user->get_list();
    $this->title = 'User Admin';
  }

  function add() {
    $this->title = 'Add User';
  }

  function edit() {
    $this->edit_user->get( $_REQUEST['id'] );
    $this->title = 'Edit User: ' . $this->edit_user->user_name;
  }

  function save() {
    // TODO: this could be a method of User called get_request()
    $this->edit_user->id = $_REQUEST['id'];
    $this->edit_user->user_name = $_REQUEST['user_name'];
    $this->edit_user->full_name = $_REQUEST['full_name'];
    $this->edit_user->email = $_REQUEST['email'];
    $this->edit_user->url = $_REQUEST['url'];
    if ( $this->edit_user->id == 0 ) {
      $password1 = $_REQUEST['password'];
      $password2 = $_REQUEST['confirm'];
      if ( $password1 != '' && $password1 == $password2 ) {
	$this->edit_user->password = crypt( $password1 );
      }
    }
    else {
      $this->edit_user->password = '';
    }
    $this->edit_user->save();
    $this->redirect_to( array( 'controller' => 'useradmin' ) );
  }

  function delete() {
    if ( $_REQUEST['id'] != 1 ) {
      $this->edit_user->get( $_REQUEST['id'] );
      $this->edit_user->delete();
    }
    $this->redirect_to( array( 'controller' => 'useradmin' ) );
  }

  function password() {
    $this->edit_user->get( $_REQUEST['id'] );
    $this->title = 'Password for User: ' . $this->edit_user->user_name;
  }

  function change_password() {
    $this->edit_user->get( $_REQUEST['id'] );
    $password1 = $_REQUEST['password'];
    $password2 = $_REQUEST['confirm'];
    if ( $password1 != '' && $password1 == $password2 ) {
      $this->edit_user->password = crypt( $password1 );
      $this->edit_user->save();
    }
    $this->redirect_to( array( 'controller' => 'useradmin' ) );
  }

  function done() {
    $this->redirect_to();
  }
}

?>
