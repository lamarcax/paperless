<?
  /*
    user_admin.php
	handles requests from user_admin_itf.php (childframes to be exact)
  */
  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');
 

  $profile_id = $_POST['profile_id'];
  $action = $_POST['action'];
  $the_login = htmlspecialchars($_POST['login']);
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $mobile = $_POST['mobile'];
  $organisation = $_POST['organisation'];
  $position = $_POST['position'];
  $useradmin = $_POST['useradmin'];
  $folderadmin = $_POST['folderadmin'];

  $user_id = $_POST['user_id'];
  $users_in = $_POST['users_in'];
  $users_out = $_POST['users_out'];

  $confirm = $_POST['confirm'];

  $post = $_POST;

  $query = "SELECT count(id) as num FROM profile WHERE id='". $profile_id ."'";
  $res = mysql_query($query);

  $row = mysql_fetch_array($res);
  mysql_free_result($res);

  if (($row['num'] == 1) || ($action == 'delete')  ) {
    if ($user->is_user_admin($profile_id)) {  
      if ($action == "save") {

        foreach($user_field as $key => $value) {
          if (($value == 1) && (empty($_POST[$key]))) {
            $errormsg .= "Field '". $key ."' should not be empty.<BR>";
          }
        }
        if (!empty($errormsg)) {
          $post['errormsg'] = $errormsg;
          $_SESSION['post'] = $post;
          header("Location: /user_admin_details_itf.php?user_id=". $user_id ."&profile_id=". $profile_id);
          exit;
        }
 
        if ($user_id == "new") {
          $query = "INSERT INTO user"
                  ." (login, pass, name, email, phone, mobile, organisation, position)"
                  ."VALUES ('". $the_login ."', PASSWORD('". $the_login ."'), '". $name ."', '". $email ."',"
                  ." '". $phone ."', '". $mobile ."', '". $organisation ."', '". $position ."')";
          mysql_query($query);
          if (mysql_errno() == 1062) {
            $errormsg = "The login '". $the_login ."' already exists!";
            $post['errormsg'] = $errormsg;
            $_SESSION['post'] = $post;
            header("Location: /user_admin_details_itf.php?user_id=". $user_id ."&profile_id=". $profile_id);
            exit;
          }
          $user_id = mysql_insert_id();
          $query = "INSERT INTO user_profile"
                  ." (user_id, profile_id, useradmin)"
                  ."VALUES ('". $user_id ."', '". $profile_id ."', '0')";
          mysql_query($query)
            or die ("Invalid Statement : " . $query . " -> " . mysql_error());
  
          /* Logging the action */
          require_once('log.inc.php');
          $p = new profile($profile_id);
          log_action($log_code["create_new_user"], "Created ". $the_login ."(". $user_id .")");
          log_action($log_code["add_user_to_profile"], "Added ". $the_login ."(". $user_id .") to profile ". $p->name ."(". $p->id .")");

        }
        else {
          $query = "UPDATE user"
                  ." SET name='". $name ."', email='". $email ."', phone='". $phone ."', mobile='". $mobile ."',"
                  ." organisation='". $organisation ."', position='". $position ."'"
                  ." WHERE id='". $user_id ."'";
          mysql_query($query)
            or die ("Invalid Statement : " . $query . " -> " . mysql_error());
  
          if ((isset($useradmin)) || ($user->id == $user_id)) {
            $useradmin = 1;
          }
          else {
            $useradmin = 0;
          }
          $query = "UPDATE user_profile"." SET useradmin='". $useradmin ."'"
                  ." WHERE user_id='". $user_id ."' AND profile_id='". $profile_id ."'";
          mysql_query($query)
          or die ("Invalid Statement : " . $query . " -> " . mysql_error());
  
          /* Logging the action */
          require_once('log.inc.php');
          $u = new user($user_id);
          log_action($log_code["edit_user"], "Edited ". $u->login ."(". $u->id .")");
  
        }
      header("Location: /user_admin_details_itf.php?user_id=" . $user_id ."&profile_id=". $profile_id);
      }


      else if ($action == "add") {
      /* add the user to the profile, if the profile is not Ghosts */
        if ($profile_id != 0) {
          if ($profile_id == 1) {
            /* if adding to Gods, remove from all others */
            $query = "SELECT id, name"
                    ." FROM profile p, user_profile up"
                    ." WHERE (p.id = up.profile_id) AND (up.user_id='". $users_out ."')";
            $res = mysql_query($query)
              or die("Invalid query : " . $query . " -> " . mysql_error());
            $prfs = array();
            while ($row = mysql_fetch_array($res)) {
              $prfs[$row['id']] = $row['name'];
            }
            mysql_free_result($res);
            $query = "DELETE FROM user_profile"
                    ." WHERE user_id='". $users_out ."'";
            mysql_query($query)
              or die("Invalid statement : " . $query . " -> " . mysql_error());
            /* Logging the remove action */
            require_once('log.inc.php');
            $u = new user($users_out);
            foreach ($prfs as $key => $value) {
              log_action($log_code["remove_user_from_profile"], "Removed ". $u->login ."(". $u->id
                       .") from profile ". $value ."(". $key .")");
            }
          }
          $query = "INSERT INTO user_profile"
                  ." (user_id, profile_id, useradmin)"
                  ."VALUES ('" . $users_out. "', '" . $profile_id . "', '0')";
          mysql_query($query)
            or die("Invalid statement : " . $query . " -> " . mysql_error());
          /* Logging the action */
          require_once('log.inc.php');
          $u = new user($users_out);
          $p = new profile($profile_id);
          log_action($log_code["add_user_to_profile"], "Added ". $u->login ."(". $u->id .") to profile ". $p->name ."(". $p->id .")");
        }
        else {
          if (!isset($confirm)) {
            unset($_POST['action']);
            $str = array_to_hidden($str,$_POST);
            $u = new user($users_out);
            confirmBox("user_admin.php",$action,$str, "Do you want to remove ". $u->login ."(". $u->id .") from all profiles ?");
            exit;
          }
          else {
          /* Make user a ghost => remove him from all profiles */
            $query = "SELECT id, name"
                    ." FROM profile p, user_profile up"
                    ." WHERE (p.id = up.profile_id) AND (up.user_id='". $users_out ."')";
            $res = mysql_query($query)
              or die("Invalid query : " . $query . " -> " . mysql_error());
            $prfs = array();
            while ($row = mysql_fetch_array($res)) {
              $prfs[$row['id']] = $row['name'];
            }
            mysql_free_result($res);
            $query = "DELETE FROM user_profile"
                    ." WHERE user_id='". $users_out ."'";
            mysql_query($query)
              or die("Invalid statement : " . $query . " -> " . mysql_error());
            /* Logging the action */
            require_once('log.inc.php');
            $u = new user($users_out);
            foreach ($prfs as $key => $value) {
              log_action($log_code["remove_user_from_profile"], "Removed ". $u->login ."(". $u->id
                       .") from profile". $value ."(". $key .")");
            }
            /* delete the users subscriptions */
            $query = "DELETE FROM subscription"
                    ." WHERE user_id='$users_out'";
            mysql_query($query)
              or die("Invalid statement : " . $query . " -> " . mysql_error());
            log_action($log_code["removed_all_subscriptions"], "deleted all subscriptions for ". $u->login ."(". $u->id
                     .")");
          }
        }
        header("Location: /profile_user_itf.php?profile_id=" . $profile_id);
      }
  
      else if ($action == "remove") {
      /* remove the user from the profile, unless the profile is Ghosts */
        if ($profile_id != 0) {
          $query = "DELETE FROM user_profile"
                  ." WHERE user_id='" .$users_in . "' AND profile_id='" . $profile_id . "'";
          mysql_query($query)
            or die("Invalid statement : " . $query . " -> " . mysql_error());
          require_once('log.inc.php');
          $u = new user($users_in);
          $p = new profile($profile_id);
          log_action($log_code["remove_user_from_profile"], "Removed ". $u->login ."(". $u->id .") from profile ". $p->name ."(". $p->id .")");
        }
        header("Location: /profile_user_itf.php?profile_id=" . $profile_id);
      }
      else if ($action == "delete") {
        if (!isset($confirm)) {
          unset($_POST['action']);
          $str = array_to_hidden($str,$_POST);
          $u = new user($users_in);
          confirmBox("user_admin.php",$action,$str, "Do you want to ". $action ." ". $u->login ."(". $u->id .") ?");
          exit;
        }
        else {
        /* delete the user */
          $u = new user($users_in);
          $query = "DELETE FROM user"
                  ." WHERE id='$users_in'";
          mysql_query($query)
            or die("Invalid statement : " . $query . " -> " . mysql_error());
  
          /* remove all author-entries from documents */
          $query = "UPDATE document"
                  ." SET author_id = 0"
                  ." WHERE author_id='". $users_in ."'";
          mysql_query($query)
            or die("Invalid statement : " . $query . " -> " . mysql_error());
  
         /* remove all maintainer-entries from documents */
          $query = "UPDATE document"
                  ." SET maintainer_id = 0"
                  ." WHERE maintainer_id='". $users_in ."'";
          mysql_query($query)
            or die("Invalid statement : " . $query . " -> " . mysql_error());

          require_once('log.inc.php');
          log_action($log_code["delete_user"], "Deleted ". $u->login ."(". $u->id .")");

          header("Location: /profile_user_itf.php?profile_id=" . $profile_id);
        }
      }


      else if ($action == "resetpwd") {
        $u = new user($user_id);
        $query = "UPDATE user"
                ." SET pass= PASSWORD('". $u->login ."')"
                ." WHERE id='". $u->id ."'";
        mysql_query($query)
          or die("Invalid statement : " . $query . " -> " . mysql_error());
  
        require_once('log.inc.php');
        log_action($log_code["resetpass"], "Reset password for ". $u->login ."(". $u->id .")");

        header("Location: /user_admin_details_itf.php?user_id=" . $u->id ."&profile_id=". $profile_id);
      }
      else if ($action == "cancel") {
        header("Location: /profile_user_itf.php?profile_id=" . $profile_id);
      }
    }
    else {
      header("Location: /access_denied.php");
    }
  }
  else {
    print_header();
      echo ("<script language='javascript'>\n"
           ."<!--\n"
           ."  window.open(\"/profile_user_itf.php?profile_id=0\", \"detail_frame\");\n"
           ."  window.open(\"/profile_chooser_itf.php\", \"profile_frame\");\n"
           ."//-->\n"
           ."</script>");
    print_footer();
  }
?>
