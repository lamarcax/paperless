<?
  /*
    Generates a page with : (changeable or not depending on user-permissions)
     description . textbox/label
     subscription . checkbox
     filename . textbox/label + extension . label
     filesize . label (dynamisch aanpassen aan gekozen revision; in kB, ondergrens .>1kB.)
     mime type . label
     author . label
     maintainer . dropdown/label
    date . label (dynamisch aanpassen aan gekozen revision)
    iframe met keyword_itf.php
    download . button

    depending on permissions :
      save changes . button (=> doc_details.php)
      delete . button (=> doc_doc.php)
      update . button (=> update_itf.php)
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('classes.inc.php');

  $javascript = "<SCRIPT language='JavaScript'>\n <!--\n"
               ."  function click_browse(folder) {\n"
               ."    document.browse_frm.folder_id.value = folder;\n"
               ."    document.browse_frm.submit();\n"
               ."  }\n"
               ."// --> \n</SCRIPT>";  


  print_header("Document details",$javascript);

  print_site_header("Document Details","doc_details");


  /*
     Getting doc_id from post stream if it is not set in the session
  */

  if (empty($_POST['doc_id'])) {
    $doc_id = $_SESSION["doc_id"];
  }
  else {
    $doc_id = $_POST["doc_id"];
  }
 
  session_unregister("doc_id");
  unset($_SESSION["doc_id"]); 
  /*
     If no doc_id in post stream or session, generate an error
  */ 
  if (empty($doc_id)) {
    echo ("Document id not set");
    exit;
  }
  $query = "SELECT count(id) as num from document WHERE id='". $doc_id ."'";
  $res = mysql_query($query);
  $row = mysql_fetch_array($res);
  mysql_free_result($res);
  if ($row['num'] == 1) {
  
    $doc = new document($doc_id, $user->id);
    $folder = new folder($doc->folder_id, $user->id);
    $author = new user($doc->author_id);
    $maintainer = new user($doc->maintainer_id);


    /*
       Creating a link to the parent folder
    */ 


    echo ("<DIV class='docbox'>\n");
    echo ("<form name='browse_frm' action='doc_list.php' method='post'>\n");
    echo ("  <input name='folder_id' type='hidden' value='". $doc->parent_id ."'>\n");
    echo ("</form>\n\n");

    echo ("<DIV class='docheadrow'>\n");
    echo ("    <A href='javascript:click_browse(". $folder->id .")'>");
    if ($folder->is_folder) {
      echo ("<IMG SRC='./pix/project.gif'>");
    }
    else {
      echo ("<IMG SRC='./pix/folder.gif'>");
    }

    echo (" ". $folder->name ."</A>\n\n");
    if ($folder->may_read) {
      echo ("  <IMG SRC='./pix/read.gif' title='You have read access.'>\n");
    }
    else {
      echo ("  <IMG SRC='./pix/no_read.gif' title=''\n");
    }

    if ($folder->may_update) {
      echo ("  <IMG SRC='./pix/update.gif' title='You can update this document.'>\n");
    }
    else {
      echo ("  <IMG SRC='./pix/no_update.gif' title='You cannot update this document.'>\n");
    }

    if ($folder->may_write) {
      echo ("  <IMG SRC='./pix/write.gif' title='You have full access.'>\n");
    }
    else {
      echo ("  <IMG SRC='./pix/no_write.gif' title='You do not have full access.'>\n");
    }

    echo ("</DIV>\n");

    /*
       Start of the doc_details submit form
    */
    echo ("<DIV class='docdivisionbox'>\n");

    echo ("<FORM action='doc_details.php' method='post'>\n");
  
    echo ("  <input type='hidden' name='doc_id' value='$doc_id'>\n");
    echo ("  <input type='hidden' name='source' value='" . getenv("SCRIPT_NAME") . "'>\n");
  
    echo (" <DIV class='docdtlbox'>\n");
    echo (" <DIV class='doctitlerow'>\n");
    echo ("  Details :<BR>\n");
    echo (" </DIV>\n");

    /*
      Document details display, depending on permissions
    */

    if ($folder->may_read) {
      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("    Name :");
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value overflow'>\n");
      echo ($doc->name . "\n");
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");

      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("    Type :");
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value overflow'>\n");
      echo ($doc->type . "\n");
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");

      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("    Author :");
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value overflow'>\n");
      echo ($author->name ." <A href='mailto:". $author->email ."'>". $cfg['email_link'] ."</a>\n");
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");

      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("Maintainer :"); 
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value overflow'>\n");
  
      if (($folder->may_update) && (!$automatic_maintainer)) {
        /*
          Now I show a drop down box with all the users that can access this document and has update rights
          getting this list from folder itself.
        */
     
        echo ("<select name='maintainer_id'>\n"
             ."  <option value='$maintainer->id'>"
             ."$maintainer->name</option>\n");
  
        foreach ($folder->get_users("update") as $key => $value) {
          echo ("  <option value='". $key ."'>". $value ."</option>\n");
        } 
        echo ("</select> <A href='mailto:". $maintainer->email ."'>". $cfg['email_link'] ."</a>\n");
      } 
      else {
        if ($automatic_maintainer) {
          echo ("+ ");
        }
        echo ("$maintainer->name <A href='mailto:". $maintainer->email ."'>". $cfg['email_link'] ."</a>\n");
      }
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");
    
        /*
           Date_modified and path are automatic values
        */ 
  
      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("    Date Modified :\n"); 
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value overflow'>\n");
      echo (timestamp_human_time($doc->date_modified) ."\n");
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");
  
      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      echo ("    Path :\n");
      echo ("  </SPAN>\n");
      echo ("  <SPAN class='value' title='".$doc->path."'>\n");
      if (strlen($doc->path) > 50) {
        echo (substr($doc->path, 0, 50) . "...");
      }
      else {
        echo ($doc->path ."\n");
      }
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");
  
        /*
           Visual representation, of 'is description required'
        */

      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='label'>\n");
      if ($doc_field[desc]) {
        echo ("* Description :");
      }
      else {
        echo ("Description :");
      }

      echo ("  </SPAN>\n");
      echo ("  <div class='value'>\n");

      if ($folder->may_update) {
        echo ("  <textarea cols=41 rows=5 maxlength=255 name='desc'>". $doc->desc ."</textarea>\n");
      }
      else {
        echo ("  <textarea readonly cols=41 rows=5 maxlength=255 name='desc'>". $doc->desc ."</textarea>\n");
      }
      echo ("  </div>\n");
      echo (" </DIV>\n");

        /*
            The maintainer and god users can see the remark field, and update it if they can update the document
        */

      if (($doc->maintainer_id == $user->id) || ($user->is_god)) {
        echo (" <DIV class='docdtlrow'>\n");
        echo ("  <SPAN class='label'>\n");
        echo ("Remark :"); 
        echo ("  </SPAN>\n");
        echo ("  <SPAN class='value overflow'>\n");
        if ($folder->may_update) {
          echo ("  <INPUT type='textfield' size=57 maxlength=127 name='remark' value='$doc->remark'>\n");
        }
        else {
          echo ("$doc->remark\n");
        }
        echo ("  </SPAN>\n");
        echo (" </DIV>\n");
      }
  
      if ($folder->may_update) {
        echo (" <DIV class='docdtlrow'>\n");
        echo ("  <SPAN class='value'>\n");
        echo ("   <INPUT TYPE='hidden' name='action' value='doc_detail_update'>\n");
        echo ("   <INPUT class='color' TYPE='submit' value='Save document changes'>\n");
        echo ("   <INPUT class='color' TYPE='reset' value='Reset the form'>\n");
        echo ("  </SPAN>\n");
        echo (" </DIV>\n");
      }
   
      echo (" </FORM>\n\n");  
  
      /*
         Subscription form, depending on the current subscription state
      */
      echo (" <DIV class='docdtlrow'>\n");
      echo ("  <SPAN class='value'>\n");
  
      echo ("   <FORM action='subscription.php' method='post'>\n");
  
      if (!$doc->subscribed) {
        echo ("    <input class='color' type='submit' value='subscribe'>\n");
        echo ("    <input type='hidden' name='action' value='subscribe'>\n");
      }
      else {
        echo ("    <input class='color' type='submit' value='unsubscribe'>\n");
        echo ("    <input type='hidden' name='action' value='unsubscribe'>\n");
      }
  
      echo ("    <input type='hidden' name='doc_id' value='" . $doc->id . "'>\n");
      echo ("    <input type='hidden' name='source' value='" . getenv("SCRIPT_NAME") . "'>\n");
      echo ("   </FORM>\n\n");
      echo ("  </SPAN>\n");
      echo (" </DIV>\n");
  
      echo (" </DIV>\n");
  
      /*
        Generating the iframe for the keyword display
      */
  
      $_SESSION["doc_id"] = $doc->id;
      echo (" <DIV class='dockeybox'>\n");
      echo ("  <iframe name='keyword_fr' width='95%' height='350px' src='./keyword_itf.php' FRAMEBORDER='0'></iframe>\n");
      echo (" &nbsp</DIV>\n");
  
  
      echo ("&nbsp</DIV>\n");
  
      /*
        Generating the iframe for the revision display
      */
  
      $_SESSION["doc_id"] = $doc->id;
      echo ("<DIV class='docdivisionbox'>\n");
      echo (" <iframe name='revision_fr' width='100%' src='./revision_itf.php' FRAMEBORDER='0'></iframe>\n");
      echo ("</DIV>\n");
      echo ("&nbsp</DIV>\n");
      /*
        Printing the end of the page
      */
    }
  }
  else {
    $fld_id = $_SESSION['searchfolder'];
    echo ("<form name='browse_frm' action='doc_list.php' method='post'>\n");
    echo ("  <input name='folder_id' type='hidden' value=''>\n");
    echo ("</form>\n\n");
    echo ("This document is no longer available.<BR>"
         ."<A href='javascript:click_browse(". $fld_id .")'>Back</A>\n\n");

  }
  print_site_footer();
  print_footer();

?>
