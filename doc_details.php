<?
/*
   Receives information from a document and writes it to the database
   Receives information from the interface and removes it from the database
*/

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');
  require_once('functions.inc.php');

  /*
    Save a excisting document to the database
  */

  function save_document($doc_id, $query_a) {
    $query = "UPDATE document SET ";

    foreach($query_a as $key => $value) {
     $query .= " `$key` = '$value' , ";
    }

    $query .=  "where id=$_POST[doc_id]";
    $query = str_replace(", where"," where",$query);
    $query = str_replace("'#"," ",$query);
    $query = str_replace("#'"," ",$query);

    $res = mysql_query($query)
      or die("Invalid query: " . mysql_error() . " query : $query");
  }

/*
  Retrieve the information from the post stream
*/

 $action = $_POST[action];
 $doc_id = $_POST[doc_id];
 $source = $_POST[source];

/*
  Creating the query array and cleaning this array, adding automatic values
*/

 $query_a = $_POST;
 unset($query_a[action]);
 unset($query_a[doc_id]);
 unset($query_a[source]);

 $query_a["modified"] = '# NOW()#'; 

/*
  Checking against configuration file
*/
 if ($automatic_maintainer) {
   $query_a["maintainer_id"] = $user->id;
 }

/*
  Saving the document
*/
 save_document($doc_id, $query_a);

/*
  Redirect to doc_details.php
*/

 $_SESSION["doc_id"] = $doc_id;
 header("Location:". substr($source,1));


?>
