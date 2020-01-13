<?php  
//PDOconblue.php
include('config.php');
   try {

$TRB_DB_PDO = new PDO('mysql:host='.$DB_TRB_SERVER.';dbname='.$DB_TRB_DATABASE, $DB_TRB_USER, $DB_TRB_PASS
#, array(    PDO::ATTR_PERSISTENT => true)
);

$TRB_DB_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//echo "Connected successfully";

   }
   
   catch(PDOException $e) {
       
       
       
       ?>
                      <div class="row">
                <div class="col-sm-12">
                    <strong><center><h1 style="color:red;"> Connection to dialler lost<br><?php echo "Connection failed: " . $e->getMessage(); ?> <i class="fa fa-exclamation"></i></h1></center></strong>
                </div>
      </div>
<?php
       
   }

?>
