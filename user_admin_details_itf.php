<?
  /*
    user_admin_details_itf.php
    Page with all the information from the selected account.
      username . label
      full name . textbox
      e-mail . textbox
      phone . textbox
      mobile . textbox
      reset password . button
      save changes . button
  */
  

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $user_id = $_GET['user_id'];
  $profile_id = $_GET['profile_id'];

  if ($user_id != "") {
    if ($user_id != "new") {
      $the_user = new user($user_id);
    }
    if (!empty($_SESSION['post'])) {
      $post = $_SESSION['post'];
      $profile_id = $post['profile_id'];
      $errormsg = $post['errormsg'];
      $the_user->login = $post['login'];
      $the_user->name = $post['name'];
      $the_user->email = $post['email'];
      $the_user->phone = $post['phone'];
      $the_user->mobile = $post['mobile'];
      $the_user->organisation = $post['organisation'];
      $the_user->position = $post['position'];

      $_SESSION['post'] = NULL;
      unset($_SESSION['post']);
      session_unregister($post);
    }
    /*
      Starting the page
    */
    $javascript = "<script language='javascript'>\n"
                 ."<!--\n"
                 ."  function resetpwd() {\n"
                 ."    document.user_frm.action.value = 'resetpwd';\n"
                 ."    document.user_frm.submit();\n"
                 ."  } \n"

                 ."  function cancel(prf) {\n"
                 ."    window.open(\"/profile_user_itf.php?profile_id=\" + prf, \"detail_frame\");\n"
                 ."    window.open(\"/profile_chooser_itf.php\", \"profile_frame\");\n"
                 ."  } \n"

                 ."//-->\n"
                 ."</script>";


    print_header("", $javascript);

    /*
      Display page information
    */

    echo ($errormsg
         ."<DIV class='userbox'>\n"
         ."<FORM name='user_frm' action='user_admin.php' method='post'>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         
         if ($user_id == "new") {
           echo ("* Login:\n"
                ."</SPAN>\n"
                ."<SPAN class='field'>\n"
                ." <input type='text' name='login' value='" . $the_user->login . "' size=16 MAXLENGTH=15>\n"
                ."</SPAN>\n");
         }
         else {
           echo ("Login:\n"
                ."</SPAN>\n"
                ."<SPAN class='field'>\n"
                ." <input type='hidden' name='login' value='". $the_user->login ."'>"
                . $the_user->login . "\n"
                ."</SPAN>\n");
         }
    echo ("</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[name]) {
           echo ("*");
         }
    echo ("  Name:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='name' value='" . $the_user->name . "' size=64 MAXLENGTH=63>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[email]) {
           echo ("*");
         }
    echo (" E-Mail:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='email' value='" . $the_user->email . "' size=64 MAXLENGTH=63>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[phone]) {
           echo ("*");
         }
    echo ("  Phone:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='phone' value='" . $the_user->phone . "' size=16 MAXLENGTH=15>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[mobile]) {
           echo ("*");
         }
    echo ("   Mobile:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='mobile' value='" . $the_user->mobile . "' size=16 MAXLENGTH=15>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[organisation]) {
           echo ("*");
         }
    echo ("   Organisation:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='organisation' value='" . $the_user->organisation . "' size=64 MAXLENGTH=63>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."<DIV class='userrow'>\n"
         ."<SPAN class='label'>\n");
         if ($user_field[position]) {
           echo ("*");
         }
    echo ("   Position:\n"
         ."</SPAN>\n"
         ."<SPAN class='field'>\n"
         ." <input type='text' name='position' value='" . $the_user->position . "' size=64 MAXLENGTH=63>\n"
         ."</SPAN>\n"
         ."</DIV>\n");


/*         if ((isset($the_user)) && ($the_user->is_user_admin($profile_id))) {
           echo "<input type='checkbox' name='useradmin' checked>User Admin<BR>\n"; 
         }
         else {
           echo "<input type='checkbox' name='useradmin'>User Admin<BR>\n";
         }
*/
    echo ("<DIV class='userrow'>\n"
         ." <SPAN class='field'>\n");
    if ($user_id != "new") {
      echo ("<input class='color' type='button' name='password' onClick='javascript:resetpwd()' value='Reset Password'> \n");
    }
    echo ("<input type='hidden' name='user_id' value='" . $user_id . "'>\n"
         ."<input type='hidden' name='profile_id' value='" . $profile_id . "'>\n"
         ."<input type='hidden' name='action' value='save'>\n"
         ."<input class='color' type='submit' value='Save'>\n"
         ."<input class='color' type='reset' value='Clear'>\n"
         ."<input class='color' type='button' onClick='cancel(". $profile_id .")' value='Cancel'>\n"
         ."</SPAN>\n"
         ."</DIV>\n"
         ."</FORM>\n"
         ."</DIV>");
          
    echo ("<DIV class='userrow'> \n"
         ."<iframe name='usr_prf_frame' src='/user_perms_itf.php?user_id=" . $the_user->id . "' frameborder='0' width='100%' height=130>"
         ."</DIV>");


    print_footer();
  }
  else {
    print_header();
    print_footer();
  }
?>
