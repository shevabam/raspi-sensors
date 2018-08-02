<?php

class Cache
{
    protected $directory;
    protected $file;
    
    
    /**
     * 
     */
    public function __construct()
    {

    }

    /**
     * Sets the directory where are cached files
     * 
     * @param string $dir Directory path
     */
    public function setDirectory($dir)
    {
        $this->directory = $dir;

        if (!is_dir($this->directory))
            mkdir($this->directory, 0777, true);

        return $this;
    }

    /**
     * Alias of setDirectory()
     */
    public function setDir($dir)
    {
        return $this->setDirectory($dir);
    }
    
    
    /**
     * Sets the cached filename
     * 
     * @param string $file Cached file
     */
    public function set($file)
    {
        if (trim(!empty($file)))
            $this->file = $file;

        return $this;
    }

    /**
     * Alias of set()
     */
    public function setFile($file)
    {
        return $this->set($file);
    }
    
    
    /**
     * Returns file statistics
     * 
     * @return array
     */
    public function getFileStats()
    {
        return stat($this->directory.$this->file);
    }
    
    
    /**
     * Checks if cached file exists
     * 
     * @param bool $expire Expire time cache
     * @return bool
     */
    public function check($expire = false)
    {
        $file = $this->directory.$this->file;
        
        if (file_exists($file) && is_file($file) && filesize($file) > 0)
        {
            if ($expire == false)
            {
                return true;
            }
            else
            {
                if (filemtime($file) + $expire < time())
                {
                    return false; // Create
                }
                else
                {
                    return true; // Read
                }
            }
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Read file
     * 
     * @return string
     */
    public function read()
    {
        return readfile($this->directory.$this->file);
    }
    
    
    /**
     * Get file content
     * 
     * @return string
     */
    public function get()
    {
        return file_get_contents($this->directory.$this->file);
    }
    
    
    /**
     * Starts buffer
     * 
     * @return string
     */
    public function start()
    {
        return ob_start();
    }
    
    
    /**
     * Ends buffer and print cached content
     * 
     * @param bool $print If true, echo's the content ; else, just put the content in the file
     * @return string
     */
    public function end($print = true)
    {
        $content = ob_get_contents();
        ob_end_clean();
        
        file_put_contents($this->directory.$this->file, $content);
        
        if ($print)
            echo $content;
    }
    
    
    /**
     * Removes one cached file
     * 
     * @param string $file Filename to delete
     * @return bool
     */
    public function delete($file = null)
    {
        if (is_null($file))
            $file = $this->file;

        if (file_exists($this->directory.$file) && is_file($this->directory.$file))
        {
            unlink($this->directory.$file);
        }
    }
    
    
    /**
     * Removes serverals files
     * 
     * @param array $array Contains filenames to delete
     * @return bool
     */
    public function multiDelete($array)
    {
        if (!is_array($array) || count($array) == 0)
        {
            return false;
        }
        else
        {
            foreach ($array as $file)
                $this->delete($file);
        }
    }
    
    
    /**
     * Removes all cached files
     * 
     * @return bool
     */
    public function deleteAll()
    {
        $scan = scandir($this->directory);
        
        if (count($scan) > 0)
        {
            foreach ($scan as $file)
                $this->delete($file);
        }
    }
}
