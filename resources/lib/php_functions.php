<?php
function user()
{
    if (isset($_SESSION['user'])) {
        return (object) $_SESSION['user'];
    }
    return null;
}

function getFacultyNames()
{
    global $pdo;
    $sql = "SELECT * FROM tblfaculty";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $facultyNames = array();
    if ($result) {
        foreach ($result as $row) {
            $facultyNames[] = $row;
        }
    }

    return $facultyNames;
}
function getLectureNames()
{
    global $pdo;
    $sql = "SELECT Id, firstName, lastName FROM tbllecture";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lectureNames = array();
    if ($result) {
        foreach ($result as $row) {
            $lectureNames[] = $row;
        }
    }

    return $lectureNames;
}
function getCourseNames()
{
    global $pdo;
    $sql = "SELECT * FROM tblcourse";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $courseNames = array();
    if ($result) {
        foreach ($result as $row) {
            $courseNames[] = $row;
        }
    }

    return $courseNames;
}
function getVenueNames()
{
    $sql = "SELECT className FROM tblvenue";
    $result =  fetch($sql);

    $venueNames = array();
    if ($result) {
        foreach ($result as $row) {
            $venueNames[] = $row;
        }
    }

    return $venueNames;
}
function getUnitNames()
{
    $sql = "SELECT unitCode,name FROM tblunit";
    $result = fetch($sql);

    $unitNames = array();
    if ($result) {
        foreach ($result as $row) {
            $unitNames[] = $row;
        }
    }

    return $unitNames;
}

function showMessage(): void
{
    if (isset($_SESSION['message'])) {
        echo " <div id='messageDiv' class='messageDiv' >{$_SESSION['message']}</div>";
        echo `<script>
        
         var messageDiv = document.getElementById('messageDiv');
    messageDiv.style.opacity = 1;
    setTimeout(function() {
      messageDiv.style.opacity = 0;
    }, 5000);
        </script>`;

        unset($_SESSION['message']);
    }
}


function total_rows($tablename)
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM {$tablename}");
    $total_rows = $stmt->rowCount();
    echo $total_rows;
}

function fetch($sql)
{
    global $pdo;
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function fetchStudentRecordsFromDatabase($courseCode, $unitCode)
{
    $studentRows = array();

    $query = "SELECT * FROM tblattendance WHERE course = '$courseCode' AND unit = '$unitCode'";
    $result = fetch($query);

    if ($result) {
        foreach ($result as $row) {
            $studentRows[] = $row;
        }
    }

    return $studentRows;
}

function js_asset($links = [])
{
    if ($links) {
        foreach ($links as $link) {
            echo "<script src='resources/assets/javascript/{$link}.js'>
        </script>";
        }
    }
}


function get_employee_by_id($id) {

    $employeeRows = [];

    $query = "SELECT * FROM employee WHERE id= '$id'";
    $result = fetch($query);

    if($result){
        foreach($result as $row){
            $employeeRows = $row;
        }
    }

    return $employeeRows;
}

function get_fullname($id){
    global $pdo;
    $stmt = $pdo->prepare("SELECT firstName, lastName from employee WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch();
    return ucfirst($result["firstName"]). ' ' .ucfirst($result["lastName"]);
}

function filter_date($filter, $date, $date1, $id){

    $employees = [];

    global $pdo;

    //default query with no filter
    $query = "SELECT
        etr.employee_id,
        e.id,
        e.firstName,
        e.Lastname,
        e.dateCreated,
        etr.date_created,
        DATE(etr.time_in) as date,
        etr.time_in,
        etr.time_out
        FROM employee_time_records etr
        LEFT JOIN employee e ON e.id = etr.employee_id
        WHERE e.id = :employee_id";


    switch($filter){
        case "day":
            $startDate = $date;
            $endDate = $date1;
            $query .= " AND DATE(time_in) BETWEEN :startDate AND :endDate";
        break;
        case "week":
            $startDate = date('Y-m-d', strtotime('monday this week', strtotime($date)));
            $endDate = date('Y-m-d', strtotime('sunday this week', strtotime($date1)));
            $query .= " AND DATE(time_in) BETWEEN :startDate AND :endDate";
        break;
        case "month":
            $startDate = date('Y-m-d', strtotime('first day of this month', strtotime($date)));
            $endDate = date('Y-m-d', strtotime('last day of this month', strtotime($date1)));
            $query .= " AND DATE(time_in) BETWEEN :startDate AND :endDate";
        break;
        
    }

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":employee_id", $id);
    if(isset($startDate)) $stmt->bindParam(":startDate", $startDate);
    if(isset($endDate)) $stmt->bindParam(":endDate", $endDate);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($result){
        foreach($result as $row){
            $employees[] = $row;
        }
    }

    return $employees;

}

