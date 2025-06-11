<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification and GitHub Updates</title>
</head>
<body>
    <?php
    require_once 'functions.php';

    session_start();

    $message = '';

    // Handle email submission
    if (isset($_POST['email']) && isset($_POST['submit-email'])) {
        $email = $_POST['email'];
        $code = generateVerificationCode();
        sendVerificationEmail($email, $code);
        $_SESSION['pending_email'] = $email;
        $_SESSION['verification_code'] = $code;
        $message = 'Verification code sent to ' . $email;
    }

    // Handle verification code submission
    if (isset($_POST['verification_code']) && isset($_POST['submit-verification'])) {
        $enteredCode = $_POST['verification_code'];
        if (isset($_SESSION['verification_code']) && $enteredCode === $_SESSION['verification_code']) {
            if (isset($_SESSION['pending_email'])) {
                registerEmail($_SESSION['pending_email']);
                $message = 'Email ' . $_SESSION['pending_email'] . ' successfully registered!';
                unset($_SESSION['pending_email']);
                unset($_SESSION['verification_code']);
            } else {
                $message = 'No pending email for verification.';
            }
        } else {
            $message = 'Invalid verification code.';
        }
    }
    ?>

    <h1>Email Verification</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" name="submit-email" id="submit-email">Submit</button>
    </form>

    <h1>Verify Code</h1>
    <form action="" method="post">
        <label for="verification_code">Verification Code:</label>
        <input type="text" name="verification_code" id="verification_code" maxlength="6" required>
        <button type="submit" name="submit-verification" id="submit-verification">Verify</button>
    </form>
</body>
</html>