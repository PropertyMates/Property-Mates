<?php


class CoOwner_Model{

    public static function make_query_from_array($table_name,$where,$columns = array(),$join = null){
        $column = implode(',',$columns);
        $select_columns = $column == "" ? 'count(*) as count' : $column;
        $sql = "SELECT {$select_columns} FROM {$table_name} $join";
        $count = 0;
        foreach ($where as $key => $value){
            $sql.= ($count == 0) ? 'WHERE' : ' AND'; $count++;
            if($key == 'created_at'){
                $sql.= " {$key} {$value[0]} '{$value[1]}'";
            } else {
                if(is_null($value) && $value !== 0){
                    $sql.= " {$key}".' is NULL';
                } elseif (is_array($value)){
                    $values = implode(',',$value);
                    $sql.= " {$key} IN ($values)";
                } else {
                    $sql.= " {$key}".' = '.( is_string($value) && !is_numeric($value) ? "'".$value."' " : $value);
                }
            }
        }
        return $sql;
    }

    public static function update_row($table,$data,$where){
        $columns = implode(',',array_keys($data));
        $values = implode(',',array_values($data));

        $columns_data = "";
        $count = 0;
        foreach ($data as $key => $value){

            if(is_null($value) && $value !== 0){
                $columns_data.= ($count == 0 ? "" : "," )." ".$key.' = NULL';
            } else {
                $columns_data.= ($count == 0 ? "" : "," )." ".$key.' = '.( is_string($value) && !is_numeric($value) ? "'".$value."' " : $value);
            }
            $count++;
        }

        $where_quey = null;
        $count = 0;
        foreach ($where as $key => $value){
            $where_quey.= ($count == 0) ? 'WHERE' : ' AND'; $count++;
            if(is_null($value) && $value !== 0){
                $where_quey.= " ".$key.' is NULL';
            } else {
                $where_quey.= " ".$key.' = '.( is_string($value) && !is_numeric($value) ? "'".$value."' " : $value);
            }
        }

        $query = "UPDATE $table SET $columns_data $where_quey";
        global $wpdb;
        return $wpdb->query($query);
    }

    public static function delete_row($table,$where){
        global $wpdb;
        $table = strpos($table,$wpdb->prefix) === false ? $wpdb->prefix.$table : $table;
        $sql = "DELETE FROM {$table}";
        $count = 0;
        foreach ($where as $key => $value){
            $sql.= ($count == 0) ? ' WHERE' : ' AND'; $count++;
            if(is_null($value) && $value !== 0){
                $sql.= " {$key}".' is NULL';
            } elseif (is_array($value)){
                $values = implode(',',$value);
                $sql.= " {$key} IN ($values)";
            } else {
                $sql.= " {$key}".' = '.( is_string($value) && !is_numeric($value) ? "'".$value."' " : $value);
            }
        }
        $wpdb->query($sql);
        return $sql;
    }

    public static function get($table,$array = array(),$single = false)
    {
        global $wpdb;
        $table = strpos($table,$wpdb->prefix) === false ? $wpdb->prefix.$table : $table;
        $sql = self::make_query_from_array($table,$array,array('*'));
            return $single ? $wpdb->get_row($sql) : $wpdb->get_results($sql);
    }

    public static function insert_in_table($table,$data)
    {
        if(!isset($data['created_at'])){
            $data['created_at'] = wp_date('Y-m-d H:i:s');
        }
        global $wpdb;
        $table = strpos($table,$wpdb->prefix) === false ? $wpdb->prefix.$table : $table;
        $wpdb->insert($table,$data);
        return $wpdb->insert_id;
    }


}

class  CoOwner_ArrayResponse{
    public $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    public function get()
    {
        return $this->array;
    }

    public function pluck($key,$key2 = null)
    {
        $new_array = array();
        foreach ($this->array as $value){
            $value = (object) $value;
            if($key2 == null){
                $new_array[] =  isset($value->$key) ? $value->$key : null;
            } elseif(isset($value->$key2)){
                $new_array[$value->$key2] = isset($value->$key) ? $value->$key : null;
            }
        }
        return $new_array;
    }
}
