<?php
$page_title = 'Add Institute';
require_once '../templates/navbar.php';

alertBox();
?>

<!---------------------------------- Form ------------------------------------------>

<form action="../functions/Validators/instituteValidator.php" method="POST" enctype="multipart/form-data">
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading" style="background-color:lightseagreen ">General Setting</div>
        <div class="panel-body">
            <div class="col-md-6 col-md-offset-2">

                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Institute Name</label>
                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" id="name"
                                   placeholder="Write down the institute name">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="address" class="col-md-2 control-label">Institute Address</label>
                        <div class="col-md-10">
                            <input type="text" name="address" class="form-control" id="address"
                                   placeholder="Please write down the institute address">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="email" class="col-md-2 control-label">Institute Email</label>
                        <div class="col-md-10">
                            <input type="email" name="email" class="form-control" id="email"
                                   placeholder="Write down the institute email address ">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-md-2 control-label">Institute Phone</label>
                        <div class="col-md-10">
                            <input type="text" name="phone" class="form-control" id="phone"
                                   placeholder="Please write down the institute address">
                        </div>
                    </div>

                    <!--- Institute type-->

                    <div class="form-group">
                        <label for="logo" class="col-md-2 control-label">Institute Logo</label>
                        <div class="col-md-6 col-md-offset-3">
                            <div class="ui tiny images">
                                <img class="ui image" src="../settings/UU.png">
                            </div>
                            <input type="file" name="logo" id="logo">
                            <p class="help-block">Max Dimension : 600 x 300,Max Size : 200KB, Format : png.</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="inputPassword3" class="col-md-2 control-label">Language</label>
                        <div class="col-md-10">
                            <div class="field">
                                <select name="language" class="ui fluid search dropdown">
                                    <option value="English">English</option>
                                    <option value="Arabic">Arabic</option>
                                    <option value="Bangla">Bangla</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- List group -->

                    <div class="form-group">
                        <label for="inputPassword3" class="col-md-8 col- control-label">Terms and Conditions</label>
                        <div class="col-md-10 col-md-offset-2">
                            <div id="summernote"></div>

                            <script>
                                $('#summernote').summernote({
                                    placeholder: '    All copyright, trade marks, design rights, patents and other intellectual property rights (registered and unregistered) in and on LMS Online Services and LMS Content belong to the LMS and/or third parties (which may include you or other users.)\n' +
                                        '    The LMS reserves all of its rights in LMS Content and LMS Online Services. Nothing in the Terms grants you a right or licence to use any trade mark, design right or copyright owned or controlled by the LMS or any other third party except as expressly provided in the Terms.',
                                    tabsize: 5,
                                    height: 150
                                });
                            </script>
                        </div>
                    </div>


                    <!--Save or cancel button-->

                    <!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-5">
                            <div class="ui buttons">
                                <button type="submit" name="save" class="ui positive button" style="font-size: medium">
                                    Save
                                </button>
                                <div class="or"></div>
                                <button class="ui  button" style="medium">Cancel</button>
                            </div>
                        </div>
                    </div>


</form>
<?php
require_once '../templates/footer.php';
?>
