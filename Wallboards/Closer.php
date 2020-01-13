<!DOCTYPE html>
<html lang="en">
<title>ADL | Real Time Report</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="/Wallboards/css/Connex.css" type="text/css" />
<script type="text/javascript" language="javascript" src="/jquery/jquery-3.0.0.min.js"></script>
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<script>
    function refresh_div() {
        jQuery.ajax({
            url:' ',
            type:'POST',
            success:function(results) {
                jQuery(".contain-to-grid").html(results);
            }
        });
    }

    t = setInterval(refresh_div,10000);
</script>
</head>
<body>
    <div class="contain-to-grid">
<?php

include('simple_html_dom.php');

$html = file_get_html('http://10.26.114.7/wallboards/functions/AllScore.php');


foreach($html->find('table') as $e)
    echo "<table id='main2' cellspacing='0' cellpadding='10'><td> $e->innertext </td></table>";

foreach($html->find('table#status_PAUSED12') as $e)
    echo "<table id='main2' cellspacing='0' cellpadding='10'><td> $e->innertext </td></table>";

$script = $html->find('script', 0);
echo  $html->plaintext;


?>
    </div>
</body>
</html>