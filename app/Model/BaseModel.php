<?php

namespace Akane\Model;

class BaseModel extends \Akane\Core\Base
{
    const TABLE_NAME = '';
    const SEARCH_COLUMNS = array('id');

    public function get($id, $primary_key_column='id', $single=true)
    {
        $sql = "SELECT * FROM `" . $this->getTableName() . "` WHERE `".$primary_key_column."` = ?";
        $q = $this->db->pdo->prepare($sql);
        $q->execute(array($id));
        if ($single==true){
            return $q->fetch();
        } else {
            return $q->fetchAll();
        }
    }

    public function all($order = '', $limit='', $where = false)
    {
        if ($order!=''){
            $order = ' ORDER BY '.$order;
        }

        if ($limit!=''){
            $limit = ' LIMIT '.$limit;
        }

        $sql = "SELECT * FROM `" . $this->getTableName() . "`";
        
        if ($where!=false && is_array($where))
        {
            $columnswhere = array_keys($where);
            array_walk($columnswhere, function(&$valuew, &$keyw) {
                $valuew = '`'.$valuew.'` = ?';
            });

            $sql .= " WHERE ";
            $sql .= implode(' AND ', $columnswhere);

            $wherevalues = array();

            foreach ($where as $key2 => $value2) {
                if (strtolower($value2)=='now()'){
                    $wherevalues[] = 'NOW()';
                } else {
                    $wherevalues[] = $value2;
                }
            }
            
            $params = $wherevalues;
        }
        
        $sql .= $order.$limit;

        $q = $this->db->pdo->prepare($sql);

        if ($where!=false && is_array($where))
        {
            for($a=0; $a < count($params); $a++){
                $q->bindParam($a+1, $params[$a]);
            }
        }

        $q->execute();

        return $q->fetchAll();
    }

    public function getData($sql, $data = false) {
        $q = $this->db->pdo->prepare($sql);
        if ($data!=false){
            $q->execute($data);
        } else {
            $q->execute();
        }
        return $q->fetchAll();
    }
    
    public function getSingleData($sql, $data = false) {
        $q = $this->db->pdo->prepare($sql);
        if ($data!=false){
            $q->execute($data);
        } else {
            $q->execute();
        }
        return $q->fetch();
    }

    public function getTableName()
    {
        $reflector = new \ReflectionClass(get_called_class());
        $table_name = $reflector->getConstant('TABLE_NAME');
        return $table_name;
    }

    public function getSearchableColumns()
    {
        $reflector = new \ReflectionClass(get_called_class());
        $column_name = $reflector->getConstant('SEARCH_COLUMNS');
        return $column_name;
    }

    public function insert($data)
    {
        if (!is_array($data)){
            throw new \Exception("wrong data given for insert action");
            exit;
        }

        try
        {
            $mark = array();
            $values = array();
            foreach ($data as $key => $value) {
                if (strtolower($value)=='now()'){
                    $values[] = 'NOW()';
                } else {
                    $values[] = '?';
                    $mark[] = $value;
                }
            }
            
            $columns = array_keys($data);

            array_walk($columns, function(&$value, &$key) {
                $value = '`'.$value.'`';
            });

            $sql = "INSERT INTO `" . $this->getTableName() . "` (";
            $sql .= implode(',', $columns);
            $sql .= ") VALUES (";
            $sql .= implode(',', $values);
            $sql .= ");";
            
            $q = $this->db->pdo->prepare($sql);

            for($a=0; $a < count($mark); $a++){
                $q->bindParam($a+1, $mark[$a]);
            }

            $q->execute();

            return true;
        } catch(\PDOException $e)
        {
            // echo $e->getMessage();
            return false;
        }
    }

    public function update($setdata, $where)
    {
        if (!is_array($setdata)){
            throw new \Exception("wrong data given for update action");
            exit;
        }

        if (!is_array($where)){
            throw new \Exception("wrong where condition given for update action");
            exit;
        }

        try
        {            
            $columns = array();
            $columnswhere = array();
            $values = array();
            $wheres = array();

            foreach ($setdata as $key => $value) {
                if (strtolower($value)=='now()'){
                    $columns[] = '`'.$key.'` = NOW()';
                } else {
                    $columns[] = '`'.$key.'` = ?';
                    $values[] = $value;
                }
            }

            foreach ($where as $keyw => $valuew) {
                if (strtolower($valuew)=='now()'){
                    $columnswhere[] = '`'.$keyw.'` = NOW()';
                } else {
                    $columnswhere[] = '`'.$keyw.'` = ?';
                    $wheres[] = $valuew;
                }
            }

            $sql = "UPDATE `" . $this->getTableName() . "` SET ";
            $sql .= implode(',', $columns);
            $sql .= " WHERE ";
            $sql .= implode(',', $columnswhere);
            $sql .= ";";

            $params = array_merge($values, $wheres);

            $q = $this->db->pdo->prepare($sql);

            for($a=0; $a < count($params); $a++){
                $q->bindParam($a+1, $params[$a]);
            }

            $q->execute();

            return true;
        } catch(\PDOException $e)
        {
            // echo $e->getMessage();
            return false;
        }
    }

    public function delete($id, $primary_key_column = 'id')
    {
        try
        {
            $sql = "DELETE FROM `" . $this->getTableName() . "` WHERE `" . $primary_key_column . "` = ?";
            $q = $this->db->pdo->prepare($sql);
            $q->execute(array($id));
            return true;
        } catch(\PDOException $e)
        {
            // echo $e->getMessage();
            return false;
        }
    }

    public function find($keyword, $order='', $limit='')
    {
        if (empty($keyword)){
            throw new \Exception("keyword cannot be null, must be given for find action");
            exit;
        }
        
        $table_columns = $this->getSearchableColumns();

        if (!is_array($table_columns)){
            throw new \Exception("wrong table_columns given for find action, must be an Array");
            exit;
        }

        if ($order!=''){
            $order = ' ORDER BY '.$order;
        }

        if ($limit!=''){
            $limit = ' LIMIT '.$limit;
        }

        $find_query = array();
        foreach ($table_columns as $col) {
            $find_query[] = "
            `".$col."` LIKE CONCAT(:key, '%') OR
            `".$col."` LIKE CONCAT('%', :key, '%') OR
            `".$col."` LIKE CONCAT('%', :key)";
        }

        $find_queries = implode(' OR ', $find_query);
        $sql = "SELECT * FROM `" . $this->getTableName() . "` WHERE (".$find_queries.")".$order.$limit;
        return $this->findData($sql, array(':key' => $keyword));
    }

    public function findData($sql, $bindparams)
    {
        if (!is_array($bindparams)){
            throw new \Exception("wrong bindparams type given for find action, must be an Array");
            exit;
        }
        
        $q = $this->db->pdo->prepare($sql);
        $q->execute($bindparams);
        
        return $q->fetchAll();
    }

    public function getLastInsertId()
    {
        return $this->db->pdo->lastInsertId();
    }
}
