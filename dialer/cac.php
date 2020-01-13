<?php
define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$dateTo = filter_input(INPUT_POST, 'dateTo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dateFrom = filter_input(INPUT_POST, 'dateFrom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$timeTo = filter_input(INPUT_POST, 'timeTo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$timeFrom = filter_input(INPUT_POST, 'timeFrom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dispo = filter_input(INPUT_POST, 'dispo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$callTime = filter_input(INPUT_POST, 'callTime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

$defaultDate = date('Y-m-d');

?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | CAC</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="/clockpicker-gh-pages/dist/jquery-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="/clockpicker-gh-pages/assets/css/github.min.css">
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<style>
    .clockpicker {
        z-index: 999999;
    }
    .clockpicker-popover {
        z-index: 999999;
    }
    .ui-datepicker {
        z-index: 1151 !important;
    }
</style>
</head>
<body>
<?php include("../includes/navbar.html"); ?>

<div class="container">
    <br>
    <div class="card">
        <h3 class="card-header">
            <i class="fa fa-line-chart"></i> CAC Report
        </h3>

        <div class="card-block">
            <p class="card-text">

            <div class="col-12">


                <div class="liveResults" id="liveResults">

                </div>

            </div>
            <br>
        </div>
        <div class="col-md-12">
            <div class="btn-group"></div>
            <button onclick="exportTableToCSV('cacReport.csv')" class="btn btn-success"><i class="fa fa-download"></i> Download</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#settingsModal"><i class="fa fa-cog"></i> Settings</button>
            <a class="btn btn-danger" href="agentPerformance.php"><i class="fa fa-refresh"></i> Reset</a>
        </div>
        </p>

    </div>
    <div class="card-footer">
        ADL
    </div>
</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="settingsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agent Performance Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="searchform" autocomplete="off">

                    <div class="row">
                        <div class="col">

                            <div class="form-group">
                                <input type="text" class="form-control" name="dateTo" id="dateTo" required
                                       value="<?php if (isset($dateTo)) {
                                           echo $dateTo;
                                       } else {
                                           echo $defaultDate;
                                       } ?>">
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <input type="text" class="form-control" name="dateFrom" id="dateFrom" required
                                       value="<?php if (isset($dateFrom)) {
                                           echo $dateFrom;
                                       } else {
                                           echo $defaultDate;
                                       } ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">

                            <div class="form-group date clockpicker">
                                <input type="text" class="form-control" name="timeFrom" id="timeFrom" required
                                       value="<?php if (isset($timeTo)) {
                                           echo $timeTo;
                                       } else {
                                           echo "09:00";
                                       } ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group date clockpicker">
                                <input type="text" class="form-control" name="timeTo" id="timeTo" required
                                       value="<?php if (isset($timeFrom)) {
                                           echo $timeFrom;
                                       } else {
                                           echo "22:00";
                                       } ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <button type="submit" name="submit" id="submit" value="Search" data-toggle="modal"
                                        data-target="#processing-modal" class="btn btn-primary btn-lg btn-block"><i
                                        class="fa  fa-search"></i> SEARCH
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/clockpicker-gh-pages/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/clockpicker-gh-pages/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done',
        autoclose: true
    })
        .find('input').change(function () {
    });

</script>
<script type="text/javascript" src="/clockpicker-gh-pages/assets/js/highlight.min.js"></script>
<script src="/js/jquery/jquery-3.3.1.slim.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#dateTo").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
    });

    $(function () {
        $("#dateFrom").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
    });

    $('form#searchform').submit(function () {
        $(this).find(':input[type=submit]').prop('disabled', true);
    });

</script>

<script>
    function refresh_div() {
        jQuery.ajax({
            url: '/php/cac.php',
            type: 'POST',
            success: function (results) {
                jQuery(".liveResults").html(results);
            }
        });
    }

    t = setInterval(refresh_div, 25000);
</script>

<script>
    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }

    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }
</script>
</body>
</html>
