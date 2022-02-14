<?php
namespace Lukeme\Utils;
use \PDO;

class Db
{
    private $dbh;

    function __construct(array $conf)
    {
	if('sqlsrv'==$conf['driver']){
            $dsn = sprintf('sqlsrv:Server=%s;Database=%s;', $conf['host'], $conf['name']);
        }else{
            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;', $conf['driver'], $conf['host'], $conf['port'], $conf['name']);
        }
        $this->dbh = new PDO($dsn, $conf['user'], $conf['pass']);
        if (isset($conf['schema'])) {
            $this->dbh->exec("SET search_path TO {$conf['schema']}");
        }
        $this->dbh->query("set names {$conf['char']}");
    }

    function fetchAll($sql, $mode = PDO::FETCH_ASSOC)
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        return $sth->fetchAll($mode);
    }

    function fetchOne($sql = null, $mode = PDO::FETCH_ASSOC)
    {
        return $this->fetchAll($sql, $mode)[0] ?? [];
    }

    function exec($sql)
    {
        return $this->dbh->exec($sql);
    }

    function getSql($tbl, $data, $new = [], $type ='mysql')
    {
        $data = array_merge($data, $new);
        foreach ($data as $k => $v) {
            if (is_null($v)) {
                unset($data[$k]);
                continue;
            }
            $data[$k] = trim($v);
        }
        $delimiter = '`';
        if($type == 'pgsql'){
            $delimiter = '"';
        }

        $fields = $delimiter . implode($delimiter.','.$delimiter, array_keys($data)) . $delimiter;
//        $fields = '"' . implode('","', array_keys($data)) . '"';
        $values = "'" . implode("','", array_values($data)) . "'";
        return sprintf("insert into %s (%s) values (%s);", $tbl, $fields, $values);
    }

    function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }


    function getTables()
    {
        $sql = "SHOW TABLES";
        return $this->fetchAll($sql, PDO::FETCH_NUM);
    }

    function getFields($table)
    {
        $sql = "DESCRIBE $table";
        return $this->fetchAll($sql, PDO::FETCH_COLUMN);
    }

    function errorInfo()
    {
        return $this->dbh->errorInfo();
    }

    //beginTransaction,commit,rollBack
    function __call($method, $param)
    {
        return $this->dbh->$method($param);
    }

}
