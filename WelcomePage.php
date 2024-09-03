<?php
session_start();
if (!isset($_SESSION['sid'])) {
    header('Location: LoginPage.php');
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .welcome-message {
            font-size: 2em;
            color: #333;
        }
    </style>
    <!-- Include the canvas-confetti library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@latest/dist/confetti.browser.min.js"></script>
    <script>
         function triggerConfetti() {
            confetti({
                particleCount: 2000,
                spread: 100,
                origin: { x: 0, y: 1 },
                angle: 45,
                scalar: 1.2
            });

            confetti({
                particleCount: 2000,
                spread: 100,
                origin: { x: 1, y: 1 },
                angle: 135,
                scalar: 1.2
            });
        }

        window.onload = function() {
            triggerConfetti();
            setTimeout(function() {
                window.location.href = "MainPage.php";
            }, 3000); // 10 seconds
        };
    </script>
</head>
<body>
    <div class="welcome-container">
    <h1 style="text-align: center; font-size:100px">🎉</h1><br>
        <div class="welcome-message" style=" font-family:cursive;color:red">
          <h1>  Welcome, <?php echo htmlspecialchars($username); ?></h1>
        </div>
    </div>
</body>
</html>
