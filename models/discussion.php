<?php

include_once( SOFA_DIR . '/model.php' );

class Discussion extends Model {
  var $page_id, $author, $email, $url, $comment;

  function Discussion( $id=0, $page_id=0, $author='', $email='', $url='', $comment='' ) {
    $this->id = $id;
    $this->page_id = $page_id;
    $this->author = $author;
    $this->email = $email;
    $this->url = $url;
    $this->comment = $comment;

    $this->Model( TABLE_PREFIX . 'discussion' );
  }

  function fill( $row ) {
    $this->id = $this->db->unescape_string( $row['id'] );
    $this->page_id = $this->db->unescape_string( $row['page_id'] );
    $this->author = $this->db->unescape_string( $row['author'] );
    $this->email = $this->db->unescape_string( $row['email'] );
    $this->url = $this->db->unescape_string( $row['url'] );
    $this->comment = $this->db->unescape_string( $row['comment'] );
  }

  function save() {
    $numrows = 0;

    if ( $this->id == 0 ) {
      // insert
      $sql = "insert into " . $this->table . " ( page_id, author, email, url, comment, updated_at ) " .
	" values ( $this->page_id, '$this->author', '$this->email', '$this->url', '$this->comment', NOW() )";
      $numrows = $this->db->insert( $sql );
      if ( $numrows > 0 ) {
	$this->id = $this->db->insert_id();
      }
    }
    else {
      // update
      $sql = "update " . $this->table . " set page_id=$this->page_id, author='$this->author', email='$this->email', " .
	"url='$this->url', comment='$this->comment', updated_at=NOW() where id=$this->id";
      $numrows = $this->db->update( $sql );
    }
    return $numrows;
  }

}

?>
