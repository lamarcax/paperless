<?
    /*
	Login handler
        Processes login requests.
        The variable are checked against the database values.
	If they are correct a php-session is created and you are redirected to home.php.
        Else you are not logged in or have sent the wrong variable then you are redirected to index_itf.php.
    */

    require_once('config.inc.php');
    require_once('auth.inc.php');
    require_once('db.inc.php');
    require_once('classes.inc.php');
    require_once('functions.inc.php');
    
    print_header("Home",$listscript);

    print_site_header("Home","home");

    echo( "Welcome to the ". $cfg[site_name] ." document management system. <BR> \n");
    
    /*
      Subscription
    */	   

   $query = "SELECT doc_id"
           ." FROM subscription, document"
           ." WHERE user_id = '$user->id'"
           ." AND doc_id = id"
           ." ORDER BY modified DESC"; 

    $res = mysql_query($query)
      or die("Invalid query: " . mysql_error());

    if ($user->is_ghost) {
      echo ("You are a ghost.");
    }

    if (mysql_num_rows($res)) {

      print_list_header("Last visited : ". timestamp_human_time($user->last_visited)
                       ." <BR>Documents you are subscribed to:\n");

      /*
        Looping to display all subscription entries
      */
 
      $color = true;
      while ($row = mysql_fetch_array($res)) {
        $doc = new document($row[doc_id],$user->id);

        if ($doc->date_modified < $user->last_visited) {
          $color = false;
        }

        print_document_docline($doc, $color);
      }
      print_list_footer();
    }
    else {
      echo( "<BR>Last visited : ". timestamp_human_time($user->last_visited).
            " <BR>You are currently not subscribed to any documents. <BR>\n" );
    }

    print_site_footer();
    print_footer();
?>
