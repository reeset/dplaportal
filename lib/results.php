<?
  //replace {query_terms}
  //replace {page} 
  if (isset($_GET["q"])) {
  $query_string = $_GET["q"];
  $page = 1;
 
  if (isset($_GET["pg"])) { $page=$_GET["pg"];}

  $query_uri = $dpla_pattern;
  $query_uri = str_replace("{query_terms}", urlencode($query_string), $query_uri);
  $query_uri = str_replace("{page}", $page, $query_uri);

  $response = file_get_contents($query_uri);
  $json_response = json_decode($response, true);
  $row_count = 0;

  if (isset($_GET["debug"])) {
?>
<pre>
<? echo print_r($json_response);?>
</pre>
<?
 exit;
}
?>

<h2>Results: </h2>Term: <? echo htmlspecialchars($_GET["q"]);?><br />
<strong>Found: </strong><? echo $json_response["count"];?> </strong>
<hr />
<table width="85%" id="results">
<?
   foreach ($json_response["docs"] as $item) {
   if ($row_count == 0) {  
?>
<tr id="norm">
<?
     $row_count = 1;
   } else {
?>
<tr id="alt">
<?
       $row_count = 0;
   }
?>

   <td valign="top">
<?
   if (!isset($item["object"])) {
?>
     <img src="http://dp.la/assets/icon-text-e41d809d5ffcea531e88accd7e677adc.gif" width="128px" />
<?
   } else {
?>
    <img src="<? echo $item["object"];?>" width="128px" />
<?
    }
?>
   <br />
   <a href="<? echo $item["isShownAt"];?>" target="_blank">View Object</a>
   </td>
   <td valign="top">
<?
     $item_title = $item["sourceResource"]["title"];
     $item_description = $item["sourceResource"]["description"];

     $i_title = "";
     $i_description = "";

     if (is_array($item_title)) {
	$i_title = $item_title[0];
     } else {
        $i_title = $item_title;
     }

     if (is_array($item_description)) {
        $i_description = $item_description[0];
     }  else {
        $i_description = $item_description;
     }       


     if (strlen($i_title) > 255) {
       $i_title = substr($i_title, 0, 255) . "....";
     }

     if (strlen($i_description) > 255) {
       $i_description = substr($i_description, 0, 255) . "....";
     }
?>
     <a href="http://dplaohio-portal.reeset.net/item.php?id=<? echo $item["id"]; ?>"><? echo $i_title;?></a><br />
     <i><? echo $i_description;?></i>                 
     <br />
   </td> 
 </tr>
<?
}
?>
</table>
<br />
<div id="pagination">
<?
  if ($json_response["count"] > 25) {
    $page_num = intval($json_response["count"]/25);
    for ($x=0; $x<$page_num;$x++) {
      echo "<a href=\"http://dplaohio-portal.reeset.net/index.php?q=" . $_GET["q"] . "&pg=" . strval($x+1) . '">' . strval($x+1) . '</a> | '; 
    }
    if (($json_response["count"] % 25) > 0) {
      echo "<a href=\"http://dplaohio-portal.reeset.net/index.php?q=" . $_GET["q"] . "&pg=" . ($page_num+1) . '">' . ($page_num+1) . '</a>';
    }
  }
?>
     
</div>

<?
}
?>
