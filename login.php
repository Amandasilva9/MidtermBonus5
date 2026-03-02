<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<?php
// ----------------------
// DATABASE CONNECTION
// ----------------------
$conn = new mysqli("localhost", "root", "", "SocialMediaDB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// ----------------------
// HELPER: PRINT TABLE
// ----------------------
function printTable($result, $title) {
    echo "<h3>$title</h3>";

    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        
        // Header row
        echo "<tr>";
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>" . $fieldinfo->name . "</th>";
        }
        echo "</tr>";

        // Data rows
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table><br>";
    } else {
        echo "No results.<br><br>";
    }
}

// ----------------------
// LOGIN LOGIC
// ----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Get user by username only
    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            $message = "Login Successful";
        } else {
            $message = "Login Unsuccessful";
        }
    } else {
        $message = "Login Unsuccessful";
    }
}

echo "<p style='color:red;'>$message</p>";

// ----------------------
// JOIN QUERIES
// ----------------------

// NATURAL JOIN
$q1 = "SELECT * FROM Users NATURAL JOIN UserDetails";
printTable($conn->query($q1), "NATURAL JOIN");

// INNER JOIN
$q2 = "SELECT * FROM Users INNER JOIN UserDetails ON Users.username = UserDetails.username";
printTable($conn->query($q2), "INNER JOIN");

// LEFT OUTER JOIN
$q3 = "SELECT * FROM Users LEFT JOIN UserDetails ON Users.username = UserDetails.username";
printTable($conn->query($q3), "LEFT OUTER JOIN");

// RIGHT OUTER JOIN
$q4 = "SELECT * FROM Users RIGHT JOIN UserDetails ON Users.username = UserDetails.username";
printTable($conn->query($q4), "RIGHT OUTER JOIN");

// FULL OUTER JOIN (UNION)
$q5 = "
    SELECT * FROM Users LEFT JOIN UserDetails ON Users.username = UserDetails.username
    UNION
    SELECT * FROM Users RIGHT JOIN UserDetails ON Users.username = UserDetails.username
";
printTable($conn->query($q5), "FULL OUTER JOIN (simulated with UNION)");

?>

</body>
</html>
