<?php
/**
 * smplPDO - Mysql Database Helper Class by Conrad Warhol
 * Please refer to documentation for detailed information on methods and code examples.
 *
 * @link http://codecanyon.net/item/smplpdo-mysql-database-helper-class/3359730
 */
 
// Include class file in your script.
ini_set('mysql.connect_timeout', 300);


ini_set('default_socket_timeout', 300);
//date_default_timezone_set('Asia/Taipei');
function getMicrotime()
{
    list($usec, $sec) = explode(' ', microtime());
	$this_runtime = (double)$usec + (double)$sec;
    //return ((double)$usec + (double)$sec);
	return $this_runtime;
	//return number_format($this_runtime, 5);
}
$start = getMicrotime();

function getRuntime($start)
{
$end = getMicrotime();
$run_time = $end-$start;
$run_time = number_format($run_time, 4);
return 'Runtime:' . $run_time. '';
}



include('/home/www/saba_id/saba/Connections/smplPDO.php');

$website_db_host = '35.201.169.116';
$website_db_name = 'saba_id';
$website_db_user = 'saba';
$website_db_pass = 'ilovesaba';

// init the class same as PDO

$website_db = @new smplPDO( "mysql:host=$website_db_host;dbname=$website_db_name", $website_db_user, $website_db_pass );

/*
$saba_cn_db_name = 'saba_cn';
$saba_en_db_name = 'saba_en';
$saba_th_db_name = 'saba_th';
$saba_in_db_name = 'saba_in';
$saba_id_db_name = 'saba_id';


$saba_db = @new smplPDO( "mysql:host=$website_db_host;dbname=$saba_id_db_name", $website_db_user, $website_db_pass );

*/



if (!function_exists('d')){
function d($d){
        echo '<pre>';
        print_r($d);
        echo '</pre>';
}
}


$page_uri = $_SERVER['REQUEST_URI'];
$page_script_name = $_SERVER['SCRIPT_NAME'];


session_start();



//d($_SERVER);

/*
$bz_browser = get_browser(null, true);
$this_browser = $bz_browser['browser'];
*/
/*

// INSERT, UPDATE, DELETE with prepared statements:
$event_db->insert( 'table', array( 'name'=>'John Doe', 'age'=>28 ) );
$event_db->update( 'table', array( 'age'=>29 ), array( 'name'=>'John Doe' ) );
$event_db->delete( 'table', array( 'name'=>'John Doe' ) );

// SELECT all, single row, single column and single variable:
$event_db->get_all( 'table', array( 'age'=>22 ) );
$event_db->get_row( 'table', array( 'name'=>'John Doe' ), array( 'name', 'email' ) );
$event_db->get_col( 'table', array( 'age'=>28 ), 'name' );
$event_db->get_var( 'table', array( 'name'=>'John Doe' ) );

// Check if a record exists: 
if( $event_db->exists( 'table', array( 'name'=>'John Doe' ) ) ) echo 'Record exists!';

// Get the count of matching records:
$event_db->get_count( 'table', array( 'age'=>22 ) );

// Debug:
$event_db->sql; // Holds the SQL query executed.
$event_db->bind; // Holds the bind parameters of a Prepared Statement.
$event_db->insert_id; // Holds the ID of last inserted row.
$event_db->num_rows; // Holds the number of rows affected by last query.
$event_db->result; // Holds the result of the last query executed.
*/
//$event_db->debug(); // Print out all necessary properties.

