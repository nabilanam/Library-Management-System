<?php
$page_title = 'Add Book';
require_once '../functions/Repositories/SimpleRepositoryFacade.php';
require_once '../functions/Models/DTO.php';
require_once '../templates/navbar.php';
if (!isAdmin()){
    redirectTo(APP_BASE_URL.'/dashboard');
}
?>

    <div class="ui container">
        <?php alertBox() ?>

        <form id="ui_form" class="ui form" method="POST" action="../functions/Validators/BookValidator.php"
              enctype="multipart/form-data" style="background-color:#f7f7f7">
            <h3 class="ui dividing header">Add Book</h3>

            <!--first row-->
            <div class="fields">
                <div class="six wide field" style="text-align: center">
                    <label for="isbn">ISBN</label>
                    <input id="isbn" name="isbn" type="text" placeholder="0123456789">
                </div>
                <div class="four wide field " style="text-align: center">
                    <label for="subtitle">Subtitle</label>
                    <input id="subtitle" name="subtitle" type="text"
                           placeholder="Write down the subtitle name">
                </div>
                <div class=" six wide field" style="text-align: center">
                    <label for="title">Title *</label>
                    <input id="title" name="title" type="text" placeholder="Write down the title name">
                </div>
            </div>

            <!--second row-->
            <div class="fields">
                <div class=" six wide field" style="text-align: center">
                    <label for="authors">Author *</label>
                    <select id="authors" name="authors[]" multiple=""
                            class="ui search fluid dropdown authors">
                        <?php
                        $repo = SimpleRepositoryFacade::getAuthorsRepository();
                        $arr = $repo->getAll();
                        foreach ($arr as $dto) {
                            echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                        }
                        unset($repo);
                        ?>
                    </select>
                </div>
                <div class="four wide field " style="text-align: center">
                    <label for="edition">Edition *</label>
                    <input id="edition" name="edition" type="text" placeholder="Write down the title name">
                </div>
                <div class="six wide field" style="text-align: center">
                    <label for="edition_year">Edition Year</label>
                    <input id="edition_year" name="edition_year" type="text"
                           placeholder="Write down the subtitle name">
                </div>
            </div>

            <!--third row-->
            <div class="fields">
                <div class=" six wide field" style="text-align: center">
                    <label for="copies">Copies *</label>
                    <input id="copies" name="copies" type="text" placeholder="25">
                </div>
                <div class=" four wide field" style="text-align: center">
                    <label for="price">Price *</label>
                    <input id="price" name="price" type="text" placeholder="Write down the ISBN name">
                </div>
                <div class=" six wide field" style="text-align: center">
                    <label for="shelf">Shelf no *</label>
                    <div class="field">
                        <select id="shelf" name="shelf" class="ui fluid search dropdown">
                            <?php
                            $repo = SimpleRepositoryFacade::getShelvesRepository();
                            $arr = $repo->getAll();
                            foreach ($arr as $dto) {
                                echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                            }
                            unset($repo);
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <!--fourth row-->
            <div class="fields">
                <div class="six wide field success" style="text-align: center">
                    <label for="publisher">Publisher</label>
                    <div class="field">
                        <select id="publisher" name="publisher" class="ui fluid search dropdown publisher">
                            <?php
                            $repo = SimpleRepositoryFacade::getPublishersRepository();
                            $arr = $repo->getAll();
                            foreach ($arr as $dto) {
                                echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                            }
                            unset($repo);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="four wide field" style="text-align: center">
                    <label for="publish_year">Publication year *</label>
                    <input id="publish_year" name="publish_year" type="text"
                           placeholder="Write down the title name">
                </div>
                <div class="six wide field" style="text-align: center">
                    <label for="pages">Total Pages *</label>
                    <input id="pages" name="pages" type="text" placeholder="Write down the ISBN name">
                </div>
            </div>

            <!--fifth row-->
            <div class="fields">
                <div class=" six wide field" style="text-align: center">
                    <label for="source">Source *</label>
                    <div class="field">
                        <select id="source" name="source" class="ui fluid search dropdown">
                            <?php
                            $repo = SimpleRepositoryFacade::getSourcesRepository();
                            $arr = $repo->getAll();
                            foreach ($arr as $dto) {
                                echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                            }
                            unset($repo);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="four wide field" style="text-align: center">
                    <label for="condition">Condition *</label>
                    <div class="field">
                        <select id="condition" name="condition" class="ui fluid search dropdown">
                            <?php
                            $repo = SimpleRepositoryFacade::getConditionsRepository();
                            $arr = $repo->getAll();
                            foreach ($arr as $dto) {
                                echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                            }
                            unset($repo);
                            ?>
                        </select>
                    </div>
                </div>
                <div class=" six wide field" style="text-align: center">
                    <label for="category">Category *</label>
                    <div class="field">
                        <select id="category" name="category" class="ui fluid search dropdown"">
                        <?php
                        $repo = SimpleRepositoryFacade::getCategoriesRepository();
                        $arr = $repo->getAll();
                        foreach ($arr as $dto) {
                            echo '<option value="' . $dto->getName() . '">' . $dto->getName() . '</option>';
                        }
                        unset($repo);
                        ?>
                        </select>
                    </div>
                </div>
            </div>

            <!--sixth row-->
            <div class="fields">
                <div class="six wide field" style="text-align: center">
                    <div class="form-group">
                        <label for="note">Note</label>
                        <input id="note" name="note" type="text" placeholder="Write down the title name">
                    </div>
                </div>
                <div class=" four wide field" style="text-align: center">
                    <div class="form-group">
                        <label for="cover_photo">Cover Photo *</label>
                        <input id="cover_photo" name="cover_photo" type="file">
                        <p class="help-block">Max Dimension : 1200 X 2000
                            Max Size : 1024KB Format : jpg,png.</p>
                    </div>
                </div>
                <div class=" six wide field" style="text-align: center">
                    <div class="form-group">
                        <label for="eBook">eBook</label>
                        <input id="eBook" name="eBook" type="file">
                        <p class="help-block">Portable Document File</p>
                    </div>
                </div>
            </div>

            <div class="ui two column centered grid">
                <button id="save_book" name="save_book" type="button" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>


<?php
require_once '../templates/footer.php';
