<?
  /*
    edit_profile_itf.php
    provides an interface to edit profiles
  */
  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');
  require_once('functions.inc.php');
  require_once('classes.inc.php');


/*
  build_tree($rootfolder_id, $level, $echostring)
    generates an html-form that shows a foldertree with checkboxes
    !NEEDS TO BE REVISED!
*/

  function print_option($type,$selected) {
    $returnstr = "  <option value='". $type ."' ". $selected ." >";

    switch ($type) { 
      case 'n': 
        $returnstr .= "No";
        break;
      case 'i':
        $returnstr .= "Inherit";
        break;
      case 'd':
        $returnstr .= "Deny";
        break;
      case 'y': 
        $returnstr .= "Yes";
        break;
    }
    
    $returnstr .= "</option>\n";

    return $returnstr;
  }
 
  function print_options($type, $root, $selected) {
    $y_sel = '';
    $n_sel = '';
    $i_sel = '';
    $d_sel = '';

    switch ($selected) {
      case "y": 
        $y_sel = 'selected';
        break;
      case "n":
        $n_sel = 'selected';
        break;
      case "i":
        $i_sel = 'selected';
        break;
      case "d":
        $d_sel = 'selected';
        break;
      default:
        if ($root) {
          $n_sel = 'selected';
        }
        else {
          $i_sel = 'selected';
        }
    }

    switch ($type) {
      case "read":
        if ($root) {
          $returnstr = print_option('y','selected');
        }
        else {
          $returnstr = print_option('y',$y_sel)
                      .print_option('n',$n_sel)
                      .print_option('i',$i_sel)
                      .print_option('d',$d_sel);
        }
        break;
      case "update":
        if ($root) {
          $returnstr = print_option('y',$y_sel)
                      .print_option('n',$n_sel)
                      .print_option('d',$d_sel);
        }
        else {
          $returnstr = print_option('y',$y_sel)
                      .print_option('n',$n_sel)
                      .print_option('i',$i_sel)
                      .print_option('d',$d_sel);
        }
        break;
      case "write":
        if ($root) {
          $returnstr = print_option('y',$y_sel)
                      .print_option('n',$n_sel)
                      .print_option('d',$d_sel);
        }
        else {
          $returnstr = print_option('y',$y_sel)
                      .print_option('n',$n_sel)
                      .print_option('i',$i_sel)
                      .print_option('d',$d_sel);
        }
        break;
    }

    return $returnstr;
  }

  function print_select($folder_id,$profile) {
    $root = false;

    if ($folder_id == 1) {
      $root = true;
    }
    if ($profile == "new") {
      $returnstr = "<TD><select name='read_a[". $folder_id ."]'>\n"
                  .print_options('read',$root,'')
                  ."</select></TD>\n"
                  ."<TD><select name='update_a[". $folder_id ."]'>\n"
                  .print_options('update',$root,'')
                  ."</select></TD>\n"
                  ."<TD><select name='write_a[". $folder_id ."]'>\n"
                  .print_options('write',$root,'')
                  ."</select></TD>\n";
     }
    else {
      $query = "SELECT `read`, `update`, `write`"
              ." FROM acl"
              ." WHERE folder_id='". $folder_id ."' AND profile_id='". $profile ."'";
       
      $res = mysql_query($query)
        or die ("Invalid query: ". $query ." -> ". mysql_error());
      $row = mysql_fetch_array($res);
      mysql_free_result($res);
    
      $returnstr = "<TD><select name='read_a[". $folder_id ."]'>\n"
                  .print_options('read',$root,$row['read'])
                  ."</select></TD>\n"
                  ."<TD><select name='update_a[". $folder_id ."]'>\n"
                  .print_options('update',$root,$row['update'])
                  ."</select></TD>\n"
                  ."<TD><select name='write_a[". $folder_id ."]'>\n"
                  .print_options('write',$root,$row['write'])
                  ."</select></TD>\n";
    } 
    return $returnstr;
  }

