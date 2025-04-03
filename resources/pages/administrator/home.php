<?php 
$filter = $_GET["filter"] ?? null;
$date = $_GET["start_date"] ?? date('Y-m-d');
$date1 = $_GET["end_date"] ?? date('Y-m-d');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">Overview</h2>
                    <form method="GET" id="filterForm" class="dropdown-form">
                        <select name="filter" id="filter" class="dropdown" style="padding-block:12px;">
                            <option value="day" <?= $filter === 'day' ? 'selected' : '' ?>>Day</option>
                            <option value="week" <?= $filter === 'week' ? 'selected' : '' ?>>Week</option>
                            <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>Month</option>
                        </select>
                        <label for="start_date" style="min-width:max-content;  font-size:14px; margin-inline: 10px;">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" value="<?= $date ?>" style="max-width:140px;">
                        <label for="end_date" style="min-width:max-content; font-size:14px; margin-inline: 10px;">End Date:</label>
                        <input type="date" name="end_date" id="end_date" value="<?= $date1 ?>" style="max-width:140px;">
                        <button type="submit" id="filterButton" style="margin-inline: 10px; padding: 10px 20px; border-radius:4px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Filter</button>
                    </form>
                </div>
                <div class="cards">
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Registered Employees</h5>
                                <h1><?php total_rows('tblstudents') ?></h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-container">
                <a href="manage-students" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Employee</h2>
                        <button class="add"><i class="ri-add-line"></i>Add Employee</button>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Registration No</th>
                                <th>Name</th>
                                <th>Faculty</th>
                                <th>Course</th>
                                <th>Email</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Total hours worked</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach (filter_date($filter, $date, $date1) as $row):

                            ?>
                                <tr>
                                    <td><?= $row["studentRegistrationNumber"] ?></td>
                                    <td><?= ucfirst($row["firstName"]) . ' '. ucfirst($row["lastName"]) ?></td>
                                    <td><?= $row["faculty"] ?></td>
                                    <td><?= $row["courseCode"] ?></td>
                                    <td><?= ucfirst($row["email"]) ?></td>
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
                                    } else {
                                        echo '<td>------------</td>';
                                    }

                                    ?>

                                    <td style="<?= $row["attendanceStatus"] === "present" ? 'color: green' : 'color:red' ?>"><?= ucfirst($row["attendanceStatus"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </section>

    <?php js_asset(["active_link", "delete_request"]) ?>


</body>

</html>