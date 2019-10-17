<?php

$inputUsername = isset($_POST['inputUsername']) ? $_POST['inputUsername'] : '';
$inputEmail = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : '';
$inputTaskText = isset($_POST['inputTaskText']) ? $_POST['inputTaskText'] : '';

$errorUserName = self::getError('inputUsername');
$errorEmail = self::getError('inputEmail');
$errorTaskText = self::getError('inputTaskText');

?>

<?php include("Header.inc") ?>

<h4>Add task</h4>

<form action="add" method="post">
<div class="form-group">
    <label for="inputUsername">Username</label>
    <input type="text" class="form-control" id="inputUsername" name="inputUsername" value="<?php echo $inputUsername ?>" placeholder="Enter username">
    <?php if($errorUserName): ?>
    <div class="alert alert-dark" role="alert">
        <?php echo $errorUserName; ?>
    </div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="inputEmail">Email</label>
    <input type="email" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo $inputEmail ?>" placeholder="Enter email">
    <?php if($errorEmail): ?>
    <div class="alert alert-dark" role="alert">
        <?php echo $errorEmail ?>
    </div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="inputTaskText">Task Text</label>
    <textarea class="form-control" id="inputTaskText" name="inputTaskText" rows="3"><?php echo $inputTaskText ?></textarea>
    <?php if($errorTaskText): ?>
    <div class="alert alert-dark" role="alert">
        <?php echo self::getError('inputTaskText') ?>
    </div>
    <?php endif; ?>
</div>

<button type="submit" class="btn btn-primary mb-2" name="create">Create</button>

</form>
<?php include("Footer.inc") ?>