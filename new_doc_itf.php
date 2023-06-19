<?

  /*
     Shows a page with : 
       Filename . textbox met browse-button
       Extract . checkbox (zipfile extracten, batch uploading)
       Maintainer . dropdown (standaard op de huidige user)
       Description . textbox
       Remark . textbox
       Submit . button

     Depending on configuration : 
       Description required or not
       Maintainer automatic of manuel
 
    With batch uploading : Extra options -> same info. checkbox and .copy info. checkbox. 
    With .same info. , every field with be field in with the same information as the first for every file in the zip.
    With .copy info. , every file from the zip will have it's own input screen 
      with every field being file as a default with the first information.
    When neither is check every file from the zip will have it's own input screen with every field being blank. 

    On submit :
     If a normal document is being add, processed by new_doc.php.
     If a batch uploading document is chosen, processed by new_batch.php
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $javascript = "<script type='text/javascript'> \n"
               ."  function copy_path() { \n"
               ."    document.add_frm.path.value = document.add_frm.content.value; \n"
               ."    return true; \n"
               ."  } \n"
               ."  function cancel() {\n"
               ."    document.add_frm.action.value = 'cancel';\n"
               ."    document.add_frm.submit();\n"
               ."  }\n"
               ."</script> \n" ;

  print_header("Add document",$javascript);
  print_site_header("Add document","new_doc");

  if (!empty($_POST["folder_id"])) {
    $redirect = $_POST;
  }
  else {
    $redirect = $_SESSION;
  }

  $chk_normal = 'checked';
  $chk_batch = '';
  $chk_same = '';
  $chk_copy = '';

  $desc = '';
  $keyword = '';
  $content_desc = '';

  $folder_id = $redirect["folder_id"];

  if (!empty($redirect['zip_error'])) {
    echo ("Error : ". $redirect['zip_error']);
    $_SESSION['zip_error'] = NULL;
    unset($_SESSION['zip_error']);
  }

  if (!empty($redirect['file_error'])) {
    echo ("Error : ". $redirect['file_error']);
    $_SESSION['file_error'] = NULL;
    unset($_SESSION['file_error']);

    $error = $redirect['prev_post'];
    $new_type = $error['new_type'];
    $chk_normal = '';

    switch ($new_type) {
      case "normal": $chk_normal = 'checked'; break;
      case "batch" : $chk_batch = 'checked'; break;
      case "same"  : $chk_same = 'checked'; break;
      case "copy"  : $chk_copy = 'cheched'; break;
    }

    $desc = $prev_post['desc'];
    $keyword = $prev_post['keyword'];
    $content_desc = $prev_post['content_desc'];

    $_SESSION['prev_post'] = NULL;
    unset($_SESSION['zip_error']);
  }

  echo ("<DIV class='newdocbox'>\n"
       ."<FORM name='add_frm' METHOD='post' ACTION='document.php' ENCTYPE='multipart/form-data'>\n"
       ."  <input type='hidden' name='folder_id' value='". $folder_id . "'> \n"
       ."  <input type='hidden' name='action' value='new'> \n"
       ." <DIV class='newdocrow'>\n"
       ." <DIV class='modelabel'>\n"
       ."  Mode:\n"
       ." </DIV>\n"
       ." <DIV class='modefield'>\n"
       ."  <input type='radio' ". $chk_normal  ." name='new_type' value='normal'> Normal<BR>\n"
       ."  <input type='radio' ". $chk_batch  ." name='new_type' value='batch'> Batch<BR>\n"
       ."  <input type='radio' ". $chk_same ." name='new_type' value='same'> Batch Same<BR>\n"
       ."  <input type='radio' ". $chk_copy ." name='new_type' value='copy'> Batch Copy\n"
       ." </DIV>\n"
       ." </DIV>\n"
       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='label'>\n"
       ."   Info:\n"
       ."  </SPAN>\n"
       ."  <SPAN class='field'>\n"
       ."  <textarea cols=41 rows=5 maxlength=255 name='desc'>". $desc ."</textarea>\n"
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
       ."   <input type='textfield' name='keyword' value='". $keyword ."' size=55>\n"
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
       ."   <input type='text' name='content_desc' value='". $content_desc  ."' maxsize='127' size='55'>\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='infonewdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   Enter a short comment describing this revision of the document\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <input type='hidden' name='MAX_FILE_SIZE' value='30000000'>\n"
       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='label'>\n"
       ."   File: \n" 
       ."  </SPAN>\n"
       ."  <SPAN class='field'>\n"
       ."   <input type='file' name='content' size='55'>\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <input type='hidden' name='path' value='empty'>\n"
       ." <DIV class='infonewdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   File to upload/store in database\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ." <DIV class='newdocrow'>\n"
       ."  <SPAN class='field'>\n"
       ."   <input class='color' type='submit' value='Add document' onclick='copy_path()'>\n"
       ."   <input class='color' type='button' value='Cancel' onclick=\"javascript:cancel();\">\n"
       ."  </SPAN>\n"
       ." </DIV>\n"
       ."</form>\n"
       ."</DIV>\n");

  print_site_footer();
  print_footer();

?>
