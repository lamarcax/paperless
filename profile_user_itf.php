<?
  /*
    profile_user_itf.php
    provides an interface to manage the users in a profile
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

  function get_users_not_in($profile) {
    global $user;
    $prfs = $user->get_admin_profiles();

    if ($user->is_god) {
      array_push($prfs, 0);
    }

    $allusers = array();
    foreach ($prfs as $value) {
      if ($value != $profile->id) {
        $p = new profile($value);
        $users = $p->get_users();
        $allusers = array_merge($allusers, $users);
      }
    }

    $allusers = array_unique($allusers);
    $notusers = $profile->get_users();

    $returnarray =  array_diff($allusers, $notusers);
//    $returnarray = $allusers;
    return $returnarray;
  }

  $javascript = "<SCRIPT LANGUAGE='javascript'>\n"
               ."<!--\n"
               ."  function move_in() {\n"
               ."    if (document.profile_frm.users_out.value != 0) {\n"
               ."      document.profile_frm.action.value = 'add';\n"
               ."      document.profile_frm.submit();\n"
               ."    }\n"
               ."  }\n"

               ."  function move_out() {\n"
               ."    document.profile_frm.action.value = 'remove';\n"
               ."    document.profile_frm.submit();\n"
               ."  }\n"

               ."  function details() {\n"
               ."    uid = document.profile_frm.users_in.value;\n"
               ."    if (uid == \"\") {\n"
               ."      uid = document.profile_frm.users_out.value;\n"
               ."    }\n"
               ."    if (uid != \"\") {\n"
               ."      window.open(\"/user_admin_details_itf.php?user_id=\" + uid + \"&profile_id=\" + "
                                 ."document.profile_frm.profile_id.value, \"detail_frame\");\n"
               ."    }\n"
               ."  }\n"

               ."  function newuser(pid) {\n"
               ."    uid = 'new';\n"
               ."    window.open(\"/user_admin_details_itf.php?user_id=new&profile_id=\" + pid, \"detail_frame\");\n"
               ."  }\n"

               ."  function deluser() {\n"
               ."    document.profile_frm.action.value = 'delete';\n"
               ."    document.profile_frm.submit();\n"
               ."  }\n"
               ."//-->\n"
               ."</SCRIPT>";

  print_header("", $javascript);

  $profile_id = $_GET['profile_id'];
  $profile = new profile($profile_id);

  echo ("<DIV class='profilebox'>\n");
  echo ("<DIV class='titlebar'>\n");
  echo ("<div class='titlerow'>\n");
  echo ("<span class='left'>\n");
  echo ("Profile :");
  echo ("</span>\n");
  echo ("<span class='right'>\n"
       . $profile->name);
  echo ("</span>\n");
  echo ("</div>\n");
  echo ("<div class='titlerow'>\n");
  echo ("<span class='left'>\n"
       ."Description :");
  echo ("</span>\n");
  echo ("<span class='right'>\n"
       . $profile->desc);
  echo ("</span>\n");
  echo ("</DIV>\n");


  $users = $profile->get_users();

  echo ("<DIV class='detailbar'>\n");

  echo ("<form name='profile_frm' action='user_admin.php' method='post'>\n");

  echo ("<DIV class='left'>\n"

       ."  <select name='users_in' size=15>\n"); 
  echo ("<OPTGROUP label='---Users in profile---'>");

  foreach ($users as $value) {
    if ($value != $user->id) {
      $usr = new user($value);
      echo ("  <option value='" . $usr->id . "'>" . $usr->login . " - " . $usr->name . "</option>\n");
    }
  }
  echo ("</OPTGROUP>");
  echo ("  </select>\n");

  echo ("</DIV>\n");
  echo ("<DIV class='rest'>\n");
  echo ("<DIV class='middle'>\n");

  if ($profile->id != 0) {
    echo ("<input class='color' type='button' onclick='javascript:move_out()' value=' --> '><BR><BR> \n");
  }
  echo ("<input class='color' type='button' onclick='javascript:move_in()' value=' <-- '>\n");

  echo ("</DIV>\n");
  echo ("<DIV class='right'>\n"
       ."  <select name='users_out' size=15>\n");
  echo ("<OPTGROUP label='---Other Users---'>");
  $not_users = get_users_not_in($profile);
  foreach ($not_users as $value) {
    if ($value != $user->id) {
      $usr = new user($value);
      if ((!$usr->is_god) || ($profile->id == 0)) {
        echo ("  <option value='" . $usr->id . "'>" . $usr->login . " - " . $usr->name . "</option>\n");
      }
    }
  }
  echo ("</OPTGROUP>");
  echo ("  </select>\n");

  echo ("</DIV>\n");
  echo ("</DIV>\n");
  echo ("</DIV>\n");
  echo ("<DIV class='buttonbar'>\n");


  if ($profile->id != 0) {
    echo ("<input class='color' type='button' onClick='javascript:newuser(". $profile->id .")' value='New User...'>\n");
  }
  else {
    echo ("<input class='color' type='button' onClick='javascript:deluser()' value='Delete User'>\n");
  }
  echo ("<input class='color' type='button' onClick='javascript:details()' value='User Details...'>\n");

  echo ("<input type='hidden' name='action'>\n"
       ."<input type='hidden' name='profile_id' value='" . $profile->id . "'>\n");

  echo ("</DIV>\n"

       ."</form>\n");

  echo ("</DIV>\n");

  print_footer();
?>
