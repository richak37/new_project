<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php");
    exit();
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["paper"])) {
    $department = $_POST['department'];
    $year = $_POST['year'];
    $subject = $_POST['subject'];
    $file = $_FILES['paper'];

    // Validate file
    $allowedTypes = ['application/pdf'];
    if (in_array($file['type'], $allowedTypes)) {
        $targetDir = "papers/$department/$year/$subject/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filePath = $targetDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Save file information to the database
            $sql = "INSERT INTO papers (department, year, subject, file_path) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$department, $year, $subject, $filePath])) {
                echo "File uploaded and data saved successfully!";
            } else {
                echo "Failed to save file data to the database.";
            }
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "Invalid file type. Only PDF files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Question Paper</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Upload Question Paper</h1>
        
        <!-- Upload Form -->
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="department">Department:</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Electrical Engineering">Electrical Engineering</option>
                    <!-- Add more departments as needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" name="year" required>
                    <option value="First Year">First Year</option>
                    <option value="Second Year">Second Year</option>
                    <option value="Third Year">Third Year</option>
                    <option value="Fourth Year">Fourth Year</option>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <select class="form-control" id="subject" name="subject" required>
                    <option value="Data Structures">Data Structures</option>
                    <option value="Circuits">Circuits</option>
                    <!-- Add more subjects as needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="paper">Upload Paper (PDF only):</label>
                <input type="file" class="form-control-file" id="paper" name="paper" accept=".pdf" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
