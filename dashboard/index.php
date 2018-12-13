<?php
$page_title = 'Dashboard';
require_once '../templates/navbar.php';
?>

    <div class="row">
        <?php alertBox(); ?>
    </div>
    <div class="ui card">
        <div class="content">
            <div class="header">Cute Dog</div>
            <div class="meta">2 days ago</div>
            <div class="description">
                <p>Cute dogs come in a variety of shapes and sizes. Some cute dogs are cute for their adorable faces,
                    others for their tiny stature, and even others for their massive size.</p>
                <p>Many people also have their own barometers for what makes a cute dog.</p>
            </div>
        </div>
    </div>

<?php
require_once '../templates/footer.php';