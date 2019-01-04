<?php
$page_title = 'Mail - View';
require_once __DIR__ . '/../templates/navbar.php';
require_once __DIR__ . '/../functions/Repositories/MailsRepository.php';
if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
    $id = $_GET['mail_id'];
    $repo = new MailsRepository();
    $mail = $repo->findById($id);
}
if (!empty($mail)) {
    ?>

    <div class="row">
        <div class="ui placeholder segment">
            <table class="ui padded selectable celled center aligned striped table">
                <tbody>
                <tr>
                    <th class="three wide">Mail ID :</th>
                    <td class="six wide"><?php echo $mail->getId(); ?></td>
                </tr>
                <tr>
                    <th>To :</th>
                    <td><?php echo $mail->getAddress(); ?></td>
                </tr>
                <tr>
                    <th>Subject :</th>
                    <td><?php echo $mail->getSubject(); ?></td>
                </tr>
                <tr>
                    <th>Body :</th>
                    <td><?php echo $mail->getMessage(); ?></td>
                </tr>
                <tr>
                    <th>Time :</th>
                    <td><?php echo $mail->getDtime(); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
}
require_once __DIR__.'/../templates/footer.php';