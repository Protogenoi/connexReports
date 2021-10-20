<?php // crap i put in to make it work in my example
////////////////////////////////////////////////////
?>

<script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"
></script>

<!-- Font Awesome -->
<link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        rel="stylesheet"
/>
<!-- Google Fonts -->
<link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
/>
<!-- MDB -->
<link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css"
        rel="stylesheet"
/>

<script
        type="text/javascript"
        src="https://code.jquery.com/jquery-3.5.1.js"
></script>

<script
        type="text/javascript"
        src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"
></script>


<link
        href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"
        rel="stylesheet"
/>

<?php // crap i put in to make it work in my example
////////////////////////////////////////////////////
?>



<?php

//var_dump(get_defined_vars());


?>

<link
        href="D_Default.css"
        rel="stylesheet"
/>


<script>


    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row

        textout = "";

        console.log(d.DispoData);

        for (const [key, value] of Object.entries(d.DispoData)) {

            textout += '<div class="progress-bar D_Default D_' + key + '" role="progressbar" style="width: calc((100% /' + d.calls + ')*' + value + ');" Title = "' + key + '">' + value + '</div>';
        }


        return textout;
    }


    $(document).ready(function () {
        var table = $('#example').DataTable({
            "iDisplayLength": 100,
            "ajax": "./AgentAJAXinfo.php",
            "columns": [
                {

                    "render": function (data) {
                        return '<a href="https://web-assurafinancial.cnx1.cloud/Admin/agents/edit/?c=' + data + '" target="_blank">' + data + '</a>';
                    }
                },
                {"data": "user_group"},
                {
                    "className": 'dispo-control',
                    "data": "calls"
                },
                {"data": "avgTalkSec"},
                {"data": "avgDead"},
                {"data": "avgWaitSec"},
                {"data": "avgDispoSec"},
            ],
            "order": [[1, 'asc']]
        });

        // Add event listener for opening and closing details
        $('#example tbody').on('click', 'td.dispo-control', function () {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });


</script>

<div class="container">


    <table id="example" class="display" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>calls</th>
            <th>avgTalkSec</th>
            <th>avgDead</th>
            <th>avgWaitSec</th>
            <th>avgDispoSec</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>calls</th>
            <th>avgTalkSec</th>
            <th>avgDead</th>
            <th>avgWaitSec</th>
            <th>avgDispoSec</th>
        </tr>
        </tfoot>
    </table>


</div>
