<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_numbers = explode("\n", trim($_POST['phone_numbers']));
    $carrier = $_POST['carrier'];
    $message = $_POST['message'];

    // Carrier email-to-SMS gateways
    $carrier_gateways = array(
        'att' => '@txt.att.net',
        'tmobile' => '@tmomail.net',
        'verizon' => '@vtext.com',
        'sprint' => '@messaging.sprintpcs.com',
    );

    if (!array_key_exists($carrier, $carrier_gateways)) {
        die('Unsupported carrier');
    }

    $from_email = 'protectiondriveforclear@proton.me'; 
    $reply_to_email = 'protectiondriveforclear@proton.me'; 

    foreach ($phone_numbers as $phone_number) {
        $phone_number = trim($phone_number);
        if (!preg_match('/^\d{10}$/', $phone_number)) {
            echo "Invalid phone number format: $phone_number. Please enter a 10-digit phone number.<br>";
            continue;
        }

        $to_number = $phone_number . $carrier_gateways[$carrier];
        $subject = 'SMS via Email';
        $headers = 'From: ' . $from_email . "\r\n" .
                   'Reply-To: ' . $reply_to_email . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        if (mail($to_number, $subject, $message, $headers)) {
            echo "SMS sent successfully to $phone_number!<br>";
        } else {
            echo "Failed to send SMS to $phone_number.<br>";
        }
    }
}
?>
