<?php
$page_title = 'Requests';
require_once '../functions/Enums/Status.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/RequestsRepository.php';
require_once '../templates/navbar.php';

/* @var Request $request */
$repo = new RequestsRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;
if (isAdmin()) {
    $number_of_rows = $repo->totalPendingBooks();
    $total_pages = ceil($number_of_rows / $results_per_page);
    $first_result = ($current_page - 1) * $results_per_page;

    $arr = $repo->getPendingsPaginated($first_result,$results_per_page);

} else {
    $number_of_rows = $repo->totalPendingBooksByUserId(getUser()['id']);
    $total_pages = ceil($number_of_rows / $results_per_page);
    $first_result = ($current_page - 1) * $results_per_page;

    $arr = $repo->findPendingsByUserIdPaginated(getUser()['id'], $first_result,$results_per_page);
}

alertBox();

$data = '<div class="row"><div class="ui placeholder segment">
<table class="ui selectable celled table">
    <thead>
    <tr>
        <th class="one wide">ID</th>
        <th class="one wide">Book Title</th>
        <th class="one wide">Available Copies</th>';
if (isAdmin()) {
    $data .= '<th class="one wide">User ID</th>
                     <th class="one wide">User Name</th>';
}
$data .= '<th class="one wide">Status</th>
        <th class="one wide">Request Date</th>
        <th class="one wide">Issue Date</th>
        <th class="one wide">Return Date</th>
        <th class="one wide">Action</th>
    </tr>
    </thead>
    <tbody>';
foreach ($arr as $request) {
    if (isAdmin()) {
        $data .= '<tr>
                  <form action="../functions/Validators/RequestValidator.php" method="POST">
                    <td>' . $request->getId() . '</td>
                    <td>' . $request->getBook()->getTitle() . '</td>
                    <td>' . $request->getBook()->getAvailableCopies() . '</td>
                    <td>' . $request->getUser()->getId() . '</td>
                    <td>' . $request->getUser()->getUserDetails()->getFirstName() . '</td>
                    <td>' . $request->getStatus()->getName() . '</td>
                    <td>' . $request->getRequestDate() . '</td>
                    <td><input name="issue_date" type="date"></td>
                    <td><input name="return_date" type="date"></td>
                    <td>
                       <div class="ui mini basic vertical buttons">
                           <button name="approve" class="ui green basic button" type="submit">Approve</button>
                           <button name="reject" class="ui red basic button" type="submit">Reject</button>
                           <input type="hidden" name="request_id" value="' . $request->getId() . '">
                       </div>
                    </td>
                  </form>
               </tr>';
    } elseif (!isAdmin() && $request->getUserRead() == 0) {
        $data .= '<tr>
                   <form action="../functions/Validators/RequestValidator.php" method="POST">
                       <td>' . $request->getId() . '</td>
                       <td>' . $request->getBook()->getTitle() . '</td>
                       <td>' . $request->getBook()->getAvailableCopies() . '</td>
                       <td>' . $request->getStatus()->getName() . '</td>
                       <td>' . $request->getRequestDate() . '</td>
                       <td>' . $request->getIssueDate() . '</td>
                       <td>' . $request->getReturnDate() . '</td>
                       <td>
                           <div class="ui mini basic vertical buttons">
                               <input name="clear" class="ui green basic button" type="submit" value="Clear"/>
                               <input type="hidden" name="request_id" value="' . $request->getId() . '">
                               <input type="hidden" name="issue_date" value="' . null . '">
                               <input type="hidden" name="return_date" value="' . null . '">
                           </div>
                       </td>
                   </form>
               </tr>';
    }
}
echo $data . '</tbody></table></div></div>';

if (!empty($arr)){
    printPagination($current_page,$total_pages,APP_URL_BASE.'/requests/index.php?');
}

require_once '../templates/footer.php';