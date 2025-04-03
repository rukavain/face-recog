<?php

$courseCode = isset($_GET['course']) ? $_GET['course'] : '';
$unitCode = isset($_GET['unit']) ? $_GET['unit'] : '';

$studentRows = fetchStudentRecordsFromDatabase($courseCode, $unitCode);

$coursename = "";
if (!empty($courseCode)) {
    $coursename_query = "SELECT name FROM tblcourse WHERE courseCode = '$courseCode'";
    $result = fetch($coursename_query);
    foreach ($result as $row) {

        $coursename = $row['name'];
    }
}
$unitname = "";
if (!empty($unitCode)) {
    $unitname_query = "SELECT name FROM tblunit WHERE unitCode = '$unitCode'";
    $result = fetch($unitname_query);
    foreach ($result as $row) {

        $unitname = $row['name'];
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>lecture Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>



<body>
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <form class="lecture-options" id="selectForm">
                <select required name="course" id="courseSelect" onChange="updateTable()">
                    <option value="" selected>Select Course</option>
                    <?php
                    $courseNames = getCourseNames();
                    foreach ($courseNames as $course) {
                        echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
                    }
                    ?>
                </select>

                <select required name="unit" id="unitSelect" onChange="updateTable()">
                    <option value="" selected>Select Unit</option>
                    <?php
                    $unitNames = getUnitNames();
                    foreach ($unitNames as $unit) {
                        echo '<option value="' . $unit["unitCode"] . '">' . $unit["name"] . '</option>';
                    }
                    ?>
                </select>
            </form>

            <button class="add" onclick="exportTableToExcel('attendaceTable', '<?php echo $unitCode ?>_on_<?php echo date('Y-m-d'); ?>','<?php echo $coursename ?>', '<?php echo $unitname ?>')">Export Attendance As Excel</button>

            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Attendance Preview</h2>
                </div>
                <div class="table attendance-table" id="attendaceTable">
                    <table>
                        <thead>
                            <tr>
                                <th>Registration No</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <?php
                            
                                $query = "SELECT
                                s.registrationNumber as number,
                                a.date_created as date,
                                a.time_in as time_in,
                                a.time_out as time_out,
                                a.attendanceStatus as status
                                FROM tblstudents s LEFT JOIN
                                tblattendance a ON a.course = s.courseCode
                                ";

                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach($result as $row):
                            
                            ?>
                            <tr>
                                <td><?= $row["number"] ?></td>
                                <td><?= gmdate("h:i:s A", strtotime($row["time_in"])) ?></td>
                                <td><?= $row["time_out"] !== null ? gmdate("h:i:s A", strtotime($row["time_out"])) : "------------" ?></td>
                                
                                <?php 

                                    if ($row["time_out"] !== null) {

                                        $time_in = new DateTime($row["time_in"]);
                                        $time_out = new DateTime($row["time_out"]);

                                        //calculate the difference
                                        $diff = $time_out->diff($time_in);
                                        //format as HH:MM:SS
                                        $total_time_worked = $diff->format('%H:%I:%S');

                                        echo '<td>' . htmlspecialchars($total_time_worked) . '</td>';
                                    }else{
                                        echo '<td>------------</td>';
                                    }

                                ?>

                                <td style="<?= $row["status"] === "present" ? 'color: green' : 'color:red' ?>"><?= ucfirst($row["status"]) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        </div>
    </section>
    <div>
</body>
<?php js_asset(['min/js/filesaver', 'min/js/xlsx', 'active_link']) ?>



<script>
    function updateTable() {
        var courseSelect = document.getElementById("courseSelect");
        var unitSelect = document.getElementById("unitSelect");

        var selectedCourse = courseSelect.value;
        var selectedUnit = unitSelect.value;

        var url = "download-record";
        if (selectedCourse && selectedUnit) {
            url += "?course=" + encodeURIComponent(selectedCourse) + "&unit=" + encodeURIComponent(selectedUnit);
            window.location.href = url;

        }
    }

    function exportTableToExcel(tableId, filename = '', courseCode = '', unitCode = '') {
        var table = document.getElementById(tableId);
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleDateString(); // Format the date as needed

        var headerContent = '<p style="font-weight:700;"> Attendance for : ' + courseCode + ' Unit name : ' + unitCode + ' On: ' + formattedDate + '</p>';
        var tbody = document.createElement('tbody');
        var additionalRow = tbody.insertRow(0);
        var additionalCell = additionalRow.insertCell(0);
        additionalCell.innerHTML = headerContent;
        table.insertBefore(tbody, table.firstChild);
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Attendance"
        });
        var wbout = XLSX.write(wb, {
            bookType: 'xlsx',
            bookSST: true,
            type: 'binary'
        });
        var blob = new Blob([s2ab(wbout)], {
            type: 'application/octet-stream'
        });
        if (!filename.toLowerCase().endsWith('.xlsx')) {
            filename += '.xlsx';
        }

        saveAs(blob, filename);
    }

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }
</script>


</html>