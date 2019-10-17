<?php

class base
{
    private static $db;
    public static $config;
    private static $connected;

    public static function init($configfile)
    {
        self::$config = parse_ini_file ($configfile , true);
    }
    /**
     * base::connect()
     * Установка атрибутов и подключение к базе данных
     */
    public static function connect()
    {
        try
        {
            $db = self::$config['db'];
            self::$db = new PDO($db['config'], $db['user'], $db['pass']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
            self::$db->query("SET NAMES 'utf8'");
            self::$db->query("SET character_set_client='utf8'");
            self::$db->query("SET character_set_database='utf8'");
            self::$connected = true;
        }
        catch( Exception $e )
        {
            self::$connected=false;
            self::_fatal($e);
        }
    }

    public static function _fatal($e)
    {
        header("Content-type: text/plain; charset=utf-8");
        echo $e->getMessage();
        print_r($e->getTraceAsString());
        exit;
    }

    public static function disconnect()
    {
        self::$db = null;
    }

    /**
     * base::quote()
     * Quotes a string for use in a query. Returns a quoted string that is theoretically
     * safe to pass into an SQL statement. Returns FALSE if the driver does not support
     * quoting in this way.
     * @param string $s
     * @return string | FALSE
     */
    public static function quote($s)
    {
        return self::$db->quote($s);
    }

    /**
     * base::prepare()
     * Prepares a statement for execution and returns a statement object.
     * If the database server successfully prepares the statement, PDO::prepare()
     * returns a PDOStatement object. If the database server cannot successfully
     * prepare the statement, PDO::prepare() returns FALSE or emits PDOException
     * (depending on error handling).
     * @param string $s
     * @return PDOStatement | FALSE | PDOException
     */
    public static function prepare($s)
    {
        return self::$db->prepare($s);
    }

    /**
     * base::begin()
     * Initiates a transaction
     */
    public static function begin()
    {
        self::$db->beginTransaction();
    }

    /**
     * base::commit()
     * Commits a transaction
     */
    public static function commit()
    {
        self::$db->commit();
    }

    /**
     * base::rollback()
     * Rolls back a transaction
     */
    public static function rollback()
    {
        self::$db->rollback();
    }

    /**
     * base::query()
     * Executes an SQL statement, returning a result set as a PDOStatement object,
     * or FALSE on failure.
     * @param string $sql SQL statement
     * @return PDOStatement | FALSE
     */
    public static function query($sql)	{return self::$db->query($sql);	}

    /**
     * base::selectField()
     * Возвращает первый столбец следующей строки из результирующей
     * таблицы запроса или FALSE, если строк больше нет.
     * @param string $sql Запрос
     * @return string | FALSE
     */
    public static function selectField($sql)
    {
        return self::$db->query($sql)->fetchColumn();
    }

    /**
     * base::selectAllUniq()
     * Возвращает ассоциативный массив, содержащий уникальные значения
     * результирующей выборки. Ключем является первое поле результирующей выбороки.
     * @param string $sql Запрос
     * @return array
     */
    public static function selectAllUniq($sql)
    {
        return self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
    }

    /**
     * base::selectRow()
     * Возвращает текущую запись из результирующей выборки в виде ассоциативного массива.
     * @param string $sql Запрос
     * @return array
     */
    public static function selectRow($sql)
    {
        return current(self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * base::selectAllAssoc()
     * Возвращает ассоциативный массив, содержащий все значения выборки
     * @param string $sql Запрос
     * @return array
     */
    public static function selectAllAssoc($sql)
    {
        return self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @static
     * @return mixed
     */
    public static function getLastInsertId()
    {
        return self::$db->lastInsertId();
    }
}