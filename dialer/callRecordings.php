<?php
define("BASE_URL", (filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS)));

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
?>
<!DOCTYPE html>
<html lang="en">
<title>Connex | Call Recordings</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
</head>
<body>
<?php include("../includes/navbar.html"); ?>
<div class="container">
    <br>
    <div class="card">
        <h3 class="card-header">
            <i class="fa fa-headphones"></i> Random Call Recordings <a href='callRecordings.php'>
                <button type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-history"></i> New
                    Search...
                </button>
            </a>
        </h3>
        <div class="card-block">

            <p class="card-text">


            <h4>Search by date</h4>

            <form action="callRecordings.php?EXECUTE=1" method="post" id="searchform" autocomplete="off">
                <div class="form-group">
                    <div class="col-xs-2">
                        <input type="text" class="form-control" name="date" id="date" required value="<?php echo $date ?>">
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-xs-2">
                        <div class="btn-group">
                            <button type="submit" name="submit" value="Search" data-toggle="modal"
                                    data-target="#processing-modal" class="btn btn-info btn-sm"><i
                                        class="fa  fa-search"></i> SEARCH
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <br>

            <?php

            if (isset($EXECUTE)) {

                require_once(BASE_URL . '/includes/CONNEX_PDO_CON.php');

                $query = $pdo->prepare("SELECT 
    full_name, phone_number, location, status, length_in_min
FROM
    recording_log
        JOIN
    users ON users.user = recording_log.user
WHERE
        DATE(start_time) =:DATE
        AND user_group IN (1700 , 1300)
        AND length_in_min BETWEEN 0.20 AND 5.00
AND status != 'DNC'
ORDER BY RAND()
LIMIT 75");
                $query->bindParam(':DATE', $date, PDO::PARAM_STR);
                ?>

                <table align="center" class="table">
                    <thead>
                    <tr>
                        <th colspan=11>Searched 75 random recordings for <?php if (isset($date)) {
                                echo $date;
                            } ?></th>
                    </tr>
                    <tr>
                        <th>Row</th>
                        <th>Agent</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Length (min)</th>
                        <th></th>
                    </tr>
                    </thead>

                    <?php

                    $query->execute();
                    if ($query->rowCount() > 0) {

                        $i = 0;
                        $ignoreNumbers = [
                                '07738215811',
                            '07846277918',
                            '07583909734',
                            '07939974552',
                            '07842773729',
                            '07789770723',
                            '07713486450',
                            '07753766397',
                            '07887493518',
                            '07989447182',
                            '07956422415',
                            '07961994476',
                            '07403182529',
                            '07506379928',
                            '07545229145',
                            '07981589777',
                            '07506379928',
                            '07885859676',
                            '07933453564',
                            '07980890518',
                            '07446838810',
                            '07479392229',
                            '07432288478',
                            '07933022481',
                            '07933022481',
                            '07983101002',
                            '07880491494',
                            '07897667095',
                            '07542202019',
                            '07810873745'
                        ];

                        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                            $i++;

                            if(in_array($result['phone_number'], $ignoreNumbers)) {
                                $result['location'] = 'Error';
                            }

                            ?>

                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php if (isset($result['full_name'])) {
                                        echo $result['full_name'];
                                    } ?></td>
                                <td><?php if (isset($result['phone_number'])) {
                                        echo $result['phone_number'];
                                    } ?></td>
                                <td><?php if (isset($result['status'])) {
                                        echo $result['status'];
                                    } ?></td>
                                <td><?php if (isset($result['length_in_min'])) {
                                        echo $result['length_in_min'];
                                    } ?></td>
                                <td>
                                    <?php if($result['location'] == 'Error') { ?>
                                        Error
                                    <?php } else { ?>
                                    <audio
                                            controls
                                            src="<?php if (isset($result['location'])) {
                                                echo $result['location'];
                                            } ?>">
                                        Your browser does not support the
                                        <code>audio</code> element.
                                    </audio>
                                    <?php } ?>
                                </td>
                            </tr>

                            <?php

                        }
                    } else {
                        echo 'No data found';
                    }

                    ?>
                </table>

                <?php

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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
    });
</script>

</body>
</html>
