<?
  /*
    advanced_search_itf.php
      Provides an interface to search for documents with advanced options
  */
  require_once('auth.inc.php');
  require_once('functions.inc.php');
  print_header("Advanced Search");
  print_site_header("Advanced Search","search");

    echo ("<DIV class='searchbox'>\n"
         ."<form name='search_frm' action='search.php' method='post'>\n"
         ." <input type='hidden' name='advanced' value='yes'>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='label'>\n"
         ." <select name='mode'>\n"
         ."  <option value='2'>Keywords containing :</option>\n"
         ."  <option value='1'>Match any words :</option>\n"
         ."  <option value='0'>Match all words :</option>\n"
         ." </select>\n"
         ."</span>\n"
         ."<span class='field'>\n"
         ." <input type='textfield' size='50' name='searchstring'>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='label'>\n"
         ." Document Name:"
         ."</span>\n"
         ."<span class='field'>\n"
         ."<input type='textfield' size='50' name='docname'><BR>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='label'>\n"
         ." Document Description:"
         ."</span>\n"
         ."<span class='field'>\n"
         ."<input type='textfield' size='50' name='docdesc'><BR>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='label'>\n"
         ." Mime Type:"
         ."</span>\n"
         ."<span class='field'>\n"
         ."<input type='textfield' size='50' name='doctype'><BR>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='field'>\n"
         ." Date of last modification<BR>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='label'>\n"
         ." Month:"
         ."</span>\n"
         ."<span class='field'>\n"
         ."<input type='textfield' size='2' maxsize='2' name='month'> \n"
         ." Day: <input type='textfield' size='2' maxsize='2' name='day'> \n"
         ." Year: <input type='textfield' size='4' maxsize='4' name='year'><BR>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."<DIV class='searchrow'>\n"
         ."<span class='field'>\n"
         ." <input class='color' type='submit' value='Search'>\n"
         ." <input class='color' type='reset' value='Clear Fields'>\n"
         ."</span>\n"
         ."</DIV>\n"
         ."</form>\n"
         ."</DIV>\n");

  print_site_footer(false);
  print_footer();
?>
