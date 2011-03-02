<?php

include_once( ROOT_DIR . '/models/discussion.php' );
include_once( ROOT_DIR . '/models/page.php' );
include_once( ROOT_DIR . '/models/tag.php' );
include_once( SOFA_DIR . '/form.php' );
include_once( 'wiki_format.php' );
include_once( 'controller.php' );

class Wiki extends Controller {
  var $text, $page, $tag, $discussion, $message;

  function Wiki() {
    // Call parent constructor...
    $this->Controller();

    $this->page = new Page();
    $this->tag = new Tag();
    $this->discussion = new Discussion();
  }

  function index() {
    $this->load();
  }

  function edit() {
    if ( !$this->check_login() ) {
      $this->redirect_to( array( 'action' => 'login' ) );
    }
    $this->load();
    $this->title = "Edit: " . $this->page->title;
  }

  // TODO: Is this complete?
  function discuss() {
    $this->load();
    $this->check_login();
    if ( !$this->discussion->get_list( 'page_id=' . $this->page->id ) ) {
      $this->message = "There's no discussion here yet.  Why don't you start one?";
    }
    $this->title = "Discussion: " . $this->page->title;
  }

  function comment() {
    $this->page->get_where( "title='" . $_POST['page'] . "'" );
    $this->discussion->author = $_POST['author'];
    $this->discussion->email = $_POST['email'];
    $this->discussion->url = $_POST['url'];
    $this->discussion->comment = $_POST['comment'];
    $this->discussion->page_id = $this->page->id;
    $this->discussion->save();
    $this->redirect_to( array( 'action' => 'discuss', 'page' => $_POST['page'] ) );
  }

  function login() {
    if ( $this->do_login() ) {
      $this->redirect_to( array( 'page' => $_REQUEST['page'] ) );
    }
  }

  function logout() {
    $this->do_logout();
    $this->redirect_to( array( 'page' => $_REQUEST['page'] ) );
  }

  function sitemap() {
    $this->title = 'Site Map';
    $this->text = "This is a list of every page on the site:\n\n";

    if ( $this->page->get_list( '', 'title' ) ) {
      while( $this->page->next() ) {
        $this->text .= '* [[' . $this->page->title . "]]\n";
      }
    }
    else {
      $this->text .= "* None\n";
    }
    $this->page->title = '';
  }

  function tag() {
    $this->title = 'Tag: ' . $_REQUEST['tag'];

    if ( $this->tag->get_where( "tag='" . $_REQUEST['tag'] . "'" ) ) {
      $list = $this->tag->get_pages();
      $this->text = 'These pages are tagged ' . $_REQUEST['tag'] . ".\n\n";
      if ( !empty( $list ) ) {
	foreach( $list as $page ) {
	  $this->text .= '* [[' . $page . "]]\n";
	}
      }
      else {
	$this->text .= "* None\n";
      }
    }
    else {
      $this->text = 'Tag not found.';
    }
  }

  function load() {
    if ( !empty( $_POST['id'] ) ) {
      $this->page->get( $_POST['id'] );
    }
    else if ( !empty( $_REQUEST['page'] ) ) {
      if ( !$this->page->get_where( "title='" . $_REQUEST['page'] . "'" ) ) {
	$this->page->title = $_REQUEST['page'];
	if ( $this->action != 'edit' ) {
	  $this->page->content = "Sorry, that page was not found.\n\n" .
	    "Click the edit button above to create it.";
	}
	else {
	  $this->page->content = "";
	}
      }
    }
    else {
      $this->page->get( 1 );
    }
    $this->title = $this->page->title;
    $this->text  = htmlentities( stripslashes( $this->page->content ) );
  }

  function save() {
    if ( $this->check_login() ) {
      $this->page->get( $_POST['id'] );
      $this->page->title = $_POST['title'];
      $this->page->content = preg_replace( "/\r\n|\r/", "\n", $_POST['text'] );
      $this->page->user_id = $this->user->id;
      $this->page->set_tags( $_POST['tags'] );
      $this->page->save();
    }
    $this->redirect_to( array( 'page' => $this->page->title ) );
  }

  function delete() {
    // don't delete the home page
    if ( !empty( $_POST['id'] ) && $_POST['id'] != 1 ) {
      if ( $this->page->get( $_POST['id'] ) ) {
	$this->page->delete();
      }
    }
    $this->redirect_to();
  }

  function cancel() {
    $this->redirect_to( array( 'page' => $_REQUEST['page'] ) );
  }

  function content() {
    return wiki_format( $this->text );
  }

  // TODO: This is ugly, partial view?
  function sidebar() {
    if ( $this->action == 'sitemap' || $this->action == 'tag' ) {
      $sidebar = "<p>All Tags:</p>\n<ul>\n";
      if ( $this->tag->get_list( '', 'tag' ) ) {
	while( $this->tag->next() ) {
	  $sidebar .= "<li>".$this->link_to($this->tag->tag,array('action'=>'tag','tag'=>$this->tag->tag))."</li>\n";
	}
      }
      else {
	$sidebar .= "<li>None</li>\n";
      }
      $sidebar .= "</ul>\n";
    }
    else {
      $sidebar = "<p>Tags:</p>\n<ul>\n";
      $list = $this->page->get_tags();
      if ( !empty( $list ) ) {
	foreach( $list as $tag ) {
	  $sidebar .= "<li>" . $this->link_to( $tag, array('action'=>'tag','tag'=>$tag) ) . "</li>\n";
	}
      }
      else {
	$sidebar .= "<li>None</li>\n";
      }
      $sidebar .= "</ul>\n";
    }
    return $sidebar;
  }

  // TODO: this is still ugly, maybe it should be a 'partial' view?
  function navbar() {
    $navbar  = "<ul>\n";

    $navbar .= "<li>" . $this->link_to( 'Home' ) . "</li>\n";
    $navbar .= "<li>" . $this->link_to( 'Site Map', array( 'action' => 'sitemap' ) ) . "</li>\n";
    if ( $this->action != 'sitemap' ) {
      // can't discuss the site map
      $navbar .= "<li>" . $this->link_to('Discuss', array('action'=>'discuss','page'=>$this->page->title)) . "</li>\n";
    }
    $navbar .= "</ul>\n";

    $navbar .= "<ul class=\"auth\">\n";
    if ( $this->check_login() ) {
      $navbar .= "<li>" . $this->link_to('Logout', array('action'=>'logout','page'=>$this->page->title)) . "</li>\n";
      $navbar .= "<li>" . $this->link_to( 'User Admin', array( 'controller' => 'useradmin' ) ) . "</li>\n";
      if ( $this->action == 'index' ) {
	$navbar .= "<li>" . $this->link_to('Edit', array('action'=>'edit','page'=>$this->page->title)) . "</li>\n";
      }
    }
    else if ( $this->action != 'login' ) {
      $navbar .= "<li>" . $this->link_to('Login', array('action'=>'login','page'=>$this->page->title)) . "</li>\n";
    }
    $navbar .= "</ul>\n";

    return $navbar;
  }

}

?>
