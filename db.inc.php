<? 
    /*
       Connects to the database and provides a function to test the connection
    */

    require_once('config.inc.php');
    
    $sql = mysql_connect($cfg['server'],$cfg['user'],$cfg['pass']) or die(mysql_error());
    mysql_select_db($cfg['db'],$sql);
    
    function test_DB() {
      global $sql;
      $res = mysql_get_server_info($sql);
      echo ("<div id='dbtest'>MySQL server version: ". $res ."</div>\n");
    }
?>
