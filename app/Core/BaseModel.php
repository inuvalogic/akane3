<?php

namespace Akane\Core;

class BaseModel extends \Akane\Core\Base
{
    const TABLE_NAME = '';

	public function getData($sql, $data = false) {
        $q = $this->db->pdo->prepare($sql);
        if ($data!=false){
            $q->execute($data);
        } else {
            $q->execute();
        }
        return $q->fetchAll();
    }
    
    public static function getSingleData($sql, $data) {
        $q = $this->db->pdo->prepare($sql);
        $q->execute($data);
        return $q->fetch();
    }

    public static function getTableName()
    {
        $reflector = new \ReflectionClass(get_called_class());
        $table_name = $reflector->getConstant('TABLE_NAME');
        return $table_name;
    }

    public static function insert($data)
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

            $sql = "INSERT INTO `".self::getTableName()."` (";
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
            self::showError($e);
            return false;
        }
    }

    public static function changeData($sql, $data){
        try
        {
            $q = $this->db->pdo->prepare($sql);
            $q->execute($data);
            return true;
        } catch(\PDOException $e)
        {
            showError($e);
            return false;
        }
    }
}
