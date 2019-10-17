<!doctype html>
<html lang="en-GB">
<head>
    <title>Task App</title>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<style>
    .col-text {word-wrap: break-word};
</style>

<body style="padding: 20px;">

<h2>Task Application</h2>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="/">Task List</a>
            <?php if(self::getRoute() != 'edit'): ?>
            <a class="nav-item nav-link" href="add">Add Task</a>
            <?php endif; ?>
            <?php if(self::isAdmin()): ?>
            <a class="nav-item nav-link" href="logout">Logged in as admin: Logout</a>
            <?php else: ?>
            <a class="nav-item nav-link" href="login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>