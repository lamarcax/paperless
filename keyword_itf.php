<?
 /*
   Showing a page (iframe inside doc_details.php) with :
     keyword entries : keyword (label) + select (checkbox)
     delete . button
     add/edit . button (add when no selection, edit when 1 keyword is selected, else disabled)
     edit . textbox (gevuld met geselecteerd keyword, leeg als er niet exact 1 geselecteerd is)
 */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  $javascript = "<SCRIPT language='JavaScript'>\n"
               ."<!--\n"
               ." function send(action)"
               ."  {\n"
               ."    document.send_frm.action.value = action;\n"
               ."    document.send_frm.submit();\n"
               ."  }\n "
               ."//-->\n"
               ."</SCRIPT>";


  /*
     Getting stuff from the session or post arrays
  */

  if (!isset($_SESSION["doc_id"])) {
    $redirect = $_POST;
  }
  else {
    $redirect = $_SESSION;
  }

  $doc_id = $redirect['doc_id'];

  if (!empty($doc_id)) {
    /*
       Checking the permission values with the folder_id (the parent folder) and the user_id.
       To get the parent_id we create the document object.       
    */
    $doc = new document($doc_id,$user->id);
    $perm_a = get_perms($doc->folder_id,$user->id);

    /*
      Now we check is the user can read or update the document, if so he will see something
      otherwise redirect to access_denied
    */
    if (($perm_a[may_read]) || ($perm_a[may_update])) {
      print_header("",$javascript);

      $keywords = new keywords($doc_id);

      echo ("<DIV class='keywordbox'>\n"
           ."<DIV class='keywordrow'>\n"
           ."<SPAN class='top'>\n"
           ."Keywords :\n"
           ."</SPAN>\n"
           ."</DIV>\n");      

      /*
        check, if you may update or not
      */

      if ($perm_a[may_update]) {
        echo ("<FORM name='send_frm' action='keyword.php' method='POST'>\n");

        /*
          The buttons to control the form : save/add delete reset

          If you have no write permission, you do not see this form
        */

        echo ("<input class='color' type='submit' value='Save/Add'>\n"
             ."<input class='color' type='button' value='Remove' onclick=\"javascript:send('delete')\">\n"
             ."<input class='color' type='reset' value='Reset'><br>\n");

        /*
          The hidden value to control the action to be taken
        */

        echo ("<input type='hidden' name='action' value='save_add'>\n"
             ."<input type='hidden' name='doc_id' value='$doc_id'>\n");
         
        /*
          Loop to print all keywords with update functionality
        */

        foreach ($keywords->keywords as $key => $value) {
          if ($keywords->visible[$key]) {
            echo ("<DIV class='keywordrow'>\n"
                 ."<SPAN class='check'>\n"
                 ."<input type='checkbox' name='chk[]' value='". $keywords->ids[$key] . "'>\n"
                 ."</SPAN>\n"
                 ."<SPAN class='word'>\n"
                 ."<input type='hidden' name='keyword_id[]' value='". $keywords->ids[$key] . "'>\n"
                 ."<input type='textfield' name='keyword[]' value='$value'> <br>\n"
                 ."</SPAN>\n"
                 ."</DIV>\n");
          }
        } 

        /*
          The new field
        */
        echo ("<DIV class='keywordrow'>\n"
             ."<SPAN class='check'>\n"
             ."New :"
             ."</SPAN>\n"
             ."<SPAN class='word'>\n"
             ."<input type='textfield' name='new_keyword' value=''><BR>\n"
             ."</SPAN>\n"
             ."</DIV>\n"
             ."<DIV class='keywordrow'>\n"
             ."<SPAN class='word'>\n"
             ."Enter keywords delimited by ");

        $delimit = "######";

        foreach ($keyword_cfg as $value) {
          $delimit = str_replace("######","'".$value ."',###### ",$delimit);
        }
        echo (str_replace("',######","'",$delimit)
             ."</SPAN>\n"
             ."</DIV>\n");

        echo ("</FORM>"
             ."</DIV>");
      }
      else {
        foreach ($keywords->keywords as $key => $value) {
          if ($keywords->visible[$key]) {
            echo ("<DIV class='keywordrow'>\n"
                 ."<SPAN class='word'>\n"
                 ."$value\n"
                 ."</SPAN>\n"
                 ."</DIV>\n");
          }
        }
      }

      print_footer();
      exit;
    }
  } 
  header("Location: access_denied.php");
  
?>
