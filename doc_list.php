<?
    /*
      Doc_list.php
      Shows a listing of the contents of the current folder
    */
  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');
  require_once('functions.inc.php');

/*
  Prepare page
*/

  print_header("Document List", $listscript);
  print_site_header("Document List","list");

/*
  Initialize folder to be shown
*/

/*
   Getting stuff from the session or post arrays
*/

  if (empty($_POST["folder_id"])) {
    $redirect = $_SESSION;
  }
  else {
    $redirect = $_POST;
  }

  $folder_id = $redirect['folder_id'];
  

  session_unregister("folder_id");  

  if (empty($folder_id)) {
    $folder_id = 1;
  }

  $query = "SELECT count(id) as num from folder WHERE id='". $folder_id ."'";
  $res = mysql_query($query);
  $row = mysql_fetch_array($res);
  mysql_free_result($res);
  if ($row['num'] == 1) {

    $_SESSION['searchfolder'] = $folder_id;
    $folder = new folder($folder_id, $user->id);

    if (!empty($_SESSION['button_msg'])) {
      echo ($_SESSION['button_msg']);
      $_SESSION['button_msg'] = NULL;
      unset($_SESSION['button_msg']);
    }
  
    if ($folder->may_write) {
      /*
         create the form to edit the folder name
      */
  
      $folder_icon = "<img src='./pix/folder.gif'>";
      if ($folder->is_project) {
        $folder_icon = "<img src='./pix/project.gif'>";
      }
  
      $folder_edit_form = "<FORM action='folder.php' method='POST'>\n"
                         ."<input type='hidden' name='action' value='save'>\n"
                         . $folder_icon ." \n"
                         ." <input type='text' name='folder_name' value='". $folder->name ."'>\n"
                         ." <input type='text' name='folder_desc' size='50' value='". $folder->desc ."'>\n"
                         ." <input class='color' type='submit' value='Save'>\n"
                         ."<input type='hidden' name='folder_id' value='". $folder->id   ."'>\n"
                         ."</FORM>\n"; 
  
  
      print_list_header($folder_edit_form, $folder->may_write, $folder->id);
    }
    else {
      print_list_header($folder_icon ." ". $folder->name ." - ". $folder->desc, $folder->may_write, $folder->id);
    }
  
  /*
    build & show subfolderlist
  */
  
  
    /* Print the parent folder link */
    if ($folder->id != 1) {
      $parentfolder = get_visible_parent($folder, $user->id);
      echo ("\n<div class='parentrow'>\n");
      print_icon_folderlink($parentfolder->id, "Parent (". $parentfolder->name .")", $parentfolder->is_project);
      echo ("  &nbsp\n"
           ."</div>\n");
    }
  
    /* show all visible subfolders */
    $show = get_visible_subfolders($folder->id, $user->id);
    $color = true;
    foreach ($show as $child_id) {
      $child = new folder($child_id, $user->id);
      $color = !$color;
      print_folder_docline($child, $color);
    } 
  
  /*
    build & show documentlist
  */
  
    /* get all documents */
    $query = "SELECT id, name"
            ." FROM document"
            ." WHERE folder_id = '". $folder->id ."'"
            ." ORDER BY name";
    $res = mysql_query($query);
  
    /* Show document entries */
    while ($row = mysql_fetch_array($res)) {
      $doc = new document($row[id], $user->id);
      $color = !$color;
      print_document_docline($doc, $color);
    }
    mysql_free_result($res);
  
  /*
    Complete page
  */
    print_list_footer($folder->may_write);
  }
  else {
    $fld_id = $_SESSION['searchfolder'];
    echo ("<form name='browse_frm' action='doc_list.php' method='post'>\n");
    echo ("  <input name='folder_id' type='hidden' value=''>\n");
    echo ("</form>\n\n");
    echo ("This folder is no longer available.<BR>"
         ."<A href='javascript:click_browse(". $fld_id .")'>Back</A>\n\n");

  }

    print_site_footer();
    print_footer();
?>
