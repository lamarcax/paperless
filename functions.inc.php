<?
/*
   Keyword functions are imported in the general function list
*/

  require_once('functions/keyword.inc.php');

/*
   Folder functions are imported in the general function list
*/

  require_once('functions/folder.inc.php');

/*
   Framework functions are imprted in the general function list
*/

  require_once('functions/framework.inc.php');
 
/*
   Other functions are imported in the general function list
*/

  require_once('functions/other.inc.php');
 
/*
   Permission functions are imported in the general function list
*/

  require_once('functions/permission.inc.php');

/*
   Save functions are imported in the general function list
*/

  require_once('functions/save.inc.php');

/*
   List functions are imported in the general function list
*/

  require_once('functions/list.inc.php');

// Old function, delete in a few versions
/*
  redirect($target, $array)
    generating and submitting a htmlpage with a hidden form, that posts variables to another page
    params :
      $target : action to be performed by submitted form (page to redirect to)
      $array : array of keys and values, keys are the variables to be posted,
               values are their values

  function redirect($target, $array="") {
    /*
      from array to list of hidden fields in form
    \/

    $extra = "";
    if ($array != "") {
      foreach($array as $key => $value) {
        $extra .= "<input type='hidden' name='" . $key . "' value='" . $value . "'>\n";
      }
    }

    /*
      Print form and redirect javascript code
    \/
    print_header();
  
    echo ("<form name='redirect_frm' action='" . $target . "' method='post'>"
         . $extra . "</form>"
         ."<script language='javascript'>\n"
         ."<!--\n"
         ." {\n"
         ."   document.redirect_frm.submit();\n"
         ." }\n"
         ."//-->\n"
         ."</script>\n");

    print_footer();
  }
*/

?>
