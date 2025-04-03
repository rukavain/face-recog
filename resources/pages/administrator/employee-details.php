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
    <script defer src="script.js"></script>
</head>

<body>

    <!-- Top Bar -->
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div class="table-container">
                <a style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Employee Details for John Doe</h2>
                        <div class="title" style="width:100%; max-width:70%; display:flex; justify-content:center; align-items:end">
                            <form id="filterForm" class="dropdown-form">
                                <select name="filter" id="filter" class="dropdown" style="padding-block:12px;">
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                                <label for="start_date" style="min-width:max-content;  font-size:14px; margin-inline: 10px;">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" value="2024-04-01" style="max-width:140px;">
                                <label for="end_date" style="min-width:max-content; font-size:14px; margin-inline: 10px;">End Date:</label>
                                <input type="date" name="end_date" id="end_date" value="2024-04-02" style="max-width:140px;">
                                <button type="button" id="filterButton" style="margin-inline: 10px; padding: 10px 20px; border-radius:4px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Filter</button>
                            </form>
                        </div>
                    </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Total hours worked</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            <tr>
                                <td>John Doe</td>
                                <td>2024-04-01</td>
                                <td>08:30 AM</td>
                                <td>05:00 PM</td>
                                <td>08:30:00</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>2024-04-01</td>
                                <td>09:00 AM</td>
                                <td>05:15 PM</td>
                                <td>08:15:00</td>
                            </tr>
                            <tr>
                                <td>Mike Johnson</td>
                                <td>2024-04-01</td>
                                <td>08:45 AM</td>
                                <td>05:10 PM</td>
                                <td>08:25:00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</body>

</html>