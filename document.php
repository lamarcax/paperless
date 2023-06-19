<?

/*
  -> ONE : Save document => save_doc.php

  -> BATCH : -> SAME INFO (all document) => save_doc.php
             -> COPY INFO (one document, and q of next with information) => new_doc_itf.php
             -> none (one document, and q of next without information) => new_doc_itf.php

*/

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');


$action = $_POST["action"];
$new_type = $_POST["new_type"];

/*
   Setting batch switch -> unzip or not (first run or later runs)
*/

$first = false;

switch ($action) {
  case "new":
    switch ($new_type) {
      /*
         Normal insert, no unzipping
      */
      case "normal":
        $folder_id = $_POST["folder_id"];
        $author_id = $user->id;
        $maintainer_id = $user->id;
        $name = $_FILES['content']['name'];
        $type = $_FILES['content']['type'];
        $path = $_POST['path'];
        $desc = $_POST["desc"];
        $file = $_FILES["content"]["tmp_name"];
        $content_desc = $_POST["content_desc"];
        $keywords = $_POST["keyword"];

        if (empty($name)) {
          $_SESSION['file_error'] = 'File was not selected.';
          $_SESSION['prev_post'] = $_POST;
          header("Location: new_doc_itf.php");
          exit;
        }

        else {

          /*
             Saving the document
          */
         $doc_id = save_new_doc($folder_id, $name, $author_id, $maintainer_id, $path, $desc, $file, $content_desc, $keywords);

          /*      
             Redirecting to the document
          */ 
          if ($doc_id != -1) {
            $_SESSION["doc_id"] = $doc_id;
            header("Location: doc_details_itf.php");
            exit;
          }
          else {
            $_SESSIOM["folder_id"] = $folder_id;
            header("Location: doc_list.php");
            exit;
          }
        }
        break;
      /********************************************************************************************** 
         Batch uploading, unzipping, use first user input as input for every file.
      */
      case "same":
        $zipfile = $_FILES["content"];

        $filelist = unzip($zipfile);

        if (empty($filelist['error'])) {
  

          $folder_id = $_POST["folder_id"];
          $author_id = $user->id;
          $maintainer_id = $user->id;
          $path = $_POST['path'];
          $desc = $_POST['desc'];
          $content_desc = $_POST["content_desc"];
          $keywords = $_POST["keyword"];

          /*
             Saving the document
          */
 
          foreach($filelist as $value) {
            $name = $value["file"];
            $tmp_name = $value["path"] ."/". $value["file"];

            $doc_id = save_new_doc($folder_id, $name, $author_id, $maintainer_id, $path, $desc, $tmp_name, $content_desc, $keywords);
          }
        }
        break;
      /**********************************************************************************************
         Batch uploading, unzipping, user input for every file.
      */
      case "batch":
        $zipfile = $_FILES["content"];
        $filelist = unzip($zipfile);

        if (empty($filelist['error'])) {

          $action = "batch_do";
          $first = true;

          $path = $_POST['path'];
          $orginal_desc = "";
          $orginal_content_desc = "";
          $orginal_keywords = "";
        }
        break;

      /**********************************************************************************************
         Batch uploading, unzipping, use user input as template for output form, require user input for every file.
      */
      case "copy":
        $zipfile = $_FILES["content"];
        $filelist = unzip($zipfile);

        if (empty($filelist['error'])) {

          $action = "batch_do";
          $first = true;

          $path = $_POST['path'];
          $desc = $_POST["desc"];
          $original_desc = $_POST["desc"];
          $original_content_desc = $_POST["content_desc"];
          $original_keywords= $_POST["keyword"];
        break;
        }
    }
    break;
  }

if ($filelist['zip_type_error']) {
  if (empty($filelist['mime_type'])) {
    $_SESSION['file_error'] = 'File was not selected.';
  }
  
  $_SESSION['prev_post'] = $_POST;
  
  $error = "'". $filelist['mime_type']. "'"
          ." type not supported by current sytem settings. Contact system administrator.";

  $_SESSION['zip_error'] = $error;
  header("Location: new_doc_itf.php");
  exit;
}

if ($action == "batch_do") {
  /**********************************************************************************************
     Handle batch uploading with user input
  */
  if (!$first) {
    /*
       Build filelist
    */
    $filelist = array();

    foreach ($_POST["next_tmp_path"] as $key => $value) {
      $element = array();

      $element["path"] = $value;
      $element["file"] = $_POST["next_tmp_names"][$key];
      $filelist[] = $element;
    }

    /*
      Get the current file from the list
    */

    $file = current($filelist);
    $name = $file["file"];
    $tmp_name = $file["path"] ."/". $name;

    /*
       Remove the current file from the next list
    */

    $key = key($filelist);
    unset($filelist[$key]);

    $folder = $folder_id;

    $author_id = $user->id;
    $maintainer_id = $user->id;

    $path = $_POST['path'];
    $desc = $_POST['desc'];
    $content_desc = $_POST["content_desc"];
    $keywords = $_POST["keyword"];

    /*
       Save the document
    */
    save_new_doc($folder_id, $name, $author_id, $maintainer_id, $path, $desc, $tmp_name, $content_desc, $keywords);

    /*
       Read the original input values (for batch copy uploading)
    */ 
      $orginal_desc = $_POST["orginal_desc"];
      $orginal_content_desc = $_POST["orginal_content_desc"];
      $orginal_keywords= $_POST["original_keyword"];
  }

  if (count($filelist)) {
    /*
       Get the current file from the list
    */
 
    $next_file = current($filelist);
    $name = $next_file["file"];
    $local_path = $next_file["path"];

    /**********************************************************************
       User input form
    */
    $javascript = "<script type='text/javascript'> \n"
                 ."  function cancel() {\n"
                 ."    document.add_frm.action.value = 'cancel';\n"
                 ."    document.add_frm.submit();\n"
                 ."  }\n"
                 ."</script> \n" ;

    print_header("Batch uploading", $javascript);
    print_site_header("Batch uploading");

    echo ("<DIV class='newdocbox'>\n"
         ."<FORM name='add_frm' METHOD='post' ACTION='document.php'>\n"
         ." <input type='hidden' name='folder_id' value='". $folder_id . "'> \n"
         ." <input type='hidden' name='action' value='batch_do'> \n"
         ." <DIV class='newdocrow'>\n"
         ."  <SPAN class='label'>\n"
         ."   Info: \n"
         ."  </SPAN>\n"
         ."  <SPAN class='field'>\n"
         ."  <textarea cols=41 rows=5 maxlength=255 name='desc'>". $original_desc ."</textarea>\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='infonewdocrow'>\n"
         ."  <SPAN class='field'>\n"
         ."   Enter a short comment describing this document\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='newdocrow'>\n"
         ."  <SPAN class='label'>\n"
         ."   Keywords: \n"
         ."  </SPAN>\n"
         ."  <SPAN class='field'>\n"
         ."   <input type='textfield' name='keyword' value='". $original_keywords ."' size=55>\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='infonewdocrow'>\n"
         ."  <SPAN class='field'>\n"
         ."   Enter keywords delimited by ");

    $delimit = "######";
 
    foreach ($keyword_cfg as $value) {
      $delimit = str_replace("######","'".$value ."',###### ",$delimit);
    }
    echo (str_replace("',######","'",$delimit));

    echo ("  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='newdocrow'>\n"
         ."  <SPAN class='label'>\n"
         ."   File Description: \n"
         ."  </SPAN>\n"
         ."  <SPAN class='field'>\n"
         ."   <input type='text' name='content_desc' value='". $original_content_desc  ."' maxsize='127' size='55'>\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='infonewdocrow'>\n"
         ."  <SPAN class='field'>\n"
         ."   Enter a short comment describing this revision of the document\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='newdocrow'>\n"
         ."  <SPAN class='label'>\n"
         ."   File: \n"
         ."  </SPAN>\n"
         ."  <SPAN class='field'>\n"
         .$name
         ."   <input type='hidden' name='name' value='". $name  ."'>\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='infonewdocrow'>\n"
         ."  <SPAN class='field'>\n"
         ."   File to be stored in database\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ." <DIV class='newdocrow'>\n"
         ."  <SPAN class='field'>\n"
         ."   <input type='submit' value='Add document'>\n"
         ."   <input type='button' value='Cancel' onclick=\"javascript:cancel();\">\n\n"
         ."  </SPAN>\n"
         ." </DIV>\n"
         ."  <input type='hidden' name='path' value='". stripslashes($path) ."'>\n"
         ."  <input type='hidden' name='original_desc' value='". $original_desc  ."'>\n"
         ."  <input type='hidden' name='original_keywords' value='". $original_keywords  ."'>\n"
         ."  <input type='hidden' name='original_content_desc' value='". $original_content_desc  ."'>\n");

   
    foreach($filelist as $value) {
      echo ("  <input type='hidden' name='next_tmp_path[]' value='". $value["path"] ."'>\n"
           ."  <input type='hidden' name='next_tmp_names[]' value='". $value["file"]  ."'>\n");
    }
    echo ("</form>\n"
         ."</DIV>\n");

    print_site_footer();
    print_footer();

    exit;
  }
  else {
    $_SESSION["folder_id"] = $folder_id;
  }
}
$_SEESION["folder_id"] = $folder_id;
header("Location: doc_list.php");
?>
