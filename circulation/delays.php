<?php
$page_title = 'Delays';
require_once '../functions/Repositories/RequestsRepository.php';
require_once '../templates/navbar.php';

$repo = new RequestsRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;

if (!isAdmin()) {
    $number_of_rows = $repo->countNonReturnedBooksByUserId(getUser()['id']);
    $total_pages = ceil($number_of_rows / $results_per_page);
    $first_result = ($current_page - 1) * $results_per_page;

    $arr = $repo->getNonReturnedBooksByUserIdPaginated($id,$first_result,$results_per_page);
    if (empty($arr)) {
        setAlert('You are clean!', 'success');
    }
}else{
    $number_of_rows = $repo->countAllNonReturnedBooks();
    $total_pages = ceil($number_of_rows / $results_per_page);
    $first_result = ($current_page - 1) * $results_per_page;

    $arr = $repo->getAllNonReturnedBooksPaginated($first_result,$results_per_page);

    if (empty($arr)) {
        setAlert('No late returns!', 'success');
    }
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>

<?php
if (!empty($arr)) {
    $data = '<table class="ui selectable celled table">
    <thead>
    <tr>
        <td class="one wide">ID</td>
        <td class="one wide">Book Title</td>';
    if (isAdmin()) {
        $data .= '<td class="one wide">User ID</td>
                     <td class="one wide">User Name</td>
                    <td class="one wide">Available Copies</td>';
    }
    $data .= '<td class="one wide">Request Date</td>
                     <td class="one wide">Issue Date</td>
                     <td class="one wide">Return Date</td>';
    if (isAdmin()) {
        $data .= '<td class="one wide">Action</td>';
    }

    $data .= '</tr>
    </thead>
    <tbody>';
    foreach ($arr as $request) {
        if (isAdmin()) {
            $data .= '<tr>
                    <td>' . $request->getId() . '</td>
                    <td>' . $request->getBook()->getTitle() . '</td>
                    <td>' . $request->getUser()->getId() . '</td>
                    <td>' . $request->getUser()->getUserDetails()->getFirstName() . '</td>
                    <td>' . $request->getBook()->getAvailableCopies() . '</td>
                    <td>' . $request->getRequestDate() . '</td>
                    <td>' . $request->getIssueDate() . '</td>
                    <td>' . $request->getReturnDate() . '</td>
                    <td>
                       <div class="ui mini basic vertical buttons">
                           <a class="ui basic blue button" href="' . APP_URL_BASE . '/mail/send.php?email=' . $request->getUser()->getEmail() . '">Email</a>
                       </div>
                    </td>
               </tr>';
        } else {
            $data .= '<tr>
                       <td>' . $request->getId() . '</td>
                       <td>' . $request->getBook()->getTitle() . '</td>
                       <td>' . $request->getRequestDate() . '</td>
                       <td>' . $request->getIssueDate() . '</td>
                       <td>' . $request->getReturnDate() . '</td>
               </tr>';
        }
    }
    echo $data . '</tbody></table>';
}
if (!empty($arr)){
    printPagination($current_page,$total_pages,APP_URL_BASE.'/circulation/delays.php?');
}
require_once '../templates/footer.php';