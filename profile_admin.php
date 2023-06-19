<?
  /*
    profile_admin.php
    handles profile management requests
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');
  require_once('functions.inc.php');

  if (!empty($_POST['action'])) {
    $action = $_POST['action'];
    $profile_id = $_POST['profile_id'];
    $name = htmlspecialchars($_POST['name']);
    $desc = htmlspecialchars($_POST['desc']);
    $read_a = $_POST['read_a'];
    $update_a = $_POST['update_a'];
    $write_a = $_POST['write_a'];
    $confirm = $_POST['confirm'];


    if ($user->is_god) {
      if ($action == "cancel") {
        print_header();
        echo ("<script language='javascript'>\n" .
              "<!--\n" .
              "  window.open(\"/profile_chooser_itf.php\", \"profile_frame\");\n" .
              "  window.open(\"/profile_user_itf.php?profile_id=". $profile_id ."\", \"detail_frame\");\n" .
              "//-->\n" .
              "</script>");
        print_footer();
      }
      /* Perform the delete-action */
      else if ($action == "delete") {

        if (!isset($confirm)) {
          unset($_POST['action']);
          $str = array_to_hidden($str,$_POST);
          confirmBox("profile_admin.php",$action,$str, "Do you want to ". $action ." ?");
          exit;
        }
        else if ((isset($profile_id)) && ($profile_id != 0) && ($profile_id != 1)) {
          $p = new profile($profile_id);
          $query = "DELETE FROM profile WHERE id='". $profile_id ."'";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());

          $query = "DELETE FROM user_profile WHERE profile_id='". $profile_id ."'";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());

          $query = "DELETE FROM acl WHERE profile_id='". $profile_id ."'";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());
          /* Log the action */
          require_once('log.inc.php');
          log_action($log_code["delete_profile"], "Deleted profile ". $p->name ."(". $p->id .")");
        }
        print_header();
        echo ("<script language='javascript'>\n" .
              "<!--\n" .
              "  window.open(\"/profile_user_itf.php?profile_id=0\", \"detail_frame\");\n" .
              "  window.open(\"/profile_chooser_itf.php\", \"profile_frame\");\n" .
              "//-->\n" .
              "</script>");
        print_footer();
 
      }
      else if ($action == "edit") {

        $errormsg = "";
        $retry = array();
        $retry['name'] = $name;
        $retry['desc'] = $desc;
        if (empty($name)) {
          $errormsg .= "The field 'Name' was empty.<BR>";
        }
        if (empty($desc)) {
          $errormsg .= "The field 'Description' was empty.<BR>";
        }
        if ($errormsg != "") {
          $retry['errormsg'] = $errormsg;
          $_SESSION['retry'] = $retry;
          header('Location: edit_profile_itf.php?profile_id=new');
          exit;
        }

        /*save profile in DB*/

        if ($profile_id == "new") {
          $query = "INSERT INTO profile (name, `desc`) VALUES ('" . $name . "', '" . $desc . "')";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());
          $new_id = mysql_insert_id();
          $query = "SELECT id FROM folder";
          $res = mysql_query($query)
            or die ("Invalid query : " . $query . " -> " . mysql_error());
          while ($row = mysql_fetch_array($res)) {
            if (isset($read_a[$row['id']])) {
              $r = $read_a[$row['id']];
              $u = $update_a[$row['id']];
              $w = $write_a[$row['id']];
            }
            else {
              if ($row['id'] == 1) {
                $r = "y";
                $u = "n";
                $w = "n";
              }
              else {
                $r = "i";
                $u = "i";
                $w = "i";
              }
            }
            $insert = "INSERT INTO acl (profile_id, folder_id, `read`, `update`, `write`) " .
                      "VALUES ('" . $new_id . "', '" . $row[id] . "', '" . $r . "', '" . $u . "', '". $w ."')";
            mysql_query($insert)
              or die ("Invalid statement : " . $insert . " -> " . mysql_error());
          }
          /* Log the action */
          require_once('log.inc.php');
          log_action($log_code["create_new_profile"], "Created profile ". $name ."(". $new_id .")");

          print_header();
          echo ("<script language='javascript'>\n" .
                "<!--\n" .
                "  window.open(\"/profile_chooser_itf.php\", \"profile_frame\");\n" .
                "  window.open(\"/profile_user_itf.php?profile_id=". $new_id ."\", \"detail_frame\");\n" .
                "//-->\n" .
                "</script>");
          print_footer();
          mysql_free_result($res);
          exit;
        }

        else if ($profile_id == 0) {
          /*
             ghost profile change
          */

          $ghost['name'] = $name;
          $ghost['desc'] = $desc;
 
          save_configuration();
        }
        else if ($profile_id == 1) {
          $query = "UPDATE profile SET name='" . $name . "', `desc`='" . $desc . "' WHERE id='" . $profile_id . "'";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());
        }

        else {
          $query = "UPDATE profile SET name='" . $name . "', `desc`='" . $desc . "' WHERE id='" . $profile_id . "'";
          mysql_query($query)
            or die ("Invalid statement : " . $query . " -> " . mysql_error());
          $query = "SELECT id FROM folder";
          $res = mysql_query($query)
            or die ("Invalid query : " . $query . " -> " . mysql_error());
          while ($row = mysql_fetch_array($res)) {
            if (isset($read_a[$row['id']])) {
              $r = $read_a[$row['id']];
              $u = $update_a[$row['id']];
              $w = $write_a[$row['id']];
            }
            else {
              if ($row['id'] == 1) {
                $r = "y";
                $u = "n";
                $w = "n";
              }
              else {
                $r = "i";
                $u = "i";
                $w = "i";
              }
            }
            $update = "UPDATE acl SET `read`='" . $r . "', `update`='" . $u . "', `write`='" . $w . "' "
                     ."WHERE profile_id='" . $profile_id . "' AND folder_id='" . $row['id'] . "'";
            mysql_query($update)
              or die ("Invalid statement : " . $update . " -> " . mysql_error());
          }
          /* Log the action */
          require_once('log.inc.php');
          log_action($log_code["edit_profile"], "Edited profile ". $name ."(". $profile_id .")");
 
          mysql_free_result($res);
        }
        print_header();
        echo ("<script language='javascript'>\n" .
              "<!--\n" .
              "  window.open(\"/profile_user_itf.php?profile_id=". $profile_id ."\", \"detail_frame\");\n" .
              "//-->\n" .
              "</script>");
        print_footer();
      }
    }
    /* if the user is not a god, deny access */
    else {
      header('Location: access_denied.php');
    }
  }
?>
