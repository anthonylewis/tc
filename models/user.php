<?php

include_once( SOFA_DIR . '/model.php' );

class User extends Model {
  var $id, $user_name, $full_name, $password, $email, $url;

  function User( $id=0, $user_name='', $full_name='', $password='', $email='', $url='' ) {
    $this->id = $id;
    $this->user_name = $user_name;
    $this->full_name = $full_name;
    $this->password = $password;
    $this->email = $email;
    $this->url = $url;

    $this->Model( TABLE_PREFIX . 'users' );
  }

  function fill( $row ) {
    $this->id = $this->db->unescape_string( $row['id'] );
    $this->user_name = $this->db->unescape_string( $row['user_name'] );
    $this->full_name = $this->db->unescape_string( $row['full_name'] );
    $this->password = $this->db->unescape_string( $row['password'] );
    $this->email = $this->db->unescape_string( $row['email'] );
    $this->url = $this->db->unescape_string( $row['url'] );
  }

  function save() {
    $numrows = 0;

    if ( $this->id == 0 ) {
      // insert
      $sql = "insert into " . $this->table . " ( user_name, full_name, password, email, url ) values " . 
	     "( '$this->user_name', '$this->full_name', '$this->password', '$this->email', '$this->url' )";
      $numrows = $this->db->insert( $sql );
      if ( $numrows > 0 ) {
	$this->id = $this->db->insert_id();
      }
    }
    else {
      // update
      if ( $this->password != '' ) {
	$sql = "update " . $this->table . " set user_name = '$this->user_name', full_name = '$this->full_name', " .
	       "password = '$this->password', email='$this->email', url='$this->url' where id = $this->id";
      }
      else {
	$sql = "update " . $this->table . " set user_name = '$this->user_name', full_name = '$this->full_name', " .
               "email = '$this->email', url='$this->url' where id = $this->id";
      }
      $numrows = $this->db->update( $sql );
    }
    return $numrows;
  }
}

?>
