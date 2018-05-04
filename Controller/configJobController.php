<?php
if(isset($_POST['send_cv'])) {

    $email_to = "itdominos123@gmail.com";
    $email_subject = "Работа Dominos.bg";

    function died($error) {
        header ("Location: ../Controller/indexController.php?page=jobsFailed");
        die();
    }


    $first_name = $_POST['f_name']; // required
    $last_name = $_POST['l_name']; // required
    $email_from = $_POST['email']; // required
    $city = $_POST['city']; // required
//    $cv = $_POST['cv']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';


    if(strlen($city) < 5 || strlen($first_name) < 2 || strlen($last_name) < 4 || strlen($email_from) < 9) {


        $error_message .= 'The Comments you entered do not appear to be valid.<br />';
    }

    if(strlen($error_message) > 0) {
        died($error_message);
    }

    $email_message = "Form details below.\n\n";


    function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }


    $email_message .= "First Name: ".clean_string($first_name)."\n";
    $email_message .= "Last Name: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "City: ".clean_string($city)."\n";

// create email headers
    $headers = 'From: '.$email_from."\r\n".
        'Reply-To: '.$email_from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $email_subject, $email_message, $headers);

    header ("Location:../Controller/indexController.php?page=main");


}
