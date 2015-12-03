<?
  if (isset($_GET["id"])) {
  $query_string = $_GET["id"];

  $query_uri = $dpla_item_pattern;
  $query_uri = str_replace("{item_id}", $query_string, $query_uri);

  $response = file_get_contents($query_uri);
  $json_response = json_decode($response, true);
  $doc = $json_response["docs"][0];

  if (isset($_GET["debug"])) {
?>
  <pre>
<? echo print_r($json_response);?>
  </pre>
<?
    exit;
  }
?>
  <h2><? echo $doc["sourceResource"]["title"]; ?> </h2>
  <br />

  <table width="85%">
  <tr>
   <td valign="top">
 <?
     if (!isset($doc["object"])) {
 ?>
     <img src="http://dp.la/assets/icon-text-e41d809d5ffcea531e88accd7e677adc.gif" width="128px" />
<?
    } else {
?>
    <img src="<? echo $doc["object"];?>" width="128px" />
<?
}
?>
     <br />
     <a href="<? echo $doc["isShownAt"];?>" target="_blank">View Object</a>
   </td>
   
   <td valign="top">
     <table>
      <tr>
       <td valign="top"><b>Created Date</b></td>
       <td valign="top"><? echo $doc["sourceResource"]["date"]["displayDate"];?></td>
      <tr>
      
      <tr>
       <td valign="top"><b>Provider</b></td>
       <td valign="top"><a href="<? echo $doc["provider"]["@id"];?>" target="_blank"><? echo $doc["provider"]["name"];?></a></td>
      </tr>

      <tr>
       <td valign="top"><b>Contributing Institution</b></td>
       <td valign="top"><? echo $doc["dataProvider"];?></td>
      </tr>
     </table>
    </td>
   </tr>

   <tr>
    <td valign="top" colspan="2">
    <b>Description</b><br />
<?
    if (is_array($doc["sourceResource"]["description"])) {
      echo join('<br />', $doc["sourceResource"]["description"]);
    } else {
      echo $doc["sourceResource"]["description"];
    }
?>
    </td>
   </tr>
   <tr>
    <td valign="top"><b>Location</b></td>
    <td valign="top"><? echo $doc["sourceResource"]["spatial"][0]["state"];?> </td>
   </tr>

   <tr>
    <td valign="top"><b>Subject(s)</b></td>
    <td valign="top">
<?
     $subject_bucket = "";
     foreach ($doc["sourceResource"]["subject"] as $subj) {
	$t_subject = join('<br />', $subj);
        $subject_bucket .= "<a href=\"http://dplaohio-portal.reeset.net/index.php?q=" . urlencode($t_subject) . '">' . $t_subject . "</a><br />";
     }

     echo $subject_bucket;

?>


    </td>
   </tr>


   <tr>
    <td valign="top"><b>Rights</b></td>
    <td valign="top"><? echo $doc["sourceResource"]["rights"];?></td>
   </tr>

   <tr>
    <td valign="top"><b>URL</b></td>
    <td valign="top"><a href="<? echo $doc["isShownAt"];?>" target="_blank"><? echo $doc["isShownAt"];?></a></td>
   </tr>
</table>


<?
}
?>
