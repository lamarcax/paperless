<?
    /*
      Logout.php
      Handles the logout requests.
      Destroys the current session and displays a goodbye-screen.
    */
  require_once('config.inc.php');
  require_once('functions.inc.php');
  require_once('auth.inc.php');
  session_destroy();
  
  /*
    Logging to the log file
  */

  require_once('log.inc.php');
  log_action($log_code[logout], "logout - ". $user->login);

  /*
    Change last_visited to now in table user
  */
 
  $query = "UPDATE user"
          ." SET last_visited = NOW( )"
          ." WHERE id = $user->id ";

  mysql_query($query)
    or die("Invalid query: " . mysql_error() . " query : $query");
 
  /*
    Goodbye screen
  */

  print_header("Logged out");
  echo ("<center><BR> Goodbye! <BR><BR> \n"
       ."<A href='index.php'>Back to login page</A> </center>\n");
  print_footer();
?>
