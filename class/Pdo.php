<?php
defined('_CONSTANT_') or die('Error. You don`t have permision to access.');
if (!class_exists('PDO'))
    die('Fatal Error: Для работы нужна поддержка PDO.');
	/* клон класса PDO */
class PDO_ extends PDO {
    function __construct($dsn, $username, $password) {
        parent :: __construct($dsn, $username, $password);
        $this -> setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
        $this -> setAttribute(PDO :: ATTR_DEFAULT_FETCH_MODE, PDO :: FETCH_ASSOC);
    } 

    function prepare($sql, $options= NULL) {
        $stmt = parent :: prepare($sql, array(
                PDO :: ATTR_STATEMENT_CLASS => array('PDOStatement_')
                ));
        return $stmt;
    } 
/* аналог запроса mysql_query */
    function query($sql, $params = array()) {
        $stmt = $this -> prepare($sql);
        $stmt -> execute($params);
        return $stmt;
    } 
/*  аналог mysql_result */
    function querySingle($sql, $params = array()) {
        $stmt = $this -> query($sql, $params);
        $stmt -> execute($params);
        return $stmt -> fetchColumn(0);
    } 
/* аналог mysql_fetch_assoc(mysql_query */
    function queryFetch($sql, $params = array()) {
        $stmt = $this -> query($sql, $params);
        $stmt -> execute($params);
        return $stmt -> fetch();
    } 
} 
// ----------------------------------------------------//
class PDOStatement_ extends PDOStatement {
    function execute($params = array()) {
        if (func_num_args() == 1) {
            $params = func_get_arg(0);
        } else {
            $params = func_get_args();
        } 
        if (!is_array($params)) {
            $params = array($params);
        } 
        parent :: execute($params);
        return $this;
    } 

    function fetchSingle() {
        return $this -> fetchColumn(0);
    } 

    function fetchAssoc() {
        $this -> setFetchMode(PDO :: FETCH_NUM);
        $data = array();
        while ($row = $this -> fetch()) {
            $data[$row[0]] = $row[1];
        } 
        return $data;
    } 
} 

class DB {
    static $dbs;
    public function __construct() {
        try {
            self :: $dbs = new PDO_('mysql:host=' . DBHOST . ';port=' . DBPORT . ';dbname=' . DBNAME, DBUSER, DBPASS);
            self :: $dbs -> exec('SET CHARACTER SET utf8');
            self :: $dbs -> exec('SET NAMES utf8');
        }
        catch (PDOException $e) {
            die('Connection failed: ' . $e -> getMessage());
        } 		
    } 
} 

$db = new DB();

?>