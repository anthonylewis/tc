<?php

include_once( SOFA_DIR . '/model.php' );

class Tag extends Model {
  var $tag;

  function Tag( $id=0, $tag='' ) {
    $this->id = $id;
    $this->tag = $tag;

    $this->Model( TABLE_PREFIX . 'tags' );
  }

  function fill( $row ) {
    $this->id = $this->db->unescape_string( $row['id'] );
    $this->tag = $this->db->unescape_string( $row['tag'] );
  }

  function save() {
    $numrows = 0;

    if ( $this->id == 0 ) {
      // insert
      $sql = "insert into " . $this->table . " ( tag ) values ( '$this->tag' )";
      $numrows = $this->db->insert( $sql );
      if ( $numrows > 0 ) {
	$this->id = $this->db->insert_id();
      }
    }
    else {
      // update
      $sql = "update " . $this->table . " set tag = '$this->tag' where id = $this->id";
      $numrows = $this->db->update( $sql );
    }
    return $numrows;
  }

  function get_pages() {
    $list = array();
    $join_table = TABLE_PREFIX . 'pages_tags';
    $page_table = TABLE_PREFIX . 'pages';
    $sql = "select title from " . $join_table . ", " . $page_table . 
      " where " . $page_table . ".id=page_id and tag_id=$this->id";
    $res = $this->db->query( $sql );
    while ( $row = $res->fetch_assoc() ) {
      $list[] = $row['title'];
    }
    return $list;
  }

}

?>
