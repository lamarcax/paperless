<?
    /*
	auth.inc.php
	Authorisation code, to be used in every other php
    */

    require_once('classes.inc.php');

    session_start();

    if (empty($_SESSION["login"]) || empty($_SESSION["id"])) {
      header("Location: login.php");
      exit;
    }

    /*
      Creates the current user
    */
    
    $user = new user($_SESSION["id"]);
?>
