<div class="row"></div>
</div> <!-- container -->
</div> <!-- column -->
</div> <!-- container -->

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>


<script src="../assets/js/templates_navbar.js"></script>
<script>
    $('.message .close')
        .on('click', function () {
            $(this)
                .closest('.message')
                .transition('fade')
            ;
        })
    ;
</script>
<?php
if (isset($page_title)) {
    switch ($page_title) {
        case 'Add Book':
            echo '<script src="' . APP_ASSETS_JS . '/books_add.js"></script>';
            break;
        case 'Browse Books':
            echo '
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>
            ';
            break;
        case 'Books by Category':
            echo '
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>
            ';
            break;
        case 'Books by Shelf':
            echo '
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>
            ';
            break;
        case 'Search Books':
            echo '
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>
            ';
            break;
        case 'Book Edit':
            echo '<script src="' . APP_ASSETS_JS . '/books_edit.js"></script>';
            break;
        case 'Add Member':
            echo '<script src="' . APP_ASSETS_JS . '/members_add.js"></script>';
            break;
        case 'Edit Member':
            echo '<script src="' . APP_ASSETS_JS . '/members_edit.js"></script>';
            break;
        case 'Browse Members':
            echo '
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>
            ';
            break;
        case 'Confirm Password':
            echo '<script src="' . APP_ASSETS_JS . '/confirm_password.js"></script>';
            break;
        case 'Add Institute':
            echo '<script src="' . APP_ASSETS_JS . '/institutes_add.js"></script>';
            break;
        case 'Browse Institute':
            echo '<script src="' . APP_ASSETS_JS . '/institutes_add.js"></script>
            <script src="' . APP_ASSETS_JS . '/data_table_config.js"></script>
            <script src="' . APP_ASSETS_JS . '/jquery.dataTables.min.js"></script>
            <script src="' . APP_ASSETS_JS . '/dataTables.semanticui.min.js"></script>';
            break;
        case 'Edit Institute':
            echo '<script src="' . APP_ASSETS_JS . '/institutes_edit.js"></script>';
            break;
        case 'Reset Password':
            echo '<script src="' . APP_ASSETS_JS . '/reset_password.js"></script>';
    }
}
?>
</body>
</html>