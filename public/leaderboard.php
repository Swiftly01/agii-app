<?php
ob_clean(); // clear any previous output

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");

include('conn.php');

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        "error" => true,
        "message" => "Database connection failed"
    ]);
    exit;
}

$query = "
SELECT 
    SUM(CASE WHEN LOWER(gender) = 'male' THEN 1 ELSE 0 END) AS male,
    SUM(CASE WHEN LOWER(gender) = 'female' THEN 1 ELSE 0 END) AS female
FROM lbrf_topical
";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$response = [
    "success" => true,
    "male" => (int)$row['male'],
    "female" => (int)$row['female'],
    "total" => (int)$row['male'] + (int)$row['female']
];

echo json_encode($response);
exit;