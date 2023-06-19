<?
  /*
    user_perms.php
      handles userpermission operations
  */

  require_once('config.inc.php');
  require_once('db.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $uid = $_POST['user_id'];
  $useradmin = $_POST['useradmin'];

  $the_user = new user($uid);
  if ($the_user->is_god) {
    $profiles = array(1);
  }
  else {
    $profiles = $the_user->get_profiles();
  }
  if (!empty($profiles)) {
    foreach ($profiles as $pid) {
      if (isset($useradmin[$pid])) {
        $ua = 1;
      }
      else {
        $ua = 0;
      }
      $query = "UPDATE user_profile"
              ." SET useradmin='". $ua ."'"
              ." WHERE user_id='". $the_user->id ."' AND profile_id='". $pid ."'";
      mysql_query($query)
        or die ("Invalid statement: ". $query ." -> ". mysql_error());
    }
  }
  header("Location: /user_perms_itf.php?user_id=". $the_user->id);
?>