$depth = -1;
$buildarray = array();
$max_level = 0;

  function build_tree($rootfolder_id, $prf_id, $level) {
    global $user;
    global $depth;
    global $buildarray;
    global $max_level;

    $depth++;
    
    if ($max_level < $level) {
      $max_level = $level;
    }
 
    /*
       Get folder and icon
    */
    $folder = new folder($rootfolder_id, $user->id);

    $pix = "<img src='./pix/folder.gif'>";
    $corner = "<img src='./pix/corner.gif'>";
    $cross = "<img src='./pix/cross.gif'>";
    $line = "<img src='./pix/line.gif'>";

    if ($folder->is_project) {
      $pix = "<img src='./pix/project.gif'>";
    }
    $pix .= $folder->name;

    /*
       Build the needed arrays
    */

    if (empty($buildarray[$depth])) {
      $buildarray[$depth] = array();
    }

    $buildarray[$depth][$level] = $pix;
    $buildarray[$depth]["folder_id"] = $folder->id;

    $children = get_visible_subfolders($rootfolder_id, $user->id);
    
    $current_depth = 0;
 
    foreach($children as $key => $value) {
      $current_depth = $depth + 1;

      if ((count($children) == $key + 1)) {
        $line_fill = "";
        $cross_fill = $corner;
      }
      else {
        $line_fill = $line;
        $cross_fill = $cross;
      }

      $buildarray[$current_depth][$level] = $cross_fill;

      build_tree($value, $prf_id, $level + 1);

      for($i = $current_depth + 1; $i <= $depth; $i++) {
          $buildarray[$i][$level] = $line_fill;
      }
    }

    if ($level == 0) {
      $echostring = "<BR><TABLE cellpadding=0 cellspacing=0>\n";

      ksort($buildarray);

      foreach($buildarray as $key => $rowvalue) {
        $echostring .= "<TR>\n";
        
        $echostring .= print_select($rowvalue['folder_id'],$prf_id);
        unset($rowvalue['folder_id']);

        ksort($rowvalue);
        $current_level = -1;
        foreach ($rowvalue as $key2 => $colvalue) {
          if ((count($rowvalue) == $key2 + 1)) {
            $echostring .= "<TD colspan='". ($max_level - $current_level) ."'>$colvalue</TD>\n";
          }
          else {
            $echostring .= "<TD>$colvalue</TD>\n";
          }
          $current_level++;
        }
        $echostring .= "</TR>\n";
      }
      $echostring .= "</TABLE><BR>\n";
      return $echostring;
    }
    else {
      return $buildarray;
    }
  }

  $profile_id = $_GET['profile_id'];
  $retry = $_SESSION['retry'];
    $prf = new profile($profile_id);
    if ($profile_id == "new") {
      $prf->name = "";
      $prf->desc = "";
    }
    if (!empty($retry)) {
echo $retry['name'];
      $prf->name = $retry['name'];
      $prf->desc = $retry['desc'];
    }


  $javascript = "<SCRIPT language='JavaScript'>\n <!--\n"

               ."  function cancel(prf) {\n"
               ."    window.open(\"/profile_user_itf.php?profile_id=\" + prf, \"detail_frame\");\n"
               ."  }\n"

               ." //-->\n </SCRIPT> ";

  print_header("", $javascript);

  if (($profile_id != 0) && ($profile_id != 1)) {
    echo ($retry['errormsg']);
    echo ("<FORM action='profile_admin.php' method='post'>\n");
    echo ("  Name <input type='text' name='name' value='" . $prf->name . "'><BR>\n"
         ."  Description <input type='text' name='desc' value='" . $prf->desc . "'><BR>\n"
         ."  Folders<BR>"
         . build_tree(1,$profile_id,0)
         ."  <input type='hidden' name='action' value='edit'>"
         ."  <input type='hidden' name='profile_id' value='". $profile_id ."'>"
         ."  <input class='color' type='submit' value='Save'>"
         ."  <input class='color' type='reset' value='Reset'>"
         ."  <input class='color' type='button' onClick='cancel(". $profile_id .")' value='Cancel'>"
         ."</FORM>\n");
  }
  else if ($profile_id == "new") {
    echo ($retry['errormsg']);
    echo ("<FORM action='profile_admin.php' method='post'>\n");
    $prf = new profile($profile_id);
    if ($profile_id == "new") {
      $prf->name = "";
      $prf->desc = "";
    }
    echo ("  Name <input type='text' name='name' value='" . $prf->name . "'><BR>\n"
         ."  Description <input type='text' name='desc' value='" . $prf->desc . "'><BR>\n"
         ."  Folders<BR>"
         . build_tree(1,$profile_id,0)
         ."  <input type='hidden' name='action' value='edit'>"
         ."  <input type='hidden' name='profile_id' value='". $profile_id ."'>"
         ."  <input class='color' type='submit' value='Save'>"
         ."  <input class='color' type='reset' value='Reset'>"
         ."  <input class='color' type='button' onClick='cancel(0)' value='Cancel'>"
         ."</FORM>\n");
  }
  else {
    echo ($retry['errormsg']);
    echo ("<FORM action='profile_admin.php' method='post'>\n");
    $prf = new profile($profile_id);
    echo ("  Name <input type='text' name='name' value='" . $prf->name . "'><BR>\n"
         ."  Description <input type='text' name='desc' value='" . $prf->desc . "'><BR>\n"
         ."  <input type='hidden' name='action' value='edit'>"
         ."  <input type='hidden' name='profile_id' value='". $prf->id ."'>"
         ."  <input class='color' type='submit' value='Save'>"
         ."  <input class='color' type='reset' value='Reset'>"
         ."  <input class='color' type='button' onClick='cancel(". $prf->id .")' value='Cancel'>"
         ."</FORM>\n");
  }
  $_SESSION['retry'] = NULL;
  print_footer();
?>
