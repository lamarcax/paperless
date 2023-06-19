<?
  /*
    profile_chooser_itf.php
    shows a dropdown menu containing managable profiles
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');

/*
  Get the profiles to be shown
*/
  $profiles = $user->get_admin_profiles();

/*
  If there are any, start the page
*/
  if ($profiles) {
    $javascript = "<SCRIPT LANGUAGE='javascript'>\n"
                 ."<!--\n"
                 ."  function del() {\n"
                 ."    document.profile_frm.action.value = 'delete';\n"
                 ."    document.profile_frm.submit();\n"
                 ."  }\n"
                 ."//-->\n"
                 ."</SCRIPT>";
      print_header("", $javascript);

/*
  Start the form, with a select-control containing the profiles
*/
      echo ("<form name='profile_frm' action='profile_admin.php' method='post'>\n"
           ."  <select name='profile_id' size='10' "
           .  " onchange='window.open(\"/profile_user_itf.php?profile_id=\" + this.options[this.selectedIndex].value,"
           .  " \"detail_frame\")'" 
           .  " onclick='window.open(\"/profile_user_itf.php?profile_id=\" + this.options[this.selectedIndex].value,"
           .  " \"detail_frame\")'>\n");
      if ($user->is_god) {
        echo ("  <option value='0'>". $ghost['name'] ."</option>\n");
      }
      foreach ($profiles as $value) {
        $prf = new profile($value);
        echo ("  <option value='" . $prf->id . "'>" . $prf->name . "</option>\n");
      }
      echo ("  </select>\n");

/*
  If the user is a God, buttons to manage the profiles are shown
*/
      if ($user->is_god) {
        echo ("<input type='hidden' name='action' value=''>\n"
             ."<input class='color' type='button' value='Delete Profile' onClick='javascript:del()'>\n"
             ."<input class='color' type='button' value='New Profile'"
             ." onClick='window.open(\"/edit_profile_itf.php?profile_id=new\", \"detail_frame\")'>\n"
             ."<input class='color' type='button' value='Edit Profile'"
             ." onClick='window.open(\"/edit_profile_itf.php?profile_id=\" + document.profile_frm.profile_id.value,"
             ."                      \"detail_frame\")'>\n");
      }
      echo ( "</form>\n");

      print_footer();
  }
?>
