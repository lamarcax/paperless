<?

    require_once('config.inc.php');
    require_once('auth.inc.php');
    require_once('db.inc.php');
    require_once('classes.inc.php');
    require_once('functions.inc.php');

    $help_file = $_GET['item'];

    print_header("Help",$listscript);

    print_site_header("Help","help");

    include('help/'. $help_file .'.hlp');
    
    print_site_footer(false);

    print_footer();

?>
