<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');



if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $location = $input['location'] ?? '';
    $interest = $input['interest'] ?? '';
    $availability = $input['availability'] ?? '';
    $message = $input['message'] ?? '';

    // Validation
    if (empty($name) || empty($email) || empty($interest) || empty($availability)) {
        echo json_encode([
            'success' => false,
            'error' => 'All required fields must be filled'
        ]);
        exit;
    }

    try {
        // Insert into database
        $sql = "INSERT INTO volunteers (name, email, location, interest, availability, message) 
                VALUES (:name, :email, :location, :interest, :availability, :message)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':location' => $location,
            ':interest' => $interest,
            ':availability' => $availability,
            ':message' => $message
        ]);
        
        $volunteer_id = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for joining Eco-Warriors! We will contact you soon.',
            'data' => [
                'id' => $volunteer_id,
                'name' => $name,
                'email' => $email
            ]
        ]);
        
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
}
?>