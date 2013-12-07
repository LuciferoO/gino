<?php
//database configuration
$config['mysql_host'] = "localhost";
$config['mysql_user'] = "barclays_facebook";
$config['mysql_pass'] = "k0st0golov";
$config['db_name']    = "edf_facebook";
$config['table_name'] = "root";
 
//connect to host
mysql_connect($config['mysql_host'],$config['mysql_user'],$config['mysql_pass']);
//select database
@mysql_select_db($config['db_name']) or die( "Unable to select database");

/*$xml          = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n"; */
$root_element = $config['table_name']."s"; //fruits
$xml         .= "<$root_element>\r\n";

//select all items in table
$sql = "SELECT urn,creationdate,filename FROM userphoto order by creationdate desc limit 20";
 
if (!$result = mysql_query($sql))
   die("Query failed.");
 
if(mysql_num_rows($result)>0)
{
   while($result_array = mysql_fetch_assoc($result))
   {
      $xml .= "\t<".$config['table_name'].">\r\n";
 
      //loop through each key,value pair in row
      foreach($result_array as $key => $value)
      {
         //$key holds the table column name
         $xml .= "\t\t<$key>";
 
 
         //embed the SQL data in a CDATA element to avoid XML entity issues
         //$xml .= "<![CDATA[".trim(stripslashes($value))."]]>"; 
		 $xml .= trim(stripslashes($value)); 
 
         //and close the element
         $xml .= "</$key>\r\n";
      }
 
      $xml.="\t</".$config['table_name'].">\r\n";
   }
}

//close the root element
$xml .= "</$root_element>\r\n";
 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//send the xml header to the browser
header ("Content-Type:text/xml"); 
 
//output the XML data
echo trim($xml);
?>