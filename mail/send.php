<?php
$page_title = 'Send Email';
require_once '../templates/navbar.php';
require_once '../functions/Utilities/Mailer.php';

if (isset($_POST['send']) && isset($_POST['address']) && isset($_POST['subject']) && isset($_POST['message'])) {
    $address = $_POST['address'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $mail = new Mail(null, $address, $subject, $message);
    $mailer = new Mailer();

    if ($mailer->send($mail)) {
        setAlert('Email sent successfully', 'success');
    } else {
        setAlert('Email couldn\'t be sent', 'danger');
    }
}

alertBox();

?>

    <div class="row">
        <div class="column ten wide">
            <div class="ui placeholder segment">
                <form class="ui form" method="post" action="send.php">
                    <div class="field">
                        <label for="address">Email :</label>
                        <input type="text" id="address" name="address" placeholder="abc@def.com"
                               value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>">
                    </div>
                    <div class="field">
                        <label for="subject">Subject :</label>
                        <input type="text" id="subject" name="subject" placeholder="Happy New Year!">
                    </div>
                    <div class="field">
                        <label for="message">Message :</label>
                        <textarea id="message" name="message"></textarea>
                    </div>
                    <div class="field">
                        <input class="ui blue button" name="send" type="submit" value="Send">
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
require_once '../templates/footer.php';