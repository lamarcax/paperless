<?php

  /*
     This is the handler for revision_update_itf.php
     Inserting a new revision of a document
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  if (!empty($_POST["doc_id"])) {
    $desc = $_POST["desc"];
    $doc_id = $_POST["doc_id"];

    /*
     Get the size
    */

    $size = $_FILES['new_content']['size'];

    /*
       Get the filename
    */

    $tmp_name = $_FILES['new_content']['tmp_name'];

  /*
     Saves the revision
  */

    $error = save_new_revision($tmp_name, $size, $doc_id, $desc);

    /*
       Redirect to source
    */
    switch ($error) {
      case "File added": 
        $_SESSION["doc_id"] = $doc_id;
        header("Location: revision_itf.php");
        exit;
      case "No file selected":
        $_SESSION["doc_id"] = $doc_id;
        $_SESSION["error"] = true;
        $_SESSION["msg"] = "No file was selected.";
        $_SESSION["rev_desc"] = $desc;
        header("Location: revision_update_itf.php");
        exit;
      case "mime-type difference":
        $_SESSION["doc_id"] = $doc_id;
        $_SESSION["error"] = true;
        $_SESSION["msg"] = "The file selected was of a different type than the document.";
        $_SESSION["rev_desc"] = $desc;
        header("Location: revision_update_itf.php");
        exit;
    }
  }
  header("Location: access_denied");
?>
