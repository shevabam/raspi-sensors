<?php 

class Param
{
    protected $db;
    private $table = 'param';

    public function __construct($db_name)
    {
        $this->db = new Database($db_name);
    }

    /**
     * Get by key
     * 
     * @param string $key Parameter key
     * @return string Returns parameter value
     */
    public function get($key)
    {
        $q = "
        SELECT * 
        FROM ".$this->table." 
        WHERE key = :key
        ";
        
        $this->db->query($q);
        $this->db->bind(':key', $key, \PDO::PARAM_STR);

        $row = $this->db->single();

        return $row['value'];
    }

    /**
     * Get all key-values entries
     * 
     * @return array
     */
    public function getAll()
    {
        $q = "SELECT * FROM ".$this->table." ";
        
        $this->db->query($q);
        
        return $this->db->fetchAll();
    }

    /**
     * Creates an parameter
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
     * Updates an parameter
     * 
     * @param string $key Parameter key
     * @param string $value Parameter value
     */
    public function update($key, $value)
    {
        $this->db->query("
            UPDATE ".$this->table." 
            SET value = :value 
            WHERE key = :key 
        ");

        $this->db->bind(':key', $key, \PDO::PARAM_STR);
        $this->db->bind(':value', $value, \PDO::PARAM_STR);

        return $this->db->execute();
    }
}