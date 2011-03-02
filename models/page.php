<?php

include_once( SOFA_DIR . '/model.php' );
include_once( 'tag.php' );

class Page extends Model {
  var $id, $title, $content, $updated_at, $user_id;

  function Page( $id=0, $title='', $content='', $updated_at='', $user_id=0 ) {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->updated_at = $updated_at;
    $this->user_id = $user_id;

    $this->Model( TABLE_PREFIX . 'pages' );
  }

  function fill( $row ) {
    $this->id = $this->db->unescape_string( $row['id'] );
    $this->title = $this->db->unescape_string( $row['title'] );
    $this->content = $this->db->unescape_string( $row['content'] );
    $this->updated_at = $this->db->unescape_string( $row['updated_at'] );
    $this->user_id = $this->db->unescape_string( $row['user_id'] );
  }

  function save() {
    $numrows = 0;

    if ( $this->id == 0 ) {
      // insert
      $sql = "insert into " . $this->table . " ( title, content, updated_at, user_id ) values " . 
	     "( '$this->title', '$this->content', NOW(), $this->user_id )";
      $numrows = $this->db->insert( $sql );
      if ( $numrows > 0 ) {
	$this->id = $this->db->insert_id();
      }
    }
    else {
      // update
      $sql = "update " . $this->table . " set title = '$this->title', content = '$this->content', " .
	     "updated_at = NOW(), user_id=$this->user_id where id = $this->id";
      $numrows = $this->db->update( $sql );
    }
    return $numrows;
  }

  function set_tags( $str ) {
    $oldtags = $this->get_tags();
    $join_table = TABLE_PREFIX . 'pages_tags';

    // clear current tags
    $sql = "delete from " . $join_table . " where page_id=$this->id";
    $this->db->delete( $sql );

    if ( !empty( $str ) ) {
      $list = explode( ' ', $str );

      // add new tags
      foreach( $list as $t ) {
	$tag = new Tag();
	if ( !$tag->get_where( "tag='$t'" ) ) {
	  // create tag
	  $tag->tag = $t;
	  $tag->save();
	}
	// now tag is in database and we have id
	$sql = "insert into " . $join_table . " ( page_id, tag_id ) values ( $this->id, $tag->id )";
	$this->db->insert( $sql );
      }
    }

    // take care of orphaned tags
    foreach( $oldtags as $t ) {
      $tag = new Tag();
      $tag->get_where( "tag='$t'" );
      $sql = "select * from " . $join_table . " where tag_id=" . $tag->id;
      $res = $this->db->select( $sql );
      if ( $res->num_rows() == 0 ) {
	$tag->delete();
      }
    }
  }

  function get_tags() {
    $list = array();
    $join_table = TABLE_PREFIX . 'pages_tags';
    $tag_table = TABLE_PREFIX . 'tags';
    $sql = "select tag from " . $join_table . ", " . $tag_table . 
      " where " . $tag_table . ".id=tag_id and page_id=$this->id";
    $res = $this->db->query( $sql );
    while ( $row = $res->fetch_assoc() ) {
      $list[] = $row['tag'];
    }
    return $list;
  }

}

?>
