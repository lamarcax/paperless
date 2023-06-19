<?
  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $javascript = "<SCRIPT language='JavaScript'>\n"
               ."<!--\n"
               ."  function cancel() {\n"
               ."    document.add_frm.action.value = 'cancel';\n"
               ."    document.add_frm.name.value = '';\n"
               ."    document.add_frm.desc.value = '';\n"
               ."    document.add_frm.is_project.value = '0';\n"
               ."    document.add_frm.submit();\n"
               ."  }\n"
               ."//-->\n"
               ."</SCRIPT>\n";


  print_header("Add Folder",$javascript);
  print_site_header("Add folder","new_folder");

  if (!empty($_POST["folder_id"])) {
    $folder_id = $_POST["folder_id"]; /* parent_id */
  }
  else {
    $folder_id = $_SESSION["folder_id"]; /* parent_id */
    $folder_name = $_SESSION["name"];
    $is_project = $_SESSION["is_project"];
    $desc = $_SESSION["desc"];
    $error_msg = $_SESSION["error_msg"];
  }

  $parent = new folder($folder_id,$user->id);

  echo ($error_msg 
       ."<DIV class='newdocbox'>\n"
       ."<FORM name='add_frm' METHOD='post' ACTION='folder.php'>\n"
       ." <input type='hidden' name='parent_id' value='". $folder_id . "'> \n"
       ." <input type='hidden' name='action' value='new'> \n"
       ." <DIV class='newdocrow'>\n"
       ."  <DIV class='modefield'>\n");

  if ($is_project) {
    if (!$parent->is_project) {
      echo ("   <input type='radio' name='is_project' value='0'> Folder <BR>\n"
           ."   <input type='radio' checked name='is_project' value='1'> Project\n");
    }
    else {
      echo ("<input type='radio' checked name='is_project' value='1'> Project\n");
    }
  }
  else {
    if (!$parent->is_project) {
      echo ("   <input type='radio' checked name='is_project' value='0'> Folder <BR>\n"
           ."   <input type='radio' name='is_project' value='1'> Project\n");
    }
    else {
      echo ("<input type='radio' checked name='is_project' value='1'> Project\n");
    }
  }

  echo ("  </DIV>\n"
       ." </DIV>\n"

       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='label'>\n"
       ."   Folder name: \n"
       ."  </SPAN>\n"
       ."  <SPAN class='field'>\n"
       ."   <input type='text' name='name' value='". $folder_name ."' maxsize='63' size='50'>\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='infonewdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   Enter the name of the new folder \n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='label'>\n"
       ."   Desc: \n"
       ."  </SPAN>\n"
       ."  <SPAN class='field'>\n"
       ."   <input type='textfield' name='desc' value='". $desc ."' maxsize='127' size='50'> \n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='infonewdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   Enter a short comment describing this folder \n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   <input type='submit' value='Add Folder'> \n"
       ."   <input type='button' value='Cancel'  onclick=\"javascript:cancel();\">\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ."</form> \n"
       ."</DIV>\n");

  

  print_site_footer();
  print_footer();

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


?>

