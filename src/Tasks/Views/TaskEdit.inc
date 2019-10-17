<?php
if (!self::isAdmin()) {
    // simple login without save form data
    self::Redirect("login");
}

$dbData = array();
if(isset($_REQUEST['id']) && (int)$_REQUEST['id']) {
    $task = new Task;
    $dbData = $task->load((int)$_REQUEST['id']);
}

$inputUsername = isset($dbData['username']) ? $dbData['username'] : '';
$inputEmail = isset($dbData['email']) ? $dbData['email'] : '';
$inputTaskText = isset($_POST['inputTaskText']) ? $_POST['inputTaskText'] : $dbData['tasktext'];

$checkboxTaskCompleted = '';

if(isset($_POST['checkboxTaskCompleted']) ||
    (!$_POST && isset($dbData['task_completed']) && $dbData['task_completed'] == 'yes')) {
    $checkboxTaskCompleted = 'checked';
}


$errorTaskText = self::getError('inputTaskText');

?>
<?php include("Header.inc") ?>

<h4>Edit task</h4>

<form action="edit" method="post">
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>">
    <div class="form-group">
        <label for="inputUsername">Username:</label>
        <input type="text" readonly class="form-control-plaintext" id="inputUsername" value="<?php echo $inputUsername ?>">
    </div>

    <div class="form-group">
        <label for="inputEmail">Email:</label>
        <input type="text" readonly class="form-control-plaintext" id="inputEmail" value="<?php echo $inputEmail ?>">
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

    <div class="form-group">
        <label for="checkboxTaskCompleted">Task completed:</label>
        <input type="checkbox" class="form-control-checkbox" id="checkboxTaskCompleted" name="checkboxTaskCompleted" <?php echo $checkboxTaskCompleted ?>>
    </div>

    <button type="submit" class="btn btn-primary mb-2" name="update">Update</button>

</form>

<?php include("Footer.inc") ?>