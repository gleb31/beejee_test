<?php

class Task {

    private $_idField = 'id';
    private $_tableName = 'tasks';
    private $_page = 1;
    private $_rowsOnPage = 3;
    private $_defaultSortField = 'username';

    /**
     * @param $id
     * @return array|bool
     */
    public function load($id)
    {
        $data = base::selectRow("SELECT * FROM " . $this->_tableName . " WHERE " . $this->_idField .
            " = " . base::quote($id));

        if($data) {
            return $data;
        }

        return false;
    }

    /**
     * @param array $params
     */
    public function create($params=array())
    {
        try {

            base::begin();
            $sth = base::prepare("INSERT INTO " . $this->_tableName .
                "(username, email, tasktext, created_date) " .
                "VALUES (:username, :email, :tasktext, NOW())");
            $sth->bindValue(':username', $params['username'], PDO::PARAM_STR);
            $sth->bindValue(':email', $params['email'], PDO::PARAM_STR);
            $sth->bindValue(':tasktext', $params['tasktext'], PDO::PARAM_STR);
            $sth->execute();
            base::commit();

            return true;

        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    /**
     * @param array $params
     */
    public function update($params=array())
    {
        try {

            if(!isset($params['id'])) {
                return false;
            }

            $statusTask = "";
            if (isset($params['status'])) {
                $statusTask = ", status = 'updated_by_admin'";
            }

            $completedTask = "";
            if (isset($params['task_completed'])) {
                $completedTask = ", task_completed = 'yes'";
            }

            base::begin();
            $sth = base::prepare("UPDATE " . $this->_tableName . " SET " .
                " tasktext = :tasktext " . $statusTask . ", updated_date = NOW()" . $completedTask .
                " WHERE " . $this->_idField . " = :id ");
            $sth->bindValue(':tasktext', $params['tasktext'], PDO::PARAM_STR);
            $sth->bindValue(':id', $params['id'], PDO::PARAM_STR);
            $sth->execute();
            base::commit();

            return true;

        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return isset($_SESSION['page']) ? $_SESSION['page'] : 1;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return isset($_SESSION['sort_field']) ? $_SESSION['sort_field'] : $this->_defaultSortField;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return isset($_SESSION['sort_direction']) ? $_SESSION['sort_direction'] : 'asc';
    }

    /**
     * @return array|bool
     */
    public function getAllTask()
    {
        $start = (int)($this->getPage() - 1) * $this->_rowsOnPage;
        $sql = "SELECT * FROM " . $this->_tableName .
            " ORDER BY " . $this->getSortField(). " " . $this->getSortDirection() .
            " LIMIT " . $start . ", " . $this->_rowsOnPage;

        // echo $sql . "<br>";
        $data = base::selectAllAssoc($sql);

        if($data) {
            return $data;
        }

        return false;
    }

    public function count()
    {
        return base::selectField("SELECT COUNT(*) FROM " . $this->_tableName);
    }

    /**
     * @return string
     */
    public function getPagination()
    {
        $rowsCount = $this->count();

        if($rowsCount <= $this->_rowsOnPage) {
            return '';
        }

        $last = ceil($rowsCount / $this->_rowsOnPage);
        $this->_page = isset($_SESSION['page']) ? (int)$_SESSION['page'] : 1;

        $start = (($this->_page - $rowsCount) > 0) ? $this->_page - $rowsCount : 1;
        $end = (($this->_page + $rowsCount) < $last) ? $this->_page + $rowsCount : $last;

        $html = '<ul class="pagination">';

        $class = ($this->_page == 1) ? "disabled" : "";
        $html .= '<li class="page-item ' . $class . '">' .
            '<a class="page-link" href="?page=' . ($this->_page - 1) . '">Previous</a> ';

        if ($start > 1) {
            $html .= '<a href="?page=1">1</a></li>';
            $html .= '<span class="disabled">...</span>';
        }

        for ($i = $start; $i <= $end; $i++) {
            if($this->_page == $i)
                $html .= '<li class="page-item active"><span class="page-link">' . $i .
                    '</span><span class="sr-only">(current)</span></li>';
            else
                $html .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<span class="disabled">...</span>';
            $html .= ' <a href="?page=' . $last . '">' . $last . '</a> ';
        }

        $class = ($this->_page == $last) ? "disabled" : "";

        $html .= '<li class="page-item ' . $class . '">' .
            '<a class="page-link" href="?page=' . ($this->_page + 1) . '">Next</a> ';

        $html .= "</ul>";

        return $html;
    }

}