<?php
/*
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 * 
 * Copyright © 2018 ADL CRM All rights reserved.
 * 
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 * 
 * Proprietary and confidential
 * 
 * Written by Michael Owen <michael@adl-crm.uk>, 2018
 * 
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  DataTables - https://github.com/DataTables/DataTables
 *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
 *  PHPMailer - https://github.com/PHPMailer/PHPMailer
 *  ClockPicker - https://github.com/weareoutman/clockpicker
 *  fpdf17 - http://www.fpdf.org
 *  summernote - https://github.com/summernote/summernote
 *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
 *  Bootstrap - https://github.com/twbs/bootstrap
 *  jQuery UI - https://github.com/jquery/jquery-ui
 *  Google Dev Tools - https://developers.google.com
 *  Twitter API - https://developer.twitter.com
 *  Webshim - https://github.com/aFarkas/webshim/releases/latest
 * 
*/

define("BASE_URL", (filter_input(INPUT_SERVER,'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Real Time Report</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="/Wallboards/css/Connex.css" type="text/css" />
<script type="text/javascript" language="javascript" src="/jquery/jquery-3.0.0.min.js"></script>
<script>
    function refresh_div() {
        jQuery.ajax({
            url:' ',
            type:'POST',
            success:function(results) {
                jQuery(".container").html(results);
            }
        });
    }

    t = setInterval(refresh_div,10000);
</script>
</head>
<body>
    <div class="container">
<?php

require_once(BASE_URL.'/includes/CONNEX_PDO_CON.php'); 

$lead_for_query = $pdo->prepare("SELECT 
    campaign_id, 
    lead_id, 
    phone_number, 
    status
FROM
    inbound_log
WHERE
    DATE(call_date) >= CURDATE()
        AND status = 'QUEUE'
        AND campaign_id='SAXfer'
");

        $lead_for_query->execute();
        if ($lead_for_query->rowCount() > 0) {
            
            echo "<table id='main2' border='1' align=\"center\" cellspacing=\"5\">";
            
            while ($result = $lead_for_query->fetch(PDO::FETCH_ASSOC)) {

                echo '<tr class="status_LEAD">';
                if($result['campaign_id'] == 'SAXfer') {
                echo "<td>SA TRANSFER WAITING</td>";    
                } else {
                echo "<td>Lead FOR " . $result['campaign_id'] . "</td>";
                }
                echo "</tr>";

                echo "</table>";
            }
        }        
        
        //END OF LEAD FOR CLOSER
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
 
// get DOM from URL or file
$html = file_get_html('http://10.26.114.7/wallboards/LifeScore.php');



// find all div tags with id=gbar
foreach($html->find('div[class=container]') as $e) {
    echo $e->innertext . '<br>';
    
}

foreach($html->find('div#container') as $e) {
    echo $e->innertext . '<br>';
// extract text from HTML
    
}

?>
</div>
</body>
</html>