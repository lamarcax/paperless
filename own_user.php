<?
  /*
    own_user.php
    handles save request from own_user_itf.php
  */
  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');
  require_once('functions.inc.php');

  /*
     Start of the query
  */

  $query = "UPDATE user SET ";

  /*
    Build the update query from the post stream, checking required fields from the config.inc.php file.
  */

  foreach ($user_field as $key=>$value) {
    if ($value) {
      if ((isset($_POST[$key])) && ($_POST[$key] != "")) {
        $query = $query . $key . " = '" . $_POST[$key] . "', ";
      }
      else {
        $errormsg .= "Field '". $key ."' should not be empty.<BR>";
      } 
    }
    else {
      if (isset($_POST[$key])) {
        $query = $query . $key . " = '" . $_POST[$key] . "', ";
      }
    }
  }
  if (!empty($errormsg)) {
    $ownuser = $_POST;
    $ownuser['errormsg'] = $errormsg;
    $_SESSION['ownuser'] = $ownuser;
    header("Location: own_user_itf.php");
    exit;
  }

  /*
    Changing the password
  */

  if ($_POST['change'] == 'change') {
    if ($_POST['new_pass'] == $_POST['confirm']) {
      $query = $query . " pass = PASSWORD('" . $_POST['new_pass'] . "')";
      $query = $query . " WHERE id = " . $user->id . " AND pass = PASSWORD('" . $_POST['old_pass'] .  "')";
    }
    else {
      $ownuser = $_POST;
      $ownuser['errormsg'] = "New password and confirm new password should be the same.";
      $_SESSION['ownuser'] = $ownuser;
      header("Location: own_user_itf.php");
      exit;
    }
  }
  else
  {
    $query .= "WHERE id = " . $user->id;
    $query = str_replace(", WHERE", " WHERE", $query); 
  }

  mysql_query($query)
     or die ("Invalid query : " . $query . " -> " . mysql_error());

  if (mysql_affected_rows() == 0) {
    $ownuser = $_POST;
    $ownuser['errormsg'] = "Incorrect password.";
    $_SESSION['ownuser'] = $ownuser;
    header("Location: own_user_itf.php");
    exit;
  }

  header("Location: own_user_itf.php");

?>
