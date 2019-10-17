<?php
/** @var $task  */
$task = new Task();
$sortDirection = $task->getSortDirection() == 'asc' ? 'desc' : 'asc';
?>

<?php include("Header.inc") ?>

<?php

    if($taskList = $task->getAllTask()) {

        echo "<table class='table'><thead>";
        echo "<tr>";
        echo "<th scope='col'><a href='?sort_field=username&sort_direction=" . $sortDirection . "'>Username</a></th>";
        echo "<th scope='col'><a href='?sort_field=email&sort_direction=" . $sortDirection . "'>Email</th>";
        echo "<th scope='col'>Task Text</td>";
        echo "<th scope='col'><a href='?sort_field=status&sort_direction=" . $sortDirection . "'>Status</a></th>";
        echo "<th scope='col'>Completed</th>";
        if(self::isAdmin()) {
            echo "<th scope='col'></th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";

        foreach ($taskList as $taskRow) {
            echo "<tr>";
                echo "<th>" . $taskRow['username'] . "</th>";
                echo "<th>" . $taskRow['email'] . "</th>";
                echo "<th class='col-text'>" . $taskRow['tasktext'] . "</th>";
                echo "<th>" . $taskRow['status'] . "</th>";
                echo "<th>" . $taskRow['task_completed'] . "</th>";
            if(self::isAdmin()) {
                echo "<th><a href='edit?id=" . $taskRow['id'] . "'>Edit</th>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // pagination
        echo $task->getPagination();

    } else {
        echo "No tasks yet";
    }

?>

<?php include("Footer.inc") ?>