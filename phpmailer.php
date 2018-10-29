<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';

$host="prod-rep3.cfti8umsjnvm.ap-southeast-1.rds.amazonaws.com"; // Host name
$username="Sample_monitor"; // MySQL username
$password="aabb1122"; // MySQL password
$db_name="trade"; // Database Name

// Connect to server and select database.
$con=mysqli_connect($host,$username,$password,$db_name) or die("cannot connect");

$client_query= "SELECT * from table";
    
    $data=mysqli_query($con,$client_query);

    while ($row = mysqli_fetch_array($data)) {
        $loan_amount = $row['loan_amount'];
        $bill_amount = $row['bill_amount'];
        $dp_amount = $row['dp_amount'];
        $tenor=$row['tenor'];
        $monthly_ins = $row['monthly_ins'];
        $loan_id = $row['loan_id'];
        $so_code = $row['so_code'];
        $so_name = $row['so_name'];
        $appdate = $row['appdate'];
        $name=$row['name'];
        $pos=$row['pos'];
        $pos_type=$row['pos_type'];
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            
            //Server settings
            $mail->SMTPDebug = 1;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'user@host.com';                 // SMTP username
            $mail->Password = 'password';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('user@host.com', 'Glenn Dalida');
            $mail->addAddress('user@host.com', 'Glenn Dalida');     // Add a recipient
            $mail->addAddress('user@host.com');
            // Name is optional
            $mail->addCC('user@host.com');
            $mail->addBCC('user@host.com');
            // Optional name
        
            //Content
            $mail->isHTML(false);                                  // Set email format to HTML
            $mail->Subject = 'Sample '.$loan_id;
            $mail->Body    = "Sample ".$appdate.",

Good Day!

Cashalo Loan ".$loan_id." has been approved for ".$name." in ".$pos."
 
Loan Details:
 
SO Name: ".$so_name."
Bill amount: PHP ".$bill_amount."
Loan Amount: PHP ".$loan_amount."
Required Down payment: PHP ".$dp_amount."
Monthly Amount Due: PHP ".$monthly_ins."
Term: ".$tenor." months
Product Type: ".$pos_type."
 
Please only accept transaction with the Cashalo Sales Officer.
 
Thank you very much!

The Cashalo Team
";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
}