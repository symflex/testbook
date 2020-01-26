<?php

namespace Component;

use PDO;

/**
 * Class Database
 * @package Component
 */
class Database extends PDO
{
    /**
     * Database constructor.
     * @param string $user
     * @param string $pass
     * @param string $db
     * @param string $host
     * @param string $type
     */
    public function __construct(
        string $user,
        string $pass,
        string $db,
        string $host = 'localhost',
        string $type = 'mysql'
    ) {
        parent::__construct(
            sprintf('%s:host=%s;dbname=%s;charset=utf8', $type, $host, $db),
            $user,
            $pass
        );

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param $sql
     */
    public function raw($sql)
    {
        $this->query($sql);
    }

    /**
     * @param $sql
     * @param array $array
     * @param int $fetchMode
     * @param string $class
     * @param null $single
     * @return array|mixed
     */
    public function select($sql, $array = [], $fetchMode = PDO::FETCH_OBJ, $class = '', $single = null)
    {
        if (strpos(strtolower($sql), 'select') === false) {
            $sql = 'SELECT ' . $sql;
        }

        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();

        if ($single === null) {
            return $fetchMode === PDO::FETCH_CLASS ? $stmt->fetchAll($fetchMode, $class) : $stmt->fetchAll($fetchMode);
        } else {
            return $fetchMode === PDO::FETCH_CLASS ? $stmt->fetchObject($class) : $stmt->fetch($fetchMode);
        }
    }

    /**
     * @param $sql
     * @param array $array
     * @param int $fetchMode
     * @param string $class
     * @return array|mixed
     */
    public function find($sql, $array = [], $fetchMode = PDO::FETCH_OBJ, $class = '')
    {
        return $this->select($sql, $array, $fetchMode, $class, true);
    }

    /**
     * @param $table
     * @param string $column
     * @return int
     */
    public function count($table, $column= 'id')
    {
        $stmt = $this->prepare(sprintf('SELECT %s FROM %s', $column, $table));
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $table
     * @param $data
     * @return string
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $stmt = $this->prepare(sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $fieldNames, $fieldValues));

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->lastInsertId();
    }

    /**
     * @param $table
     * @param $data
     * @param $where
     * @return int
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :d_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i === 0) {
                $whereDetails .= $key . '= :w_' . $key;
            } else {
                $whereDetails .= ' AND ' . $key . '= :w_' . $key;
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare(sprintf('UPDATE %s SET %s WHERE %s', $table, $fieldDetails, $whereDetails));

        foreach ($data as $key => $value) {
            $stmt->bindValue(':d_' . $key, $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(':w_' . $key, $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $table
     * @param $where
     * @param int $limit
     * @return int
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i === 0) {
                $whereDetails .= $key . ' = :' . $key;
            } else {
                $whereDetails .= ' AND ' . $key . ' = :' . $key;
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $useLimit = '';
        if (is_numeric($limit)) {
            $useLimit = 'LIMIT ' .  $limit;
        }

        $stmt = $this->prepare(sprintf('DELETE FROM %s WHERE %s %s', $table, $whereDetails, $useLimit));

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteByIds(string $table, string $column, string $ids)
    {
        $stmt = $this->prepare(sprintf('DELETE FROM %s WHERE %s IN (%s)', $table, $column, $ids));
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $table
     * @return false|int
     */
    public function truncate($table)
    {
        return $this->exec('TRUNCATE TABLE ' . $table);
    }
}
