<?php

define("TASKS_LIST", LOCAL_FOLDER . "/src/Tasks/Views/TaskList.inc");
define("LOGIN", LOCAL_FOLDER . "/src/Tasks/Views/Login.inc");
define("TASKS_ADD", LOCAL_FOLDER . "/src/Tasks/Views/TaskAdd.inc");
define("TASKS_CONFIRM", LOCAL_FOLDER . "/src/Tasks/Views/TaskConfirm.inc");
define("TASKS_EDIT", LOCAL_FOLDER . "/src/Tasks/Views/TaskEdit.inc");

include_once(LOCAL_FOLDER . "/src/Tasks/Models/Task.php");

class TaskController
{
	public $errorMessages = array();
    public $currentRoute = '';

	function __construct()
    {
        $this->_postbackProcess();
    }

    /**
     *
     */
    private function _postbackProcess()
    {
        $error = false;
        $request = $_POST;

        if (!isset($request)) {
            return;
        }

        // login admin
        if (isset($request['login'])) {

            $adminName = trim(strip_tags($request['inputAdminname']));
            $adminPassword = trim(strip_tags($request['inputPassword']));

            if ($adminName == ADMIN_NAME && $adminPassword == ADMIN_PASS) {
                $_SESSION['admin_login'] = 1;
                $this->Redirect();
            }

            $this->errorMessages['inputAdminData'] = "Invalid or black admin credit details";
        }

        // create task
        if (isset($request['create'])) {
            $userName = trim(strip_tags($request['inputUsername']));

            if (strlen($userName) < 3) {
                $this->errorMessages['inputUsername'] = "The username is too short";
                $error = true;
            }

            $email = trim(strip_tags($request['inputEmail']));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errorMessages['inputEmail'] = "Invalid  email address";
                $error = true;
            }

            $tasktext = trim(strip_tags($request['inputTaskText']));
            if (strlen($tasktext) < 3) {
                $this->errorMessages['inputTaskText'] = "The task text is too short ( < 3 symbols)";
                $error = true;
            }

            if (!$error) {
                $task = new Task();
                $params = array('username' => $userName, 'email' => $email, 'tasktext' => $tasktext);
                if ($task->create($params)) {
                    $this->Redirect("confirmation");
                }
            }
        }

        // update task
        if (isset($request['update']) && isset($request['id']) && $request['id'] > 0) {
            $tasktext = trim(strip_tags($request['inputTaskText']));
            if (strlen($tasktext) < 3) {
                $this->errorMessages['inputTaskText'] = "The task text is too short ( < 3 symbols)";
                $error = true;
            }

            $taskCompleted = isset($request['checkboxTaskCompleted']) ? 'yes' : 'no';

            if (!$error) {
                $task = new Task();
                $oldData = $task->load($_REQUEST['id']);

                $params = array('id' => $_REQUEST['id'], 'tasktext' => $tasktext);

                // update text by admin
                if ($oldData['tasktext'] != $tasktext) {
                    $params['status'] = 'update_by_admin';
                }

                if($taskCompleted == 'yes') {
                    $params['task_completed'] = $taskCompleted;
                }

                if ($task->update($params)) {
                    $this->Redirect("/");
                }
            }
        }
    }

    /**
     * @param $field
     * @return bool
     */
	public function getError($field)
    {
        return isset($this->errorMessages[$field]) ? $this->errorMessages[$field] : false;
    }

    /**
     * @return string
     */
	public function displayView()
    {
        ob_start();

        $URL = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = trim($URL, '/');
        $route = urldecode($route);

        if ($route == "logout") {
            session_destroy();
            $this->Redirect();
        }

        switch ($route) {
            case 'login';
                $view = LOGIN;
                break;
            case 'add';
                $view = TASKS_ADD;
                break;
            case 'edit';
                $view = TASKS_EDIT;
                break;
            case 'confirmation';
                $view = TASKS_CONFIRM;
                break;
            default:
                $view = TASKS_LIST;
        }

        $this->currentRoute = $route;

        if (isset($_REQUEST['page'])) {
            $_SESSION['page'] = (int)$_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1;
        }
        if (isset($_REQUEST['sort_field'])) {
            $_SESSION['sort_field'] = in_array($_REQUEST['sort_field'], array('username','email','status'))
                ? $_REQUEST['sort_field'] : 'username';
        }
        if (isset($_REQUEST['sort_direction'])) {
            $_SESSION['sort_direction'] = in_array($_REQUEST['sort_direction'],array('asc','desc'))
                ? $_REQUEST['sort_direction'] : 'asc';
        }
        require_once($view);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public function isAdmin()
    {
        return (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] = 1) ? true : false;
    }

    public function getRoute()
    {
        return $this->currentRoute;
    }

    /**
     * @param $template
     */
    public function Redirect($template = "/")
    {
        Header("Location: {$template}");
    }
}