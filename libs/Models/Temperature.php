<?php 

class Temperature
{
    protected $db;
    private $table = 'temperature';

    public function __construct($db_name)
    {
        $this->db = new Database($db_name);
    }

    /**
     * Get all temperatures by sensor ID
     * 
     * @param int $sensor_id
     */
    public function getAllBySensorId($sensor_id)
    {
        $this->db->query("SELECT * FROM temperature WHERE sensor_id = :sensor_id ORDER BY date, created_at");
        $this->db->bind(':sensor_id', $sensor_id, \PDO::PARAM_INT);
        
        return $this->db->fetchAll();
    }

    /**
     * Get all temperatures by sensor device
     * 
     * @param string $sensor_device
     */
    public function getAllBySensorDevice($sensor_device)
    {
        $this->db->query("
            SELECT T.* 
            FROM temperature AS T 
            INNER JOIN sensor AS S 
                ON T.sensor_id = S.id 
            WHERE S.device = :sensor_device 
            ORDER BY date, created_at
        ");
        $this->db->bind(':sensor_device', $sensor_device, \PDO::PARAM_STR);
        
        return $this->db->fetchAll();
    }

    /**
     * Get last temperature for sensor device
     * 
     * @param string $sensor_device
     */
    public function getLastBySensorDevice($sensor_device)
    {
        $this->db->query("
            SELECT T.* 
            FROM temperature AS T 
            INNER JOIN sensor AS S 
                ON T.sensor_id = S.id 
            WHERE S.device = :sensor_device 
            ORDER BY date DESC, created_at DESC 
            LIMIT 1
        ");
        $this->db->bind(':sensor_device', $sensor_device, \PDO::PARAM_STR);
        
        return $this->db->single();
    }

    /**
     * Get all temperatures dates (yyyymmddhhii)
     * 
     * @param int $group_id
     * @param array $options Array of query options : order_type|limit
     */
    public function getAllDatesByGroupId($group_id, $options = array())
    {
        $q = "
            SELECT 
                strftime('%Y', t.date) || strftime('%m', t.date) || strftime('%d', t.date) || strftime('%H', t.date) || substr(strftime('%M', t.date), 1, 1) || '0' AS date_group  
            FROM temperature AS t 
            INNER JOIN sensor AS s 
                ON t.sensor_id = s.id
            INNER JOIN sensor_group AS g
                ON s.group_id = g.id
            WHERE g.id = :group_id
            GROUP BY date_group
            ORDER BY t.date
        ";

        if (isset($options['order_type']))
            $q .= " ".$options['order_type']." ";

        if (isset($options['limit']) && $options['limit'] > 0)
            $q .= " LIMIT ".$options['limit'];

        $this->db->query($q);
        $this->db->bind(':group_id', $group_id, \PDO::PARAM_INT);
        
        return $this->db->fetchAll();
    }

    /**
     * Get all temperatures by date grouped
     * 
     * @param string $date (yyyymmddhhii)
     */
    public function getAllByDate($date)
    {
        $this->db->query("
            SELECT 
                t.*
            FROM temperature AS t 
            WHERE strftime('%Y', t.date) || strftime('%m', t.date) || strftime('%d', t.date) || strftime('%H', t.date) || substr(strftime('%M', t.date), 1, 1) || '0' = :date
        ");
        $this->db->bind(':date', $date, \PDO::PARAM_STR);
        
        return $this->db->fetchAll();
    }

    /**
     * Remove all temperatures from sensor_id
     * 
     * @param int $sensor_id
     */
    public function removeTempBySensorId($sensor_id)
    {
        $this->db->query("
            DELETE FROM ".$this->table." 
            WHERE sensor_id = :sensor_id 
        ");
        $this->db->bind(':sensor_id', $sensor_id, \PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    /**
     * Creates a temperature
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
     * Removes a temperature
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