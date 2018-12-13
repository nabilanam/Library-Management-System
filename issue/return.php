<?php
$page_title = 'Return';
require_once '../templates/navbar.php';

?>
    <div class="row">
        <?php alertBox(); ?>
    </div>

    <div class="ui placeholder segment">
        <div class="column six wide">
            <form class="ui form" action="../functions/Validators/ReturnValidator.php" method="post">
                <?php if (!isset($_SESSION['fine'])) { ?>
                    <div class="row">
                        <div class="ui two column very relaxed stackable grid">

                            <div class="column middle aligned">
                                <div class="field">
                                    <label for="request_id">Request ID :</label>
                                    <input type="text" id="request_id" name="request_id" style="text-align: center">
                                </div>
                                <div class="field">
                                    <button class="ui blue button" type="submit" name="calculate1">Calculate</button>
                                </div>
                            </div>

                            <div class="column">
                                <div class="field">
                                    <label for="user_id">User ID :</label>
                                    <input type="text" id="user_id" name="user_id" style="text-align: center">
                                </div>
                                <div class="field">
                                    <label for="book_id">Book ID :</label>
                                    <input type="text" id="book_id" name="book_id" style="text-align: center">
                                </div>
                                <div class="field">
                                    <label for="issue_date">Issue Date :</label>
                                    <input type="date" id="issue_date" name="issue_date" style="text-align: center">
                                </div>
                                <div class="field">
                                    <button class="ui blue button" type="submit" name="calculate2">Calculate</button>
                                </div>
                            </div>
                        </div>

                        <div class="ui vertical divider">
                            Or
                        </div>
                    </div>
                <?php } ?>

                <?php if (isset($_SESSION['fine'])) { ?>
                    <div class="field">
                        <label for="fee">Fee :</label>
                        <input type="text" readonly id="fee" name="fee" value="<?php echo $_SESSION['fine']; unset($_SESSION['fine']); ?>"
                               style="text-align: center">
                    </div>
                    <div class="fields">
                        <div class="field">
                            <button class="ui blue button" type="submit" name="completed">Submit</button>
                        </div>
                        <div class="field">
                            <button class="ui blue button" type="submit" name="cancelled">Cancel</button>
                        </div>
                        <input type="hidden" name="request_id" value="<?php echo $_SESSION['req_id']; unset($_SESSION['req_id']); ?>">
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>


<?php
require_once '../templates/footer.php';