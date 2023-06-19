<?
  /*
    own_user_itf.php
    Page with all the information from your account.
      username . label
      full name . textbox
      e-mail . textbox
      phone . textbox
      mobile . textbox
      old password . textbox
      new password . textbox
      confirm new password - textbox
      save changes . button
  */
  

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('functions.inc.php');

  $the_user = $user;

  if (!empty($_SESSION['ownuser'])) {
    $ownuser = $_SESSION['ownuser'];
    $profile_id = $ownuser['profile_id'];
    $errormsg = $ownuser['errormsg'];
    $the_user->name = $ownuser['name'];
    $the_user->email = $ownuser['email'];
    $the_user->phone = $ownuser['phone'];
    $the_user->mobile = $ownuser['mobile'];
    $the_user->organisation = $ownuser['organisation'];
    $the_user->position = $ownuser['position'];

    $_SESSION['ownuser'] = NULL;
    unset($_SESSION['ownuser']);
    session_unregister($ownuser);
  }


  /*
    Starting the page
  */

  print_header("Own user account");
  print_site_header("Own user account","own_user");

  /*
    Display page information
  */

  echo ("<DIV class='userbox'>\n");
  echo ($errormsg);
  echo ("  <DIV class='userrow'>\n"
       ."    <SPAN class='label'>\n"
       ."      Login:\n"
       ."    </SPAN>\n"
       ."    <SPAN class='field'>\n"
       . $the_user->login
       ."    </SPAN>\n"
       ."  </DIV>\n");
  echo ("  <FORM action='own_user.php' method='post'>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[name]) {
         echo "*";
       }
  echo ("        Name:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='name' value='" . $the_user->name . "' size=64 MAXLENGTH=63><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[email]) {
         echo "*";
       }
  echo ("        E-Mail:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='email' value='" . $the_user->email . "' size=64 MAXLENGTH=63><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[phone]) {
         echo "*";
       }
  echo ("        Phone:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='phone' value='" . $the_user->phone . "' size=16 MAXLENGTH=15><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[mobile]) {
         echo "*";
       }
  echo ("        Mobile:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='mobile' value='" . $the_user->mobile . "' size=16 MAXLENGTH=15><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[organisation]) {
         echo "*";
       }
  echo ("        Organisation:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='organisation' value='" . $the_user->organisation . "' size=64 MAXLENGTH=63><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n");
       if ($user_field[position]) {
         echo "*";
       }
  echo ("        Position:\n"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='text' name='position' value='" . $the_user->position . "' size=64 MAXLENGTH=63><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"

       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='checkbox' name='change' value='change'>Change Password<BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"

       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n"
       ."        Old Password:"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='password' name='old_pass' size=16 MAXLENGTH=15><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"

       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n"
       ."        New Password:"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='password' name='new_pass' size=16 MAXLENGTH=15><BR>\n"
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='label'>\n"
       ."        Confirm New Password:"
       ."      </SPAN>\n"
       ."      <SPAN class='field'>\n"
       ."        <input type='password' name='confirm' size=16 MAXLENGTH=15><BR>\n" 
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."    <DIV class='userrow'>\n"
       ."      <SPAN class='field'>\n"
       ."        <input class='color' type='submit' value='Save Changes'>\n" 
       ."        <input class='color' type='reset' value='Reset'>\n "
       ."      </SPAN>\n"
       ."    </DIV>\n"
       ."  </FORM>\n" 
       ."</DIV>\n");
  
  print_site_footer();
  print_footer();

?>
