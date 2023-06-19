<?
  /*
    user_admin_itf.php
    shows an interface to manage users on the system (admin-function)
  */

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  require_once('db.inc.php');
  require_once('classes.inc.php');


  if ($user->is_user_admin()) {
    print_header();
    print_site_header("User Administration","user_admin");

    echo ("<div class='adminbox'><div class='profileadminbox'><iframe name='profile_frame' src='/profile_chooser_itf.php' frameborder=0 height=450 width='99%'></iframe></div>\n");
    echo ("<div class='useradminbox'><iframe name='detail_frame' src='/user_admin_details_itf.php' frameborder=0 height=450 width='99%'></iframe></div>&nbsp</div>\n");


    print_site_footer();
    print_footer();
  }
  else {
    header("Location: access_denied.php");
  }
?>
