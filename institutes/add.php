<?php
$page_title = 'Add Institute';
require_once '../templates/navbar.php';

?>

<div class="row">
    <?php alertBox(); ?>
</div>

<div class="ui placeholder segment">
    <form class="ui form" action="../functions/Validators/InstituteValidator.php" method="POST"
          enctype="multipart/form-data">

        <div class="field">
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" placeholder="Uttara University">
        </div>

        <div class="field">
            <label for="address">Address *</label>
            <input type="text" name="address" id="address" placeholder="R#15, S#06, Azampur, Uttara">
        </div>

        <div class="field">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" placeholder="info@uttarauniversity.edu.bd">
        </div>

        <div class="field">
            <label for="logo">Logo *</label>
            <input type="file" name="logo" id="logo">
            <p class="help-block">Format : png, jpg</p>
        </div>

        <div class="field">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" placeholder="+8802-58952280">
        </div>

        <div class="field">
            <button type="button" name="save_institute" id="save_institute" class="ui blue button">Save</button>
        </div>
    </form>
</div>
<?php
require_once '../templates/footer.php';
?>
