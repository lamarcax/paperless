<?
  /*
    search.php
      Handles all search requests
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  $searchstring = $_POST['searchstring'];
  $mode = $_POST['mode'];
  $searchfolder = $_SESSION['searchfolder'];

  print_header("Search Results", $listscript);


 // if (!empty($searchstring)) {
    /* process the string for delimiters */
    $printstring = $searchstring; 
    foreach ($keyword_cfg as $value) {
      $searchstring = str_replace($value,"######",$searchstring);
    }
    /* get individual keywords from searchstring */
    $words_array = explode("######", $searchstring);





    $joinpart = "";
    $wherepart= "";
    if ($current_folder != 0) {
      $folderpart = " AND (d.folder_id='". $searchfolder ."'))";
    }
    else {
      $folderpart = ")";
    }

    switch ($mode) {
      case 0:
        /* Match all words */
        foreach($words_array as $i => $value) {
          if (!empty($value) && ($i != 0)) {
            $joinpart .= " left join doc_keyword dk". $i
                        ." ON ((dk.doc_id=dk". $i .".doc_id) AND (dk". $i .".keyword='". $value ."'))";
            $wherepart .= " AND NOT(dk". $i .".keyword IS NULL)";
          }
        }
        $joinpart .= " left join document d"
                    ." ON ((dk.doc_id=d.id)" . $folderpart;
        $wherepart = " NOT(d.folder_id IS NULL) AND (dk.keyword='". $words_array[0] ."')"
                     .$wherepart;
        break;
      case 1:
        /* Match any word */
        $joinpart = ",document d";
        $wherepart = " ((dk.doc_id = d.id)". $folderpart ." AND (";

        foreach($words_array as $value) {
          if (!empty($value)) {
            $wherepart .= " keyword='". $value ."' OR";
          }
        }
        $wherepart .= ")";
        $wherepart = str_replace(" OR)", ")", $wherepart);
        $wherepart = str_replace("()", "(1)", $wherepart);
        break;

      case 2:
        /* Partly match word */
        $joinpart = ", document d";
        $wherepart = " ((dk.doc_id = d.id)". $folderpart ." AND (";

        foreach($words_array as $value) {
          if (!empty($value)) {
            $wherepart .= " keyword LIKE '%". $value ."%' OR";
          }
        }
        $wherepart .= ")";
        $wherepart = str_replace(" OR)", ")", $wherepart);
        $wherepart = str_replace("()", "(1)", $wherepart);
        break;
    }

    if ($advanced == "yes") {
      $docname = $_POST['docname'];
      $docdesc = $_POST['docdesc'];
      $doctype = $_POST['doctype'];
      $year = $_POST['year'];
      if (empty($year)) {
        $year="____";
      }
      $month = $_POST['month'];
      if (empty($month)) {
        $month="__";
      }
      $day = $_POST['day'];
      if (empty($day)) {
        $day="__";
      }
      $month = str_pad($month, 2, "0", STR_PAD_LEFT);
      $day = str_pad($day, 2, "0", STR_PAD_LEFT);

      $wherepart .= " AND ((d.name LIKE '%". $docname ."%')"
                   ." AND (d.desc LIKE '%". $docdesc ."%')"
                   ." AND (d.type LIKE '%". $doctype ."%')"
                   ." AND (d.modified LIKE '". $year . $month . $day ."%'))";
    }

    $query = "SELECT DISTINCT dk.doc_id"
            ." FROM doc_keyword dk"
            . $joinpart
            ." WHERE"
            . $wherepart;

    print_site_header("Search Results");


    $res = mysql_query($query)
      or die ("Invalid query: ". $query ." -> ". mysql_error());
    if (mysql_num_rows($res) == 0) {
      print_docline("Search finished without result");
    }
    else {
      $color = true;
      $nohits = true;
      $projects_hit = array();

      while ($row = mysql_fetch_array($res)) {
        $hit = new document($row['doc_id'], $user->id);
        $fld = new folder($hit->folder_id, $user->id);
        if ($fld->may_read && ((!$fld->is_project) || ( ($current_folder == 1) && ($fld->id == $searchfolder) ))) {
          if ($nohits) {
            print_list_header("Search String: '". htmlspecialchars($printstring) ."'");
          }
          print_document_docline($hit, $color);
          $color = !$color;
          $nohits = false;
        }
        else if ($fld->may_read && $fld->is_project && empty($projects_hit[$fld->id])) {
          if ($nohits) {
            print_list_header("Search String: '". htmlspecialchars($printstring) ."'");
          }
          print_folder_docline($fld, $color);
          $projects_hit[$fld->id] = 1;
          $color = !$color;
          $nohits = false;
        }
      }
      if ($nohits) {
        print_docline("Search finished without result");
      }
      else {
        print_list_footer();
      }
    }
    print_site_footer();
    print_footer();
?>
