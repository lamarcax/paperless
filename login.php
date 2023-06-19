<?
  /*
     Generates a webpage with a form to input username and password.

     This form is processed by index.php to create the session for your visite.
  */

    require_once('functions.inc.php');

    print_header("login");
?>  

<SCRIPT LANGUAGE = "JavaScript">
  <!--
    if (parent.location.href != window.location.href) parent.location.href = window.location.href;
  // -->
</SCRIPT>

<div id="content">
  <div id="content">
    <img src='./pix/logo.jpg' height=300px width=400px>
  </div>
  <div id="formbox">
    <form action='index.php' method='post'>
      <div class='formrow'>
        <span class='label'>
          <b>Username:</b>
        </span>
        <span class='field'>
          <input type='text' name='login' size='12'>
        </span>
      </div>
      <div class='formrow'>
        <span class='label'>
          <b>Password:</b>
        </span>
        <span class='field'>
          <input type='password' name='pass' size='12'>
        </span>
      </div>
      <div class='formrow'>
        <input class='color' type='Submit' value='Login'>
        <input class='color' type='Reset' value='Reset'>
      </div>
    </form>
  </div>
</div>

<?
    print_footer();
?>
