<?php 

class Sensor
{
    protected $db;
    private $table = 'sensor';

    public function __construct($db_name)
    {
        $this->db = new Database($db_name);
    }

    /**
     * Get all sensors by group
     * 
     * @param int $group_id Group ID
     * @param string $enabled All|0|1
     */
    public function getAllSensorsByGroup($group_id, $enabled = 'all')
    {
        $q = "
            SELECT s.*, COUNT(t.id) AS temp_number  
            FROM sensor AS s 
            LEFT JOIN temperature AS t 
                ON s.id = t.sensor_id 
            WHERE group_id = :group_id 
        ";
        
        if ($enabled != 'all' && ($enabled == 0 || $enabled == 1))
            $q .= " AND s.enabled = ".$enabled." ";

        $q .= "
            GROUP BY s.id
            ORDER BY name
        ";

        $this->db->query($q);
        $this->db->bind(':group_id', $group_id, \PDO::PARAM_INT);
        
        return $this->db->fetchAll();
    }

    /**
     * Get sensor by id
     * 
     * @param int $id
     */
    public function getById($id)
    {
        $this->db->query("
            SELECT 
                s.* 
            FROM ".$this->table." AS s 
            WHERE s.id = :id
        ");
        $this->db->bind(':id', $id, \PDO::PARAM_INT);
        
        return $this->db->single();
    }

    /**
     * Get sensor by device
     * 
     * @param string $device
     */
    public function getByDevice($device)
    {
        $this->db->query("
            SELECT 
                s.* 
            FROM ".$this->table." AS s 
            WHERE s.device = :device
        ");
        $this->db->bind(':device', $device, \PDO::PARAM_STR);
        
        return $this->db->single();
    }

    /**
     * Creates a sensor
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
     * Updates a sensor
     * 
     * @param int $id Sensor ID
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
     * Removes a sensor
     * 
     * @param int $id
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM ".$this->table." WHERE id = :id");
        $this->db->bind(':id', $id, \PDO::PARAM_INT);
        
        return $this->db->execute();
    }


    /**
     * Removes chart cache datas
     * 
     * @return bool
     */
    public function removeCache()
    {
        $Cache = new Cache;

        $Cache->setDir('datas/')->setFile('cache.datas.json')->delete();
        $Cache->setDir('datas/')->setFile('cache.zoom.json')->delete();

        return true;
    }
}