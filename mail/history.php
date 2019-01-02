<?php
$page_title = 'Mail History';
require_once __DIR__ . '/../templates/navbar.php';
require_once __DIR__ . '/../functions/Repositories/MailsRepository.php';

if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

$repo = new MailsRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 4;
$number_of_rows = $repo->totalRecords();
$total_pages = ceil($number_of_rows / $results_per_page);
$first_result = ($current_page - 1) * $results_per_page;

$arr = $repo->getPaginated($first_result, $results_per_page);

?>

    <div class="row">
        <?php alertBox(); ?>
    </div>

    <table id="table" class="ui selectable celled table">
        <thead>
        <tr>
            <th class="one wide">ID</th>
            <th class="one wide">Date Time</th>
            <th class="one wide">Address</th>
            <th class="one wide">Subject</th>
            <th class="one wide">Message</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $data = '';
        foreach ($arr as $mail) {
            $data .= '<tr>
        <td>' . $mail->getId() . '</td>
        <td>' . $mail->getDtime() . '</td>
        <td>' . $mail->getAddress() . '</td>
        <td>' . $mail->getSubject() . '</td>
        <td>' . mb_strimwidth($mail->getMessage(),0,50, '...') . '</td>
        </tr>';
        }
        echo $data;
        ?>
        </tbody>
    </table>
<?php
printPagination($current_page,$total_pages,APP_URL_BASE.'/mail/history.php?');
require_once __DIR__ . '/../templates/footer.php';