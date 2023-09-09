<?php
// Start the session to keep track of the encrypted message and any other session variables.
session_start();

// If a reset request has been made, unset the encrypted message and redirect to the main page.
if (isset($_POST['reset'])) {
    unset($_SESSION['encrypted']);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Check if the user is trying to store a new encrypted message.
if (isset($_POST['message']) && isset($_POST['password'])) {
    
    // Encrypt the provided message using OpenSSL with the provided password.
    $encrypted = openssl_encrypt($_POST['message'], 'aes-256-cbc', $_POST['password'], 0, '1234567812345678');
    
    // Save the encrypted message in the session to retrieve later.
    $_SESSION['encrypted'] = $encrypted;
    
    // Redirect to prevent form resubmission on refresh.
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// If there's an encrypted message saved and the user submits a password, try to decrypt the message.
if (isset($_POST['password']) && isset($_SESSION['encrypted'])) {
    $decrypted = openssl_decrypt($_SESSION['encrypted'], 'aes-256-cbc', $_POST['password'], 0, '1234567812345678');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <title>Secure Message</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #2C3E50;
            color: #ecf0f1;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background: #34495e;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1), 0px 1px 3px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="password"], textarea {
            width: calc(100% - 30px); /* Adjusting for the total padding */
            padding: 10px 15px;
            border: 1px solid #7f8c8d;
            border-radius: 5px;
            background-color: #485a6f;
            color: #ecf0f1;
            margin-bottom: 20px;
            transition: border 0.3s;
        }


        textarea {
            height: 80px;
            resize: none; /* This disables user resizing */
        }

        input[type="password"]:focus,
        textarea:focus {
            border: 1px solid #2980b9;
            outline: none;
        }

        input[type="submit"] {
            cursor: pointer;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .error,
        .status {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .error {
            background-color: #c0392b;
        }

        .status {
            background-color: #27ae60;
        }

    </style>
</head>
<body>

<form action="" method="post">

    <!-- If there's no encrypted message saved, show the message input and password input fields. -->
    <?php if (!isset($_SESSION['encrypted'])): ?>

        <label for="message">Enter your message:</label>
        <textarea name="message" required></textarea>
        
        <label for="password">Set a password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Encrypt & Store">

    <!-- If an encrypted message exists, show the password input field and a reset button. -->
    <?php else: ?>

        <label for="password">Enter password to view message:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Decrypt">

        <!-- If a password was submitted and decrypted the message successfully, display the decrypted message. -->
        <?php if (isset($decrypted)): ?>
            <p>Your decrypted message is: <?= htmlspecialchars($decrypted) ?></p>
        <?php endif; ?>

        <!-- Button to reset the form and start fresh by clearing the saved encrypted message. -->
        <input type="submit" name="reset" value="Reset">

    <?php endif; ?>

</form>

</body>
</html>
