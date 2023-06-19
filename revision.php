<?

/*
   The handler for revision_itf.php
   
     -> | Bringing the blobs from the database
        | Zipping the files for preparation of download
     -> Producing the download link
*/

  require_once('config.inc.php');
  require_once('auth.inc.php');
  require_once('db.inc.php');

  if (!empty($_POST["redirect"])) {
    $doc_id_a = $_POST["doc_id_a"];
    $revision_a = $_POST["revision_a"];
    $selected = $_POST["chk"];
    $redirect = $_POST["redirect"];
  }
  else {
    $doc_id_a = $_SESSION["doc_id_a"];
    $revision_a = $_SESSION["revision_a"];
    $selected = $_SESSION["chk"];
    $redirect = $_SESSION["redirect"];    

    session_unregister($doc_id_a);
    session_unregister($revision_a);
    session_unregister($chk);
    unset($_SESSION["doc_id_a"]);
    unset($_SESSION["revision_a"]);
    unset($_SESSION["chk"]);
  }

  if ((!empty($doc_id_a)) && (!empty($revision_a)) && (!empty($selected))) {

    /*
        We clean the directory for this revision
    */

    $cmd = "rm -R zip_dir/usr". $user->id . "/*";
 
    exec($cmd);

    /*
       Create a download_tupple object and get the query from it
    */

    $download_tuples = new download_tuples($selected, $doc_id_a, $revision_a);

    $query = $download_tuples->create_select();

    /*
       Run the query
    */

    $res = mysql_query($query)
      or die("Invalid query: ". mysql_error() ." query : ". $query);

    while ($row = mysql_fetch_array($res)) {
      $doc_id = $row[doc_id];
      $filename = $row[name];
      $file = $row[content];
      $type = $row[type];
      $size = $row[size];
      $revision = $row[revision];

      global $log_code;

      $doc = new document($doc_id,$user->id);
 
      $msg = "Download of Document \'". $doc->name . "(". $doc_id .")\' Revision \'". $revision ."\'";
 
      log_action($log_code["download_document"], $msg); 

      /*
         Store the file writing location
      */

      $path = "zip_dir/usr". $user->id ."/doc". $doc_id ."/rev". $revision;

      /*
         We make the directory for this revision
      */

      $cmd = "mkdir -m 777 -p ". $path;
      exec ($cmd);

      /*
         We write the blob to the file system
      */

      $handle = fopen($path ."/". $filename, "w");
      fwrite($handle, $file);
      fclose($handle);
    }

    if (mysql_num_rows($res) == 1) {

    }
    else {

      /*
         We zip the file in 1 pack file
      */
    
      $cmd = "/var/www/html/docmgr2/sdms/zip_script.sh zip_dir/usr" . $user->id ." ". $packfile;
      exec($cmd);

      /*
         We prepare to send the pack file to the user
      */

      $path = "zip_dir/usr". $user->id;
      $filename = "packfile.zip";
      $type = "application/x-zip-compressed";
      $size = filesize($path ."/". $filename);
    }

    header("Content-disposition:attachment; filename=\"". $filename ."\"");

    /* 
       The browsers do not touch octetstream : no opening in the browser. 
    */
    header("Content-Type: application/octetstream");
    header("Content-Encoding: $type");

//    header("Content-type: ". $type);
    header("Content-Length: ". (string)$size);
    header("Pragma: no-cache");
    header("Expires: 0");

    readfile($path ."/". $filename);

    /*
       We clean the directory for this revision
    */

   $cmd = "rm -R zip_dir/usr". $user->id;

   exec($cmd);
  }
  else {
    header("Location: ". $redirect);
  }
?>
