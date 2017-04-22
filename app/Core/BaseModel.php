<?php

namespace Akane\Core;

class BaseModel extends \Akane\Core\Base
{
    const TABLE_NAME = '';

	public function all() {
        $sql = "SELECT * FROM `" . $this->getTableName() . "`";
        $q = $this->db->pdo->prepare($sql);
        if ($data!=false){
            $q->execute($data);
        } else {
            $q->execute();
        }
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
    
    public function getSingleData($sql, $data) {
        $q = $this->db->pdo->prepare($sql);
        $q->execute($data);
        return $q->fetch();
    }

    public function getTableName()
    {
        $reflector = new \ReflectionClass(get_called_class());
        $table_name = $reflector->getConstant('TABLE_NAME');
        return $table_name;
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
            $columns = array_keys($setdata);
            $columnswhere = array_keys($where);

            array_walk($columns, function(&$value, &$key) {
                $value = '`'.$value.'` = ?';
            });

            array_walk($columnswhere, function(&$valuew, &$keyw) {
                $valuew = '`'.$valuew.'` = ?';
            });

            $sql = "UPDATE `" . $this->getTableName() . "` SET ";
            $sql .= implode(',', $columns);
            $sql .= " WHERE ";
            $sql .= implode(',', $columnswhere);
            $sql .= ";";
            
            $values = array();

            foreach ($setdata as $key => $value) {
                if (strtolower($value)=='now()'){
                    $values[] = 'NOW()';
                } else {
                    $values[] = $value;
                }
            }

            $wherevalues = array();

            foreach ($where as $key2 => $value2) {
                if (strtolower($value2)=='now()'){
                    $wherevalues[] = 'NOW()';
                } else {
                    $wherevalues[] = $value2;
                }
            }
            
            $params = array_merge($values, $wherevalues);

            $q = $this->db->pdo->prepare($sql);

            for($a=0; $a < count($params); $a++){
                $q->bindParam($a+1, $params[$a]);
            }

            $q->execute();

            return true;
        } catch(\PDOException $e)
        {
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
            return false;
        }
    }
}
