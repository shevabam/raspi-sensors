<?php

class Database
{
    private $dbh;
    private $error;
    private $stmt;
    private $dbname;
 
    public function __construct($dbname)
    {
        $this->dbname = $dbname;
        $dsn = 'sqlite:'.$this->dbname;

        $options = array(
            \PDO::ATTR_PERSISTENT         => true,
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instance
        try {
            $this->dbh = new \PDO($dsn, null, null, $options);
        }
        // Catch any errors
        catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function prepare($statement)
    {
        return $this->query($statement);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type))
        {
            switch (true)
            {
                case is_int($value):  $type = \PDO::PARAM_INT;  break;
                case is_bool($value): $type = \PDO::PARAM_BOOL; break;
                case is_null($value): $type = \PDO::PARAM_NULL; break;
                default: $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function exec($q)
    {
        return $this->dbh->exec($q);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function fetchAll($type = \PDO::FETCH_ASSOC)
    {
        $this->execute();
        return $this->stmt->fetchAll($type);
    }

    public function single($type = \PDO::FETCH_ASSOC)
    {
        $this->execute();
        return $this->stmt->fetch($type);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}

