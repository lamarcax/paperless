<?

/*
  Tonen van een lijst van resision van een document, met de mogelijkheid voor downloading.
*/

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  /*
    Check to see if a doc_id was given through POST, if yes read it.
        Why would I check, I am/was planning on changing this to a POST variable and removing the check,
        as you might have figured out already, if you are reading this I did not.
  */

  if (!empty($_SESSION["doc_id"])) {
    $doc_id = $_SESSION["doc_id"];

  }
  else {
    $doc_id = $_POST["doc_id"];
  }

  /*
     Checking the permission values with the folder_id (the parent folder) and the user_id.
     To get the parent_id we create the document object.
  */
    $doc = new document($doc_id,$user->id);
    $perm_a = get_perms($doc->folder_id,$user->id);

    /*
      Now we check if the user can read the document, if so he will see something
      otherwise redirect to access_denied
    */
    if ($perm_a[may_read]) {
      $javascript = "<SCRIPT LANGUAGE='javascript'>\n"
                   ."<!--\n"
                   ."  function update() {\n"
                   ."    document.rev_frm.action = 'revision_update_itf.php';\n"
                   ."    document.rev_frm.submit();\n"
                   ."  }\n"
                   ."//-->\n"
                   ."</SCRIPT>";

      print_header("", $javascript);
      $revisions = new revisions($doc_id);
 
      echo ("<div class='revdtlbox'>\n"
           ."<div class='revlabel'>\n"
           ."Revision :"
           ."</div>\n"
           ."<div class='revdetail'>\n"
           ."<FORM name='rev_frm' action='revision.php' method='POST'>\n"
           ." <INPUT class='color' TYPE='submit' value='download'>\n");
      if ($perm_a[may_update]) {
        echo ("<INPUT class='color' TYPE='button' onClick='update()' value='update'>\n");
      }
      echo (" <input type='hidden' name='redirect' value='revision_itf.php'>\n"
           ." <input type='hidden' name='doc_id' value='". $doc_id ."'>\n"
           ." <table class='rev' border=0>\n"
           ."  <TR class='line header'>\n"
           ."   <TD>Select\n"
           ."   <TD>Nr\n"
           ."   <TD>Date\n"
           ."   <TD>Description\n"
           ."   <TD>Filesize\n"
           ."  </TR>\n");
      $color = true;
      foreach ($revisions->revisions as $key => $value) {
        if ($color) {
          echo ("  <TR class='line color1'>");
          $color = !$color;
        }
        else {
          echo ("  <TR class='line color2'>");
          $color = !$color;
        }
        echo ("   <TD><INPUT TYPE='checkbox' name='chk[]' value='". $key ."'>\n");

        echo ("   <INPUT TYPE='hidden' name='doc_id_a[]' value='". $doc_id ."'>\n"
             ."   <INPUT TYPE='hidden' name='revision_a[]' value='". $value->revision ."'>\n");

        echo ("   <TD>". $value->revision
             ."   <TD>". timestamp_human_time($value->date)
             ."   <TD>". $value->desc
             ."   <TD> ". $value->size
             ."  </TR>");
      }
      
      echo (" </TABLE>\n"
           ."</FORM>&nbsp</div></div>\n");
      exit;
    }
  header("Location: access_denied.php"); 
?>
