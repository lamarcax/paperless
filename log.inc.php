<?
  /*
    The log function, every page can call this to write to the log

    log_action

    array of log code => log number  
    array of log number => log code 
  */
 
  require_once('config.inc.php');
  require_once('db.inc.php');
  require_once('auth.inc.php');

  $log_code = array("login" => 01, 
                    "logout" => 02, 

                    "subscribe" => 10, 
                    "unsubscribe" => 11, 
                    "removed_all_subscriptions" => 12,

                    "log_change" => 20,

                    "new_document" => 30,
                    "change_document" => 31,
                    "removed_document" => 32,
                    "move_document" => 33,
                    "download_document" => 34,
                    "unpacking" => 35,

                    "new_revision" => 41,
                    "removed_all_revisions" => 42,

                    "new_keyword" => 51,
                    "delete_keyword" => 52,
                    "change_keyword" => 53,
                    "removed_all_keywords" => 54,

                    "create_new_user" => 61,
                    "edit_user" => 62,
                    "delete_user" => 63,
                    "add_user_to_profile" => 64,
                    "remove_user_from_profile" => 65,
                    "resetpass" => 66,

                    "create_new_profile" => 71,
                    "edit_profile" => 72,
                    "delete_profile" => 73,

                    "delete_folder" => 80,
                    "move_folder" => 81);

  $code_log = array();

  foreach ($log_code as $key => $value) {
    $code_log[$value] = $key;
  } 

  function log_action($log_code, $desc) {
    global $user;
    
    $query = "INSERT INTO log (user_id, timestamp, log_code,`desc`)"
            ." VALUES ('". $user->login ."(". $user->id .")', NOW() , '". $log_code ."', '". $desc ."' )";

    $res = mysql_query($query)
      or die("Invalid query: " . mysql_error());
    

  }
?>
