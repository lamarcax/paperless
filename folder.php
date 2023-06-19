<?

/*
   The handler to make new folders and to save changes to the details of a folder
*/

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

$action = $_POST["action"];
  switch ($action) {
    case "new": 

      if (!empty($_POST["parent_id"])) {
        $redirect = $_POST;
      }
      else {
        $redirect = $_SESSION;
      }
      $parent_id = $redirect["parent_id"];
      $folder_name = $redirect["name"];
      $desc = $redirect["desc"];
      $is_project = $redirect["is_project"];

      $_SESSION["error_msg"] = NULL;
      unset($_SESSION["error_msg"]);
      session_unregister($error_msg);

      /*
         Saving the new folder
      */
      $error = save_new_folder($folder_name, $parent_id, $desc, $is_project);

      /*
         Redirecting to the document
      */

      if ($error["success"]) {

        $_SESSION["parent_id"] = NULL;
        unset($_SESSION["parent_id"]);
        session_unregister($parent_id);
        $_SESSION["name"] = NULL;
        unset($_SESSION["name"]);
        session_unregister($name);
        $_SESSION["desc"] = NULL;
        unset($_SESSION["desc"]);
        session_unregister($desc);
        $_SESSION["is_project"] = NULL;
        unset($_SESSION["is_project"]);
        session_unregister($is_project);
        $_SESSION["error"] = NULL;
        unset($_SESSION["error"]);
        session_unregister($error);
        $_SESSION["error_msg"] = NULL;
        unset($_SESSION["error_msg"]);
        session_unregister($error_msg);

        header("Location: doc_list.php");
      }
      else {
        unset($error["success"]);
        $error_msg = "";

        if ($error["duplicate"]) {
          $error_msg .= "Your folder name is not unique in the parent folder. <BR>\n";
          unset($error["duplicate"]);
        }

        foreach ($error as $key => $value) {
          $error_msg .= "The ". $key ." field was ". $value .". <BR>\n";  
          unset($error[$key]);
        }

        $_SESSION["error_msg"] = $error_msg;
        $_SESSION["name"] = $folder_name;
        $_SESSION["desc"] = $desc;
        $_SESSION["is_project"] = $is_project;
        $_SESSION["folder_id"] = $parent_id;

        header("Location: new_folder_itf.php");
      }
    break;
  case "save":
    $folder_id = $_POST['folder_id'];
    $folder_name = $_POST['folder_name'];
    $folder_desc = $_POST['folder_desc'];

    $error = save_folder($folder_id, $folder_name, $folder_desc);
 

    $_SESSION['folder_id'] = $folder_id;
    header("Location: doc_list.php");
    break;    
  case "cancel":

    $_SESSION["parent_id"] = NULL;
    unset($_SESSION["parent_id"]);
    session_unregister($parent_id);
    $_SESSION["name"] = NULL;
    unset($_SESSION["name"]);
    session_unregister($name);
    $_SESSION["desc"] = NULL;
    unset($_SESSION["desc"]);
    session_unregister($desc);
    $_SESSION["is_project"] = NULL;
    unset($_SESSION["is_project"]);
    session_unregister($is_project);
    $_SESSION["error"] = NULL;
    unset($_SESSION["error"]);
    session_unregister($error);
    $_SESSION["error_msg"] = NULL;
    unset($_SESSION["error_msg"]);
    session_unregister($error_msg);

    header("Location: doc_list.php");   
    break;
  }
?>
