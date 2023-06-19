<?
  /*
    user_perms_itf.php
      provides an interface to set the useradmin & folderadmin attributes for a user in all his profiles
      (used in an iframe in user_admin_details_itf.php)
  */

  require_once('config.inc.php');
  require_once('classes.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $user_id = $_GET['user_id'];

  if ($user_id != "new") {
    $the_user = new user($user_id);
    if ($the_user->is_god) {
      $profiles = array(1);
    }
    else {
      $profiles = $the_user->get_profiles();
    }
  }
  print_header();
  if (!empty($profiles)) {
    echo ("<DIV class='prfscontent'><FORM action='/user_perms.php' method='post'>"
         ."Profiles this user is in :<BR>"
         ."<input class='color' type='submit' value='Save Permissions'>"
         ."<input class='color' type='reset' value='Reset'><BR>"
         ."<input type='hidden' name='user_id' value='". $the_user->id ."'>"
         ." <TABLE class='prfs'>"
         ."  <TR class='header color1'>"
         ."    <TD>useradmin?"
         ."    <TD>Profile Name"
         ." </TR>");
 
    $color="color2";
    foreach ($profiles as $pid) {
      if ($user->is_user_admin($pid)) {
        $prf = new profile($pid);

        echo ("<TR class='line ". $color ."'>");
        if ($color == "color1") {
          $color = "color2";
        }
        else {
          $color = "color1";
        }
        if ($the_user->is_user_admin($prf->id)) {
          echo ("<TD><input type='checkbox' name='useradmin[". $prf->id ."]' checked>"); 
        }
        else {
          echo ("<TD><input type='checkbox' name='useradmin[". $prf->id ."]'>");
        }
        echo ("<TD>". $prf->name ."</TR>");
      }
    }
    echo ("</TABLE>\n</form>\n</DIV>\n");
  }
  print_footer();
?>
