<?php
require_once '../templates/modal.php';
/* @var User[] $arr */
function printMemberTable($arr)
{
    modal('Delete Member', 'Are you sure you want to delete this member?');

    $data = '<table class="ui selectable celled table">
        <thead>
        <tr>
            <th class="one wide">ID</th>
            <th class="one wide">First Name</th>
            <th class="one wide">Last Name</th>
            <th class="one wide">User Type</th>
            <th class="one wide">Email</th>
            <th class="one wide">Mobile</th>
            <th class="one wide">Present Address</th>
            <th class="one wide">Permanent Address</th>
            <th class="one wide">Action</th>
        </tr>
        </thead>
        <tbody>';
    foreach ($arr as $user) {
        $details = $user->getUserDetails();
        $data .= '<tr>
                             <td>' . $user->getId() . '</td>
                             <td>' . $details->getFirstName() . '</td>
                             <td>' . $details->getLastName() . '</td>
                             <td>' . $user->getUserType()->getName() . '</td>
                             <td>' . $user->getEmail() . '</td>
                             <td>' . $details->getMobileNo() . '</td>
                             <td>' . $details->getPresentAddress() . '</td>
                             <td>' . $details->getPermanentAddress() . '</td>
                             <td>
                                <div class="ui mini basic vertical buttons">
                                 <a class="ui green basic button" href="' . APP_URL_BASE . '/members/view.php?member_id=' . $user->getId() . '">View</a>';

        if ($user->getUserType()->getId() != 1) {
            $data .= '<a class="ui blue basic button" href="' . APP_URL_BASE . '/members/edit.php?member_id=' . $user->getId() . '">Edit</a>
                       <form id="delete_form" action="" method="POST">
                          <input type="hidden" name="delete_id" value="' . $user->getId() . '">
                          <a class="ui red basic button" href="#" role="button" 
                          onclick="$(\'.mini.modal\')
                          .modal({
                                closable  : true,
                                onApprove : function() {
                                    $(\'#delete_form\').submit();
                                    return true;
                                }})
                          .modal(\'show\')">Delete</a>
                       </form>';
        }
        $data .= '<a class="ui gray basic button" href="' . APP_URL_BASE . '/circulation/history.php?user_id=' . $user->getId() . '">History</a>
                     </div></td></tr>';
    }
    echo $data . '</tbody></table>';
}

?>