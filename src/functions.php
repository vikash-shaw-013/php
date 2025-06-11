<?php

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emails = array_filter($emails, function($e) use ($email) {
        return $e !== $email;
    });
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = 'Your Verification Code';
    $message = '<p>Your verification code is: <strong>' . $code . '</strong></p>';
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";

    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    $url = 'https://www.github.com/timeline';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function formatGitHubData($data) {
    // For simplicity, assuming the fetched data is already HTML or can be directly embedded.
    // In a real-world scenario, you'd parse and format the data more robustly.
    return '<div>' . $data . '</div>';
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $githubData = fetchGitHubTimeline();
    $formattedData = formatGitHubData($githubData);

    $subject = 'Latest GitHub Updates';
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";

    foreach ($emails as $email) {
        $unsubscribeLink = 'http://localhost/src/unsubscribe.php?email=' . urlencode($email);
        $message = $formattedData . '<p><a href="' . $unsubscribeLink . '">Unsubscribe</a></p>';
        mail($email, $subject, $message, $headers);
    }
}

?>