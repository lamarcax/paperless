<?
  /*
    Does all keyword transaction. 
    Ask if you are certain about your action
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  /*
    Get variables from post stream
  */

  $action = $_POST[action];
  $confirm = $_POST[confirm];
  $chk_A = $_POST[chk];
  $keyword_id_A = $_POST[keyword_id];
  $keyword_A = $_POST[keyword];
  $doc_id = $_POST[doc_id];
  $new_keyword = $_POST[new_keyword];

  /*
    Check control variables to decide which action to take
  */

  if ($action != 'cancel') {
    if (!isset($confirm)) {

      /*
        Creating the extra information for the confirm box
	Copying all receive info.
      */

      $extra = "<input type='hidden' name='doc_id' value='$doc_id'>\n"
              ."<input type= 'hidden' name='new_keyword' value='$new_keyword'>\n"; 

      if (isset($chk_A)) {
        foreach ($chk_A as $value) {
          $extra = $extra ."<input type='hidden' name='chk[]' value='$value'>\n";
        }    
      }

      if (isset($keyword_id_A)) {
        foreach ($keyword_id_A as $value) {
          $extra = $extra ."<input type='hidden' name='keyword_id[]' value='$value'>\n";
        }
      }

      if (isset($keyword_A)) {
        foreach ($keyword_A as $value) {
          $extra = $extra ."<input type='hidden' name='keyword[]' value='$value'>\n";
        }
      }
      switch ($action) {
        case "save_add":
          if (isset($chk_A) || !empty($new_keyword)) {
            confirmBox("keyword.php",$action, $extra, "Are you sure you want to save your changes?");
            exit;
          }
          break;
     
        case "delete":
          if (isset($chk_A)) {
            confirmBox("keyword.php",$action, $extra, "Are you sure you want to delete the selected keywords?");
            exit;
          }
          break;
      }
    }
    else {
      if ($action == 'delete') {
        deleteKeywords($chk_A);
      }
      else if ($action == 'save_add') {
        save_addKeywords($doc_id, $new_keyword,$keyword_id_A,$keyword_A);
      }
    }
  }

  /*
    Redirects to keyword_itf.php, after the action has happened
  */

  $_SESSION['doc_id'] = $doc_id;
  header("Location: keyword_itf.php");

?>
