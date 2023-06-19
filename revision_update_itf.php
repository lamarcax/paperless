<?php

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('classes.inc.php');

  $javascript = "<SCRIPT language='javascript'>\n"
               ."<!--\n"
               ."  function cancel() {\n"
               ."    document.update_form.action = '/revision_itf.php';\n"
               ."    document.update_form.submit();\n"
               ."  }\n"
               ."//-->\n"
               ."</SCRIPT>\n";


  print_header("Document details", $javascript);

if (!empty($_POST['doc_id'])) {
  $doc_id = $_POST['doc_id'];
  $rev_desc = '';
}
else {
  $doc_id = $_SESSION['doc_id'];
  $update_error = $_SESSION['error'];
  $update_msg = $_SESSION['msg'];
  $rev_desc = $_SESSION['rev_desc'];
}

echo ("<DIV class='revbox'>\n"
     .  $msg
     ." <FORM name='update_form' METHOD='post' ACTION='revision_update.php' ENCTYPE='multipart/form-data'>"
     ."  <DIV class='revrow'>\n"
     ."   <SPAN class='label'>\n"
     ."    File Description:"
     ."   </SPAN>\n"
     ."   <SPAN class='field'>\n"
     ."    <INPUT TYPE='text' NAME='desc' size='40' value='". $rev_desc ."'>"
     ."    <INPUT TYPE='hidden' NAME='doc_id' value='". $doc_id ."'>"
     ."    <INPUT TYPE='hidden' name='MAX_FILE_SIZE' value='1000000'>"
     ."   </SPAN>\n"
     ."  </DIV>\n"
     ."  <DIV class='revrow'>\n"
     ."   <SPAN class='label'>\n"
     ."    File to upload/store in database:"
     ."   </SPAN>\n"
     ."   <SPAN class='field'>\n"
     ."    <INPUT class='color' TYPE='file' NAME='new_content' size='40'>"
     ."   </SPAN>\n"
     ."  </DIV>\n"
     ."  <DIV class='revrow'>\n"
     ."   <SPAN class='field'>\n"
     ."    <INPUT class='color' TYPE='submit' value='Send'>"
     ."    <INPUT class='color' TYPE='reset' value='Clear Fields'>"
     ."    <INPUT class='color' TYPE='button' onclick='cancel()' value='Cancel'>"
     ."   </SPAN>\n"
     ."  </DIV>\n"
     ." </form>"
     ."</DIV>\n");

  print_footer();

  $_SESSION['error'] = NULL;
  unset($_SESSION['error']);
  $_SESSION['msg'] = NULL;
  unset($_SESSION['msg']);
  $_SESSION['rev_desc'] = NULL;
  unset($_SESSION['rev_desc']);
?>
