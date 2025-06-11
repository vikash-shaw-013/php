<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe</title>
</head>
<body>
    <?php
    require_once 'functions.php';

    session_start();

    $message = '';

    // Handle unsubscribe email submission
    if (isset($_POST['unsubscribe_email']) && isset($_POST['submit-unsubscribe'])) {
        $email = $_POST['unsubscribe_email'];
        $code = generateVerificationCode();
        sendVerificationEmail($email, $code);
        $_SESSION['pending_unsubscribe_email'] = $email;
        $_SESSION['unsubscribe_verification_code'] = $code;
        $message = 'Verification code sent to ' . $email . ' for unsubscription.';
    }

    // Handle unsubscribe verification code submission
    if (isset($_POST['unsubscribe_verification_code']) && isset($_POST['verify-unsubscribe'])) {
        $enteredCode = $_POST['unsubscribe_verification_code'];
        if (isset($_SESSION['unsubscribe_verification_code']) && $enteredCode === $_SESSION['unsubscribe_verification_code']) {
            if (isset($_SESSION['pending_unsubscribe_email'])) {
                unsubscribeEmail($_SESSION['pending_unsubscribe_email']);
                $message = 'Email ' . $_SESSION['pending_unsubscribe_email'] . ' successfully unsubscribed!';
                unset($_SESSION['pending_unsubscribe_email']);
                unset($_SESSION['unsubscribe_verification_code']);
            } else {
                $message = 'No pending email for unsubscription verification.';
            }
        } else {
            $message = 'Invalid verification code.';
        }
    }
    ?>

    <h1>Unsubscribe</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="unsubscribe_email">Email:</label>
        <input type="email" name="unsubscribe_email" id="unsubscribe_email" required>
        <button type="submit" name="submit-unsubscribe" id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <h1>Verify Unsubscribe Code</h1>
    <form action="" method="post">
        <label for="unsubscribe_verification_code">Verification Code:</label>
        <input type="text" name="unsubscribe_verification_code" id="unsubscribe_verification_code" maxlength="6" required>
        <button type="submit" name="verify-unsubscribe" id="verify-unsubscribe">Verify</button>
    </form>
</body>
</html>