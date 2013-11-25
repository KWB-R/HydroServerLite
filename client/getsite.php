<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

require_once 'db_config.php';

$varid=$_GET['varid'];

$select = "SELECT * FROM sites WHERE SiteID='$varid'";

$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );

$fields = mysql_num_fields ( $export );

$data="";

while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = ",";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . ",";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );

if ( $data == "" )
{
    //$data = "\n(0) Records Found!\n";  
	$data = "\n(0) " + $RecordsFound  + "\n";                      
}

echo($data);
?>