<?php
$page_title = 'Change Limits';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/MailSettingsRepository.php';


if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

$repo = new MailSettingsRepository();

if (isset($_POST['save'])) {

    $host = trim($_POST['host']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $port = trim($_POST['port']);

    if (empty($host) || empty($username) || empty($password) || empty($port)){
        setAlert('All field is required!', 'danger');
        redirectTo(APP_URL_BASE . '/mail/settings.php');
    }
    $settings = new MailSettings($host,$username,$password,$port);

    if ($repo->save($settings)) {
        setAlert('Successfully updated!', 'success');
        redirectTo(APP_URL_BASE . '/mail/settings.php');
    }
    setAlert('Nothing to update!', 'warning');
    redirectTo(APP_URL_BASE . '/mail/settings.php');

} else {
    $settings = $repo->find();
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>

    <div class="ui placeholder segment">
        <form class="ui form" action="" method="POST">

            <div class="field">
                <label for="host">Host *</label>
                <input type="text" name="host" id="host" style="text-align: center"
                       value="<?php echo $settings ? $settings->getHost() : '' ?>" placeholder="smtp.domain.com">
            </div>
            <div class="field">
                <label for="username">Username *</label>
                <input type="text" name="username" id="username" style="text-align: center"
                       value="<?php echo $settings ? $settings->getUsername() : '' ?>" placeholder="johndoe@domain.com">
            </div>
            <div class="field">
                <label for="port">Port *</label>
                <input type="text" name="port" id="port" style="text-align: center"
                       value="<?php echo $settings ? $settings->getPort() : '' ?>" placeholder="123">
            </div>

            <div class="field">
                <label for="password">Password *</label>
                <input type="password" name="password" id="password" style="text-align: center"
                       value="<?php echo $settings ? $settings->getPassword() : '' ?>"
                       placeholder="123456">
            </div>
            <div class="field">
                <button type="submit" name="save" class="ui blue button">Save</button>
            </div>
        </form>
    </div>

<?php
require_once '../templates/footer.php';