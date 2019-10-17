<?php
$inputAdminname = isset($_POST['inputAdminname']) ? $_POST['inputAdminname'] : '';
$errorAdminData = self::getError('inputAdminData');
?>

<?php include("Header.inc") ?>

<h4>Login</h4>

<form action="login" method="post">

    <div class="form-group">
        <label for="inputAdminname">Admin name</label>
        <input type="text" class="form-control" id="inputAdminname" name="inputAdminname" value="<?php echo $inputAdminname ?>" placeholder="Enter admin name">
    </div>

    <div class="form-group">
        <label for="inputPassword">Admin password</label>
        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Enter admin password">
    </div>

    <button type="submit" class="btn btn-primary mb-2" name="login">Login</button>

    <?php if($errorAdminData): ?>
    <div class="alert alert-dark" role="alert">
        <?php echo $errorAdminData ?>
    </div>
    <?php endif; ?>

</form>

<?php include("Footer.inc") ?>