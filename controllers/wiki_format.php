<?php

  // Add simple html formatting to a string.
  //   I hope you like regular expressions...
  function wiki_format( $str ) {
    // handle horizontal rules
    $str = preg_replace( '/^----+$/m', 
      "<hr noshade=\"noshade\" size=\"1\" />\n", $str);
  
    // handle headings
    $str = preg_replace( '/^===(.*?)===$/m', "<h3>\\1</h3>\n", $str );
    $str = preg_replace( '/^==(.*?)==$/m', "<h2>\\1</h2>\n", $str );
    $str = preg_replace( '/^=(.*?)=$/m', "<h1>\\1</h1>\n", $str );
  
    // handle emphasis + strong
    $str = preg_replace( '/\*_(\S[\w -\/]*?)_\*/', 
      '<strong><em>\\1</em></strong>', $str );
  
    $str = preg_replace( '/_\*(\S[\w -\/]*?)\*_/', 
      '<em><strong>\\1</strong></em>', $str );
  
    // handle emphasis
    $str = preg_replace( '/\b_(\S[\w -\/]*?)_\b/', '<em>\\1</em>', $str );
  
    // handle strong
    $str = preg_replace( '/\*(\S[\w -\/]*?)\*/', '<strong>\\1</strong>', $str );
  
    $str = format_links( $str );
    $str = format_lists( $str );
    $str = format_paragraphs( $str );
  
    return $str;
  }
  
  // format links
  function format_links( $str ) {
    // handle tag links
    $str = preg_replace( '/\[\[tag:([\w ]*?)\]\]/', 
      '<a href="?action=tag&tag=\\1">\\1</a>', $str);

    // handle page links
    $str = preg_replace( '/\[\[([\w ]*?)\]\]/', 
      '<a href="?page=\\1">\\1</a>', $str);

    // handle named web links
    $str = preg_replace( '/\[\[(http:\/\/\S+[A-Za-z0-9\/]) ([\w &;]*?)\]\]/', 
      '<a href="\\1">\\2</a>', $str);

    // handle no name web links
    $str = preg_replace( '/\[\[(http:\/\/\S+[A-Za-z0-9\/])\]\]/', 
      '<a href="\\1">\\1</a>', $str);
  
    // handle named e-mail links
    $str = preg_replace( '/\[\[(\S+@\S+[A-Za-z0-9]) ([\w]*?)\]\]/', 
      '<a href="mailto:\\1">\\2</a>', $str);
    
    // handle no name e-mail links
    $str = preg_replace( '/\[\[(\S+@\S+[A-Za-z0-9])\]\]/', 
      '<a href="mailto:\\1">\\1</a>', $str);
  
    return $str;
  }
  
  // format paragraphs and line breaks
  function format_paragraphs( $str ) {
    // split into paragraphs
    $paras = preg_split( '/\n\n+/', $str );
  
    // add line and paragraph breaks
    foreach( $paras as $i => $v ) {
      // don't add <p> to headings, rules, or lists
      $match = preg_match( '/^<h|^<ul>|^<ol>|^<li>|^<\/ul>|^<\/ol>/', 
                 $paras[$i] );
      if ( !$match ) {
        $paras[$i] = preg_replace( '/\n/', "<br />\n", $paras[$i] );
        $paras[$i] = "<p>" . $paras[$i] . "</p>";
      }
    }
  
    // recombine the paragraphs
    $str = join( "\n", $paras );
  
    return $str;
  }
  
  // format lists in the given string
  function format_lists( $str ) {
    // split into lines
    $lines = preg_split( '/\n/', $str );
  
    $in_ul = false;
    $in_ol = false;
    
    foreach( $lines as $i => $v ) {
      // handle bulleted lists
      if ( preg_match( '/^\*\s*(.*?)$/m', $lines[$i] ) ) {
        $lines[$i] = preg_replace( '/^\*\s*(.*?)$/m', 
          "<li>\\1</li>\n", $lines[$i] );
        if ( $in_ul == false ) {
          $lines[$i] = "<ul>\n" . $lines[$i];
          $in_ul = true;
        }
      }
      else if ( $in_ul == true ) {
        $in_ul = false;
        $lines[$i] = $lines[$i] . "</ul>\n";
      }
  
      // handle numbered lists
      if ( preg_match( '/^\#\s*(.*?)$/m', $lines[$i] ) ) {
        $lines[$i] = preg_replace( '/^\#\s*(.*?)$/m', 
          "<li>\\1</li>\n", $lines[$i] );
        if ( $in_ol == false ) {
          $lines[$i] = "<ol>\n" . $lines[$i];
          $in_ol = true;
        }
      }
      else if ( $in_ol == true ) {
        $in_ol = false;
        $lines[$i] = $lines[$i] . "</ol>\n";
      }
    }
    
    // recombine the lines
    $str = join( "\n", $lines );
  
    // make sure we closed the lists  
    if ( $in_ol == true ) {
      $str = $str . "</ol>\n";
    }
    if ( $in_ul == true ) {
      $str = $str . "</ul>\n";
    }
  
    return $str;
  }

?>
