<?
  /*
    List of the logs with search options (default all), sorted in a certain order.
      username
      code
      description
      timestamp

     also a  search textfield/button, to search through the logs for a specific users, code, ...

     TO DO : logging the log access
	     keeping prev search string in search box
  */

  
    require_once('config.inc.php');
    require_once('auth.inc.php');
    require_once('db.inc.php');
    require_once('classes.inc.php');
    require_once('functions.inc.php');
    require_once('log.inc.php');

    /*
      Javascript to control the form
    */

    $javascript = " <SCRIPT language='JavaScript'>\n"
                 ." <!--\n"
                 ."  function click(order, direction) {\n"
                 ."   document.logsearch_frm.order.value = order;\n"
                 ."   document.logsearch_frm.direction.value = direction;\n"
                 ."   document.logsearch_frm.submit();\n"
                 ."  }\n"
                 ."//-->\n"
                 ."</SCRIPT>\n";


    print_header("Log file",$javascript);
    print_site_header("Log","log");
   

    /*
      reading variable from the post stream for the order system
    */ 

    $from=$_POST[from];
    if (!isset($from)) {
      $from="0";
    }

    $show=$_POST[show];
    if (!isset($show)) {
      $show="30";
    }

    $order=$_POST[order];    
    if (!isset($order)) { 
      $order="id";
    } 

    $direction=$_POST[direction];
    if (!isset($direction)) {
      $direction="desc";
    }
    
    /*
      reading variables from the post stream for the search system
    */

    $id_search=$_POST[id_search];
    if (empty($id_search)) {
      $id_search="";
    }
    else {
      //delimit id, maybe
      $id_search = " AND id LIKE '%$id_search%' ";
    }  

    $login_id_search=$_POST[login_id_search];
    if (empty($login_id_search)) {
      $login_id_search="";
    }
    else {
      //delimit id, maybe
      $login_id_search = " AND user_id LIKE '%$login_id_search%' ";
    }

    $timestamp_search=$_POST[timestamp_search];
    if (empty($timestamp_search)) {
      $timestamp_search="";
    }
    else {
      //delimit id, maybe
      $timestamp_search = " AND timestamp LIKE '%$timestamp_search%' ";
    }

    $log_code_search=$_POST[log_code_search];
    if (empty($log_code_search)) {
      $log_code_search="";
    }
    else {
      //delimit id, maybe
      $log_code_search = " AND log_code LIKE '%$log_code_search%' ";
    }

    $desc_search=$_POST[desc_search];
    if (empty($desc_search)) {
      $desc_search="";
    }
    else {
      //delimit id, maybe
      $desc_search = " AND `desc` LIKE '%$desc_search%' ";
    }
 
    /*
      Determine the next direction for the select
    */

    if ($direction == "asc") {
      $next_direction = "desc";
    } 
    else { 
      $next_direction = "asc";
    }

    /*
      Form for ordering     
    */ 

    echo "<form name='logsearch_frm' action='log.php' method='post'>\n";
    echo "<input name='order' type='hidden' value='$order'>\n";
    echo "<input name='direction' type='hidden' value='$direction'\n>";
    echo "<input name='from' type='textfield' value='$from'>\n";
    echo "<input name='show' type='textfield' value='$show'\n>";
    echo "<input type='submit' value='show'>\n";

    /*
      Checking which search parameter is being used and reversing it's next direction
    */

    $href_js = array("id" => "asc", 
                "user_id" => "asc",
              "timestamp" => "asc",
               "log_code" => "asc");

    if ($order == "id") {
      $href_js["id"] = $next_direction; 
    }
    elseif ($order == "user_id") {
      $href_js["user_id"] = $next_direction; 
    }
    elseif ($order == "timestamp") {
      $href_js["timestamp"] = $next_direction;
    }
    else {
      $href_js["log_code"] = $next_direction;
    }

    echo "<DIV>";

    /*
      Table with the content of the log
    */

    echo "<TABLE class='log'>\n";

    /*
      Printing the search form
    */

    echo "<TR><TD><input name='id_search' type='textfield' size='9' value=''>\n";
    echo "<TD><input name='login_id_search' type='textfield' size='9'  value=''>\n";
    echo "<TD><input name='timestamp_search' type='textfield' size='25' value=''>\n";
    echo "<TD><input name='log_code_search' type='textfield'  size='9'  value=''>\n";
    echo "<TD><input name='desc_search' type='textfield' size='82' value=''></TR>\n</FORM>\n";

    /*
      Printing the table header
    */

    echo "<TR><TD class='header'><A href=\"javascript:click('id','".$href_js["id"]."')\">Id</A>\n";
    echo "<TD class='header'><A href=\"javascript:click('user_id','".$href_js["user_id"]."')\">User Id</A>\n";
    echo "<TD class='header'><A href=\"javascript:click('timestamp','".$href_js["timestamp"]."')\">Timestamp</A>\n";
    echo "<TD class='header'><A href=\"javascript:click('log_code','".$href_js["log_code"]."')\">Log Code</A>\n";
    echo "<TD class='header'>Description</TR>\n";

    /*
      Creating the query and querying the mySQL Server
    */

    $query = "SELECT id FROM log where 1 $id_search $login_id_search $timestamp_search $log_code_search $desc_search order by $order $direction LIMIT $from, $show";

    $res = mysql_query($query)
      or die("Invalid query: " . mysql_error());
 
    /*
      Looping to display all log entries     
    */

    while ($row = mysql_fetch_array($res)) {
      if ($current_style == "line1") { 
        $current_style = "line2";
      } else { 
        $current_style = "line1";
      }

      $log = new log($row[id]);

      echo ("<TR><TD class='$current_style'>$log->id\n");
      echo ("<TD class='$current_style'>$log->user_id\n");
      echo ("<TD class='$current_style'>". $log->timestamp ."\n");
      echo ("<TD class='$current_style'>$log->log_code\n");
      echo ("<TD class='$current_style'> $log->desc </TR>\n");
    }
 
    if ((mysql_num_rows($res) == 0) || (!isset($res))) {
      echo ("<TR> <TD class='line1' colspan ='5'> no log entries found </TR>");
    }

    mysql_free_result($res);

    echo "</DIV></TABLE>\n";

    /*
      Ending of the site
    */

    print_site_footer();
    print_footer();
?>
