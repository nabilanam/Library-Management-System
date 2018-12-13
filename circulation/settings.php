<?php
$page_title = 'Membership Limitations';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/UserTypesRepository.php';


if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

$repo = new UserTypesRepository();

$arr = $repo->getAll();
?>

<div class="row">
    <?php alertBox() ?>
</div>

    <table class="ui selectable celled table">
        <thead>
            <tr>
                <th>Member type</th>
                <th>Max Days</th>
                <th>Max Books</th>
                <th>Fine Per Day</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($arr as $type) {
            echo '<tr>
                      <td>' . $type->getName() . '</td>
                      <td>' . $type->getDayLimit() . '</td>
                      <td>' . $type->getBookLimit() . '</td>
                      <td>' . $type->getFinePerDay() . '</td>
                      <td>
                      <form action="edit.php" method="get">
                          <button type="submit" class="ui blue button">Edit</button>
                          <input type="hidden" name="type_id" value="'.$type->getId().'">
                      </form>
                      </td>
                  </tr>';
        }
        ?>
        </tbody>
    </table>
<?php
require_once '../templates/footer.php';