</div>
</div>
</div>

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>


<script src="../assets/js/templates_navbar.js"></script>
<?php
if (isset($page_title)) {
    switch ($page_title) {
        case 'Add Book':
            echo '<script src="../assets/js/books_add.js"></script>';
            break;
        case 'Browse Book':
            echo '
            <script src="../assets/js/books_browse.js"></script>
            <script src="../assets/js/jquery.dataTables.min.js"></script>
            <script src="../assets/js/dataTables.bootstrap.min.js"></script>
            ';
            break;
        case 'Book Edit':
            echo '<script src="../assets/js/books_edit.js"></script>';
            break;
        case 'Add Member':
            echo '<script src="../assets/js/members_add.js"></script>';
            break;
        case 'Browse Members':
            echo '
            <script src="../assets/js/books_browse.js"></script>
            <script src="../assets/js/jquery.dataTables.min.js"></script>
            <script src="../assets/js/dataTables.bootstrap.min.js"></script>
            ';
            break;
        case 'Confirm Password':
            echo '<script src="../assets/js/confirm_password.js"></script>';
            break;
        case 'Add Institute':
            echo '
                 <script>
                    $("#summernote").summernote({
                        placeholder: "    All copyright, trade marks, design rights, patents and other intellectual property rights (registered and unregistered) in and on LMS Online Services and LMS Content belong to the LMS and/or third parties (which may include you or other users.)\n" +
                        "    The LMS reserves all of its rights in LMS Content and LMS Online Services. Nothing in the Terms grants you a right or licence to use any trade mark, design right or copyright owned or controlled by the LMS or any other third party except as expressly provided in the Terms.",
                        tabsize:5,
                        height: 150
                    });
                </script>
            ';
            break;
        case 'Books by Category':
            echo '
            <script src="../assets/js/books_by.js"></script>
            <script src="../assets/js/jquery.dataTables.min.js"></script>
            <script src="../assets/js/dataTables.bootstrap.min.js"></script>
            ';
            break;
        case 'Books by Shelf':
            echo '
            <script src="../assets/js/books_by.js"></script>
            <script src="../assets/js/jquery.dataTables.min.js"></script>
            <script src="../assets/js/dataTables.bootstrap.min.js"></script>
            ';
            break;
        case 'Reset Password':
            echo '
            <script src="../assets/js/reset_password.js"></script>
            ';
    }
}
?>
</body>
</html>