<?php

class SensorGroup 
{
    protected $db;
    private $table = 'sensor_group';

    public function __construct($db_name)
    {
        $this->db = new Database($db_name);
    }

    /**
     * Get all groups
     */
    public function getAllGroups()
    {
        $this->db->query("
            SELECT 
                g.*, COUNT(s.id) AS sensors_number 
            FROM sensor_group AS g 
            LEFT JOIN sensor AS s 
                ON g.id = s.group_id 
            GROUP BY g.id
            ORDER BY g.name
        ");
        
        return $this->db->fetchAll();
    }

    /**
     * Get group by id
     * 
     * @param int $id
     */
    public function getById($id)
    {
        $this->db->query("
            SELECT 
                g.*, COUNT(s.id) AS sensors_number 
            FROM sensor_group AS g 
            LEFT JOIN sensor AS s 
                ON g.id = s.group_id
            WHERE g.id = :id
        ");
        $this->db->bind(':id', $id, \PDO::PARAM_INT);
        
        return $this->db->single();
    }

    /**
     * Creates a group
     * 
     * @param array $datas Array of fields and values
     */
    public function insert(array $datas)
    {
        $fields_1 = '';
        $fields_2 = '';
        foreach ($datas as $field => $value)
        {
            $fields_1 .= $field.', ';
            $fields_2 .= ':'.$field.', ';
        }
        $fields_1 = rtrim($fields_1, ', ');
        $fields_2 = rtrim($fields_2, ', ');

        $this->db->query("INSERT INTO ".$this->table."(".$fields_1.") VALUES(".$fields_2.")");

        foreach ($datas as $field => $value)
        {
            $this->db->bind(':'.$field, $value);
        }

        return $this->db->execute();
    }

    /**
     * Updates a group
     * 
     * @param int $id Group ID
     * @param array $datas Array of fields and values
     */
    public function update($id, array $datas)
    {
        $fields_set = '';
        foreach ($datas as $field => $value)
        {
            $fields_set .= $field.' = :'.$field.', ';
        }
        $fields_set = rtrim($fields_set, ', ');

        $this->db->query("
            UPDATE ".$this->table." 
            SET ".$fields_set." 
            WHERE id = :id 
        ");

        $this->db->bind(':id', $id, \PDO::PARAM_INT);

        foreach ($datas as $field => $value)
        {
            $this->db->bind(':'.$field, $value);
        }

        return $this->db->execute();
    }

    /**
     * Removes a group
     * 
     * @param int $id
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM ".$this->table." WHERE id = :id");
        $this->db->bind(':id', $id, \PDO::PARAM_INT);
        
        return $this->db->execute();
    }
}