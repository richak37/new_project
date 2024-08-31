<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/db.php';

// Initialize filter variables
$department = '';
$year = '';
$subject = '';

// Handle filter form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $department = $_GET['department'] ?? '';
    $year = $_GET['year'] ?? '';
    $subject = $_GET['subject'] ?? '';
}

// Build the query with filters
$sql = "SELECT * FROM papers WHERE 1=1";
$params = [];

if ($department) {
    $sql .= " AND department = ?";
    $params[] = $department;
}
if ($year) {
    $sql .= " AND year = ?";
    $params[] = $year;
}
if ($subject) {
    $sql .= " AND subject = ?";
    $params[] = $subject;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$papers = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Papers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1>View Question Papers</h1>
        
        <!-- Filter Form -->
        <form method="GET" action="view_papers.php">
            <div class="form-group">
                <label for="department">Department:</label>
                <select class="form-control" id="department" name="department">
                    <option value="">Select Department</option>
                    <option value="Computer Science" <?php echo ($department === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Electrical Engineering" <?php echo ($department === 'Electrical Engineering') ? 'selected' : ''; ?>>Electrical Engineering</option>
                    <!-- Add more departments as needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" name="year">
                    <option value="">Select Year</option>
                    <option value="First Year" <?php echo ($year === 'First Year') ? 'selected' : ''; ?>>First Year</option>
                    <option value="Second Year" <?php echo ($year === 'Second Year') ? 'selected' : ''; ?>>Second Year</option>
                    <option value="Third Year" <?php echo ($year === 'Third Year') ? 'selected' : ''; ?>>Third Year</option>
                    <option value="Fourth Year" <?php echo ($year === 'Fourth Year') ? 'selected' : ''; ?>>Fourth Year</option>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <select class="form-control" id="subject" name="subject">
                    <option value="">Select Subject</option>
                    <option value="Data Structures" <?php echo ($subject === 'Data Structures') ? 'selected' : ''; ?>>Data Structures</option>
                    <option value="Circuits" <?php echo ($subject === 'Circuits') ? 'selected' : ''; ?>>Circuits</option>
                    <!-- Add more subjects as needed -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        
        <!-- Display Papers -->
        <ul class="list-group mt-3">
            <?php if ($papers): ?>
                <?php foreach ($papers as $paper): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($paper['department']); ?></strong> - 
                        Year: <?php echo htmlspecialchars($paper['year']); ?> - 
                        Subject: <?php echo htmlspecialchars($paper['subject']); ?>
                        <a href="<?php echo htmlspecialchars($paper['file_path']); ?>" class="btn btn-primary btn-sm float-right" download>Download</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No papers found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
