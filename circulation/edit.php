<?php
$page_title = 'Change Limits';
require_once '../templates/navbar.php';
require_once '../functions/Models/UserType.php';
require_once '../functions/Enums/UserTypes.php';
require_once '../functions/Repositories/UserTypesRepository.php';


if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

$repo = new UserTypesRepository();

if (isset($_POST['type_id'])) {
    $type = $repo->findById($_POST['type_id']);

    if (!$type) {
        setAlert('Invalid type!', 'danger');
        redirectTo(APP_URL_BASE . '/circulation/settings.php');
    }

    $type->setBookLimit($_POST['book_limit']);
    $type->setDayLimit($_POST['day_limit']);
    $type->setFinePerDay($_POST['fpd']);

    if ($repo->update($type)) {
        setAlert('Successfully updated!', 'success');
        redirectTo(APP_URL_BASE . '/circulation/settings.php');
    }
    setAlert('Nothing to update!', 'warning');
    redirectTo(APP_URL_BASE . '/circulation/edit.php?type_id='.$_POST['type_id']);

} elseif (isset($_GET['type_id'])) {
    $type = $repo->findById($_GET['type_id']);

    if (!$type) {
        setAlert('Invalid type!', 'danger');
        redirectTo(APP_URL_BASE . '/circulation/settings.php');
    }
} else {
    setAlert('Invalid request!', 'danger');
    redirectTo(APP_URL_BASE . '/circulation/settings.php');
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>

    <div class="ui placeholder segment">
        <form class="ui form" action="" method="POST">
            <div class="field">
                <label><?php echo $type->getName() ?></label>
            </div>

            <div class="field">
                <label for="book_limit">Book Limit *</label>
                <input type="text" name="book_limit" id="book_limit" style="text-align: center"
                       value="<?php echo $type->getBookLimit() ?>" placeholder="0123456789">
            </div>
            <div class="field">
                <label for="day_limit">Day Limit *</label>
                <input type="text" name="day_limit" id="day_limit" style="text-align: center"
                       value="<?php echo $type->getDayLimit() ?>" placeholder="johndoe@anonymous.com">
            </div>

            <div class="field">
                <label for="fpd">Fine Per Day *</label>
                <input type="text" name="fpd" id="fpd" style="text-align: center"
                       value="<?php echo $type->getFinePerDay() ?>"
                       placeholder="H#01, S#01, Bosumoti, Dhaka">
            </div>
            <div class="field">
                <button type="submit" class="ui blue button">Save</button>
            </div>
            <input type="hidden" name="type_id" value="<?php echo $type->getId() ?>">
        </form>
    </div>

<?php
require_once '../templates/footer.php';