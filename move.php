<?
  /*
    move.php
      handles all move requests 
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');


  if (!empty($_POST["folder_id"])) {
    $redirect = $_POST;
  }
  else {
    $redirect = $_SESSION;
  }

  $folder_id = $redirect["folder_id"];
  $doc_chk = $redirect["doc_chk"];
  $folder_chk = $redirect["folder_chk"];
  $doc_id_a = $redirect["doc_id_a"];
  $folder_id_a = $redirect["folder_id_a"];
  $target = $redirect["target"];
  $action = $redirect["action"];

if ($action != 'cancel') {
  $move_docs = array();
  if (!empty($doc_chk)) {
    foreach($doc_chk as $value) {
      $move_docs[] = $doc_id_a[$value];
    }
  }

  $move_folders = array();
  if (!empty($folder_chk)) {
    foreach($folder_chk as $value) {
      $move_folders[] = $folder_id_a[$value];
    }
  }

  foreach($move_folders as $val) {
    $query = "UPDATE folder"
            ." SET parent_id='". $target ."'"
            ." WHERE id='". $val ."'";
    mysql_query($query)
      or die ("Invalid statement: ". $query . " -> ". mysql_error());
    require_once('log.inc.php');
    $fld = new folder($val, $user->id);
    $tgt = new folder($target, $user->id);
    log_action($log_code["move_folder"], "Moved folder \'". $fld->name ."(". $val .")\' to target folder \'". $tgt->name ."(". $target .")\'");
  }

  foreach($move_docs as $val) {
    $query = "UPDATE document"
            ." SET folder_id='". $target ."'"
            ." WHERE id='". $val ."'";
    mysql_query($query)
      or die ("Invalid statement: ". $query . " -> ". mysql_error());
    require_once('log.inc.php');
    $doc = new document($val, $user->id);
    $tgt = new folder($target, $user->id);
    log_action($log_code["move_document"], "Moved document \'". $doc->name ."(". $val .")\' to target folder \'". $tgt->name ."(". $target .")\'");
  }
}
  $_SESSION['folder_id'] = $folder_id;
  header ('Location: /doc_list.php');
?>
