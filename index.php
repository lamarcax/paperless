<?
    /*
	Login handler
        Processes login requests.
        The variable are checked against the database values.
	If they are correct a php-session is created and you are redirected to home.php.
        If you are not logged in or have sent the wrong variable then you are redirected to login.php.
    */

    require('config.inc.php');
    require('db.inc.php');

    session_start();

    /*
      Authenticate given login parameters
    */

    if (isset($_POST["login"])) {

      $login_frm = $_POST["login"];
      $pass_frm = $_POST["pass"];

      $query = "SELECT id, login"
              ." FROM user"
              ." WHERE login = '"  .  $login_frm . "'"
              ."       AND"
              ."       pass = PASSWORD('" . $pass_frm . "')";

      $res = mysql_query($query)
        or die("Invalid query: " . mysql_error());
      $numrows = mysql_num_rows($res);
      if ((empty($numrows)) || ($numrows != 1)) {
        header("Location: login.php");
        exit;
      }
      $row = mysql_fetch_array($res);
      $id = $row[id];
      $login = $row[login];
   
      /*
        register the session
      */

      $_SESSION["id"] = $id;
      $_SESSION["login"] = $login;
      
      /*
        Logging to the log file
      */

      require_once('log.inc.php');
      log_action($log_code[login],"login - $login");

    }
    
    require_once('auth.inc.php');

    header("Location: home.php");
?>
