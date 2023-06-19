<?
  /*
    Button_handler.php
    Handles incoming requests from buttons in doc_list.php
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  if (!empty($_POST['action'])) {
    $action = $_POST['action'];
    $checkArray = $_POST['chk'];
  }

  function download($chkArray, $docArray, $RevArray, $redirect, $folder_id, $fol_chkArray, $folderArray) {
    global $user;

    $dowArray = array();

    if (!empty($fol_chkArray)) {
      foreach ($fol_chkArray as $value) {
       $dowArray[] = $folderArray[$value];
      }
    }

    /*
       We get the documents from the folder into the docArray, with the current revision number
    */

    if (empty($chkArray)) {
      $chkArray = array();
      $RevArray = array();
      $docArray = array();
    }

    if (!empty($dowArray)) {
      $next = $chkArray[count($chkArray)-1];
      $next++;

      foreach($dowArray as $value) {
        
        $folder = new folder($value, $user->id);
        $fol_down_list = $folder->download_list();

        foreach ($fol_down_list["doc_id_a"] as $key => $value) {
          $chkArray[$next] = $next;
          $docArray[$next] = $value;
          $RevArray[$next++] = $fol_down_list["rev_id_a"][$key];
        }
      }
    }
    else {
      $_SESSION['button_msg'] = "Select documents, folders or projects to download.";
    }

    $_SESSION["folder_id"] = $folder_id;
    $_SESSION["chk"] = $chkArray;
    $_SESSION["doc_id_a"] = $docArray;
    $_SESSION["revision_a"] = $RevArray;
    $_SESSION["redirect"] = $redirect;

    header("Location: revision.php");
  }

  function add_document($folder_id) {
    $_SESSION['folder_id'] = $folder_id;

    header('Location: /new_doc_itf.php');
  }

  function add_folder($folder_id) {
    $_SESSION['folder_id'] = $folder_id;

    header('Location: /new_folder_itf.php');
  }

  function delete($fol_chkArray, $folderArray, $folder_id, $doc_chkArray, $docArray) {
    $_SESSION["folder_id"] = $folder_id;
    /*
       Build folder confirm str
    */
    if (!empty($fol_chkArray)) {

      $delArray = array();

      foreach ($fol_chkArray as $value) {
       $delArray[] = $folderArray[$value];
      }
      $str = array_to_hidden($str,$delArray,"folder_ids[]");
    }

    /*
       Build document confirm str
    */
    if (!empty($doc_chkArray)) {

      $delArray = array();

      foreach ($doc_chkArray as $value) {
        $delArray[] = $docArray[$value];
      }
      $str = array_to_hidden($str,$delArray,"doc_ids[]");
    }
    if (empty($str)) {
      $_SESSION['button_msg'] = "Select documents, folders or projects to delete.";

      header("Location: doc_list.php");
      exit;
    }
    else
    {
      confirmBox("button_handler.php","delete",$str, "Do you want to delete the documents and/or the folders ?");
    }
  }

  function build_options($subject_id_a, $usr_id, $fld_id, $indent="") {

    $noprojects = false;
    foreach ($subject_id_a as $value) {
      $subject = new folder($value, $usr_id);
      if (!$subject->is_project) {
        $noprojects = true;
      }
    }

    $fld = new folder($fld_id, $usr_id);
    if (!in_array($fld->id, $subject_id_a)) {
      if ($fld->may_write && (($noprojects && !$fld->is_project) || (!$noprojects))) {
        $printstring = "<option value='". $fld->id ."'>". $indent . $fld->name ."</option>\n";
      }
      $subs = get_visible_subfolders($fld->id, $usr_id);
    
      foreach ($subs as $value) {
        $printstring .= build_options($subject_id_a, $usr_id, $value, $indent."-");
      }
    }
    return $printstring;
  }

  function move($d_chk, $f_chk, $d_ids, $f_ids, $f_id) {
    if (!empty($d_chk) || !empty($f_chk)) {
      $subjects = array();
      if (!empty($f_chk)) {
        foreach($f_chk as $value) {
          $subjects[] = $f_ids[$value];
        }
      }
      global $user;
      $javascript = "<script type='text/javascript'> \n"
                   ."  function cancel() {\n"
                   ."    document.move_frm.action.value = 'cancel';\n"
                   ."    document.move_frm.submit();\n"
                   ."  }\n"
                   ."</script> \n" ;

      print_header("Move", $javascript);
      print_site_header("Move","move");
      $hiddens = "";
      $hiddens = array_to_hidden($hiddens, $d_chk, "doc_chk[]");
      $hiddens = array_to_hidden($hiddens, $d_ids, "doc_id_a[]");
      $hiddens = array_to_hidden($hiddens, $f_chk, "folder_chk[]");
      $hiddens = array_to_hidden($hiddens, $f_ids, "folder_id_a[]");

      echo ("<form name='move_frm' action='move.php' method='post'>\n"
           .$hiddens
           ."  <input type='hidden' name='folder_id' value='". $f_id ."'>\n"
           ."  <input type='hidden' name='action' value='move'>\n"
           ." <select name='target'>\n"
           .build_options($subjects, $user->id, 1)    
           ." </select>"
           ."<input class='color' type='submit' value='Move'>\n"
           ."<input class='color' type='button' onClick='cancel()' value='Cancel'>\n"
           ."</form>\n");
      print_site_footer();
      print_footer();
    }
    else {
      $_SESSION['button_msg'] = "Select documents, folders or projects to move.";
      $_SESSION['folder_id'] = $f_id;
      header('Location: /doc_list.php');
      exit;
    }
  }

  switch ($action) {
    case "download":
      download($_POST["doc_chk"], $_POST["doc_id_a"], $_POST["revision_a"], $_POST["redirect"],$_POST["folder_id"],$_POST["folder_chk"], $_POST["folder_id_a"]);   
      break;
    case "add_doc":
      add_document($_POST["folder_id"]);
      break;
    case "move":
      move($_POST["doc_chk"], $_POST["folder_chk"],$_POST["doc_id_a"], $_POST["folder_id_a"], $_POST["folder_id"]);
      break;
    case "add_folder":
      add_folder($_POST["folder_id"]);
      break;
    case "delete":
      if (empty($_POST["confirm"])) {
        delete($_POST["folder_chk"], $_POST["folder_id_a"], $_POST["folder_id"], $_POST["doc_chk"], $_POST["doc_id_a"]);
      }
      else {
        /* 
           Remove them selected
        */
        if (!empty($_POST["doc_ids"])) {
          foreach($_POST["doc_ids"] as $value) {
            $doc = new document($value, $user->id);

            $doc->delete();
          }
        }

        if (!empty($_POST["folder_ids"])) {
          foreach($_POST["folder_ids"] as $value) {
            $fol = new folder($value,$user->id);
            $result = $fol->delete();
            if (!$result['success']) {
              $_SESSION['button_msg'] .= "You cannot delete folder with name '". $result['folder_name'] . "'.<BR>";
            }
          }
        }
        header("Location: doc_list.php");
      }
      break;
    default:
      if (!empty($_POST['folder_id'])) {
        $_SESSION['folder_id'] = $_POST['folder_id'];
      }
      header('Location: /doc_list.php');

      break;
  } 
?>
