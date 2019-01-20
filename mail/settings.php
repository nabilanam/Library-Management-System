<?php
$page_title = 'Change Limits';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/MailSettingsRepository.php';
require_once '../functions/Scheduler/MailAlertTask.php';


if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

$repo = new MailSettingsRepository();
$task = new MailAlertTask();

if (isset($_POST['save'])) {

    $host = trim($_POST['host']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $port = trim($_POST['port']);

    if (isWindowsOS()){
        $task_exists = $task->exists();
        if ($_POST['automail'] == 'on') {
            $task->create();
        } else {
            $task->delete();
        }
    }

    if (empty($host) || empty($username) || empty($password) || empty($port)){
        setAlert('All field is required!', 'danger');
        redirectTo(APP_URL_BASE . '/mail/settings.php');
    }

    $settings = new MailSettings($host,$username,$password,$port);

    if ($repo->save($settings) || (isWindowsOS() && $task_exists != $task->exists())) {
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

    <div class="column six wide">
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

                <?php
                if (isWindowsOS()) {
                    ?>
                    <div class="inline field">
                        <input type="checkbox" name="automail" id="automail" style="text-align: center"
                            <?php echo $task->exists() ? "checked" : "" ?>>
                        <label for="automail">Alert delayed users at 12 am (Daily)</label>
                    </div>
                    <?php
                }
                ?>

                <div class="field">
                    <button type="submit" name="save" class="ui blue button">Save</button>
                </div>
            </form>
        </div>
    </div>

<?php
require_once '../templates/footer.php';