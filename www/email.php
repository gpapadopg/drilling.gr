<?php
if('PUT' == $_SERVER['REQUEST_METHOD'] && null != ($data = file_get_contents('php://input'))) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');

    require __DIR__ . '/../config.php';

    parse_str($data, $fields);

    $errors = array();

    if(null == ($name = filter_var($fields['name'], FILTER_SANITIZE_STRING))) {
        $errors['name'] = true;
    }

    if(null == ($mail = filter_var($fields['mail'], FILTER_VALIDATE_EMAIL))) {
        $errors['mail'] = true;
    }

    if(null == ($telephone = filter_var($fields['telephone'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^(69\\d{8}|2\\d{9})$/'))))) {
        $errors['telephone'] = true;
    }

    if(null == ($comments = filter_var($fields['comments'], FILTER_SANITIZE_STRING))) {
        $errors['comments'] = true;
    }

    if(count($errors)) {
        echo json_encode(array('e'=>$errors));
    } else {
        require_once __DIR__ . '/../vendor/autoload.php'; // swift/lib/swift_required.php';

        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
              ->setUsername($username)
              ->setPassword($password);
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance('Φόρμα Επικοινωνίας | drilling.gr')
              ->setFrom(array('info@drilling.gr' => 'Φόρμα Επικοινωνίας | drilling.gr'))
              ->setTo($recipients)
              ->setBody('Φόρμα Επικοινωνίας από drilling.gr ' . "\n" . sprintf(
                        "Από: %s (%s)\n".
                        "Τηλ: %s\n".
                        "_________________________________________\n".
                        "%s", $name, $mail, $telephone, $comments
                    )
                );
        $result = $mailer->send($message);
        if (!$result) {
            echo json_encode(array('o'=>false));
            exit;
        }

        echo json_encode(array('o'=>true));
    }

    exit;
}

