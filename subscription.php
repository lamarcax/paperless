<?
  /*
    Handles document subscription requests
    gets from POST stream :
	doc_id (relevant document)
        source (page to redirect to afterwards)
	action (subscribe/unsubscribe from source - cancel from confirmBox)
	confirm (confirmation from confirmBox)
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');
  require_once('log.inc.php');

/*
  read POST stream
*/
  $doc_id = $_POST["doc_id"];
  $action = $_POST["action"];
  $source = $_POST["source"];
  $folder_id = $_POST["folder_id"];

  $doc = new document($doc_id, $user->id);

/*
  compile variables to be posted back, according to source
*/
  if ($source == "/doc_list.php") {
    $_SESSION["folder_id"] = $folder_id;
  }
  else if ($source == "/doc_details_itf.php") {
    $_SESSION["doc_id"] = $doc_id;
  }

/*
  if the action was not confirmed by a confirmBox, show one now
*/
  if (!isset($_POST[confirm])) {
    $post = "";
    foreach ($_POST as $key => $value) {
      if (($key != 'confirm') && ($key != 'action')) {
        $post = $post . "<input type='hidden' name='" . $key . "' value='" . $value . "'>\n";
      }
    }
    confirmBox(getenv("SCRIPT_NAME"), $action, $post, "Do you really want to " . $action . " to " . $doc->name . "?");
    exit;
  }

/*
  if "subscribe" was passed, insert a line into the subscription table
*/
  else if ($action == "subscribe") {
    $stmt = "INSERT into subscription (user_id, doc_id)"
           ." VALUES (" . $user->id . ", " . $doc_id . ")";
  }

/*
  if "unsubscribe" was passed, delete the relevant line from the subscription table
*/
  else if ($action == "unsubscribe") {
    $stmt = "DELETE from subscription"
           ." WHERE user_id = " . $user->id
           ." AND doc_id = " . $doc->id;
  }

/*
  carry out insert- or delete statement
*/
  mysql_query($stmt);

/*
  log the action
*/
  if (($action == "subscribe") || ($action == "unsubscribe")) {
    log_action($log_code[$action], $action . " - " . $doc->name);
  }

/*
  go back to the calling page after processing
*/

  header("Location: " .substr($source,1));
?>
