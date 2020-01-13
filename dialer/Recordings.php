<?php
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Call Recordings</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<link href="/img/favicon.ico" rel="icon" type="image/x-icon" />
</head>
<body>
<?php include("../includes/navbar.html"); ?>
  <div class="container">
      <br>
<div class="card">
<h3 class="card-header">
<i class="fa fa-headphones"></i> Call Recordings <a href='Recordings.php'><button type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-history"></i> New Search...</button></a>
</h3>
<div class="card-block">

<p class="card-text">      


<h4>Search by number, use leading zero e.g. 01792862744 </h4>

<form action="Recordings.php?EXECUTE=1" method="post" id="searchform" autocomplete="off"> 
    <div class="form-group">
      <div class="col-xs-2">
<input type="tel" class="form-control" name="PHONE_NUMBER" required pattern=".{11}|.{11,11}" maxlength="11" placeholder="07446301726" value="<?php echo $_POST['PHONE_NUMBER'] ?>">
</div>
</div>
 


    <div class="form-group">
      <div class="col-xs-2">
          <div class="btn-group">
          <button type="submit" name="submit" value="Search" data-toggle="modal" data-target="#processing-modal" class="btn btn-info btn-sm"><i class="fa  fa-search"></i> SEARCH</button>
          </div>
      </div>
</div>
</form>
<br>

<?php

if(isset($EXECUTE)){
include("../includes/DIALLER_PDO_CON.php"); 

$PHONE_NUMBER = filter_input(INPUT_POST, 'PHONE_NUMBER', FILTER_SANITIZE_NUMBER_INT);    
    
$RM_ZERO = ltrim($PHONE_NUMBER, '0');
$newlocationvar = "$RM_ZERO";
$four_phone = "44$RM_ZERO";

    
$query = $TRB_DB_PDO->prepare("SELECT lead_id,call_date,status from vicidial_log where phone_number=:nozero OR phone_number=:four OR phone_number=:zero group by lead_id ORDER BY call_date DESC");
$query->bindParam(':nozero', $newlocationvar, PDO::PARAM_INT);
$query->bindParam(':four', $four_phone, PDO::PARAM_INT);
$query->bindParam(':zero', $PHONE_NUMBER, PDO::PARAM_INT);


echo "<table align=\"center\" class=\"table\">";

echo   
    "<thead>
    <tr>
    <th colspan= 11>Searched for $PHONE_NUMBER</th>
    </tr>
    <tr>
    <th>Lead ID</td>
    <th>Call Date</th>
    <th>Status</th>
    </tr>
    </thead>";

$query->execute();
if ($query->rowCount()>0) {
while ($result=$query->fetch(PDO::FETCH_ASSOC)){

$lead_id  =$result['lead_id'];
$call_date=$result['call_date'];
$status=$result['status'];

echo"<tr>
  <td><a href='https://trb.bluetelecoms.com/vicidial/admin_modify_lead.php?lead_id=$lead_id&archive_search=No&archive_log=0' target='_blank'>$lead_id</a></td>  
      <td>$call_date</td>  
          <td>$status</td> 
</tr>";

}
} else {
    echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No recordings found for  $PHONE_NUMBER/$RM_ZERO</div>";
}
echo "</table>";
}

?>

<br>
</p>

</div>
    <div class="card-footer">
ADL
</div>
</div>
</div>

    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script type="text/javascript" language="javascript" src="/jquery/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" language="javascript" src="/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
