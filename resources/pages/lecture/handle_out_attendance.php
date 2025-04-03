<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Initialize response
$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    // Verify POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get and validate JSON input
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data received');
    }
    
    if (empty($data['courseCode'])) {
        throw new Exception('Course code is required');
    }
    
    // Update query for tblattendance
    $sql = "UPDATE tblattendance a
            INNER JOIN (
                SELECT studentRegistrationNumber, MAX(dateMarked) as latest_date
                FROM tblattendance
                WHERE course = :courseCode
                AND time_out IS NULL
                GROUP BY studentRegistrationNumber
            ) latest ON a.studentRegistrationNumber = latest.studentRegistrationNumber 
                    AND a.dateMarked = latest.latest_date
            SET a.time_out = NOW()
            WHERE a.course = :courseCode
            AND a.time_out IS NULL AND a.attendanceStatus='present'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseCode', $data['courseCode']);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to execute update');
    }
    
    $rowCount = $stmt->rowCount();
    $response = [
        'status' => 'success',
        'message' => $rowCount > 0 
            ? "Successfully updated time out for $rowCount students" 
            : "No unattended records found for this course",
        'updatedCount' => $rowCount
    ];

} catch (PDOException $e) {
    http_response_code(500);
    $response['message'] = "Database error: " . $e->getMessage();
    error_log("PDO Error: " . $e->getMessage());
} catch (Exception $e) {
    http_response_code(400);
    $response['message'] = $e->getMessage();
}

// Ensure clean output
ob_clean();
echo json_encode($response);
exit;