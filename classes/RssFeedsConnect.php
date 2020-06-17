<?php

/**
 * This class establishes the database connection and adds methods to allow 
 * updates, insertions and deletes, etc...
 * 
 * @author Sukh
 */
class RssFeedsConnect
{    

    /**
     * $dbh
     * 
     * This value holds the database object
     *
     * @var string
     */
    private $dbh;

    /**
     * Class constructor.
     * 
     * Accepts the PDO Object parameter
     *
     * @param object $dbh This is the PDO Object
     * @throws PDOException
     */
    public function __construct(PDO $dbh)
    {
        if(empty($dbh)) {
            echo 'No database connection set.';
            return false;
        } else {
            try {
                $this->dbh = $dbh;
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            } 
        }
    }

    /**
     * setDB
     * 
     * This allows DB connection to be changed to a different database
     *
     * @param object $dbh This is the PDO Object
     * @throws PDOException
     */
    public function setDB(PDO $dbh) 
    {
        if(empty($dbh)) {
            echo 'No database connection set.';
            return false;
        } else {
            try {
                $this->dbh = $dbh;
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            } 
        }
    }

    /**
     * setDBOptions
     * 
     * This allows DB attributes to be set
     *
     * @param int $attr PDO Attribute to set
     * @param mixed $val PDO Value to set with the attribute
     * 
     * @return false if attributes not set
     * 
     * @throws PDOException
     */
    public function setDBOptions($attr, $val) 
    {
        if(empty($attr) || empty($val)) {
            echo 'Either the attribute or value is missing for the PDO options.';
            return false;
        } else {
            try {
                $this->dbh->setAttribute($attr, $val);
            } catch (PDOException $e) { 
                echo 'Could not set attributes, check the connection.';
            }
        }
    }

    /**
     * getID
     * 
     * This gets the ID of the passed value if it exists else returns false
     *
     * @param string $value URL value
     * 
     * @return $query->fetchColumn() returns the 1st column to the matched value
     * @return false returns false if value dont exist or empty
     */
    public function getID($value)
    {
        if(!empty($value)) 
        {
            $data = [
                'link' => $value,
            ];
    
            $query = $this->dbh->prepare('SELECT id FROM links WHERE link = :link');
            $query->execute($data);
    
            if ($query->rowCount() > 0) {
                return $query->fetchColumn();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * getLink
     * 
     * This gets the url itself
     *
     * @param string $value URL value
     * 
     * @return $query->fetchColumn() returns the 1st column to the matched value
     * @return false returns false if value dont exist or empty
     */
    public function getLink($value)
    {
        if(!empty($value)) 
        {
            $data = [
                'link' => $value,
            ];
    
            $query = $this->dbh->prepare('SELECT id FROM links WHERE link = :link');
            $query->execute($data);
    
            if ($query->rowCount() > 0) {
                return $query->fetchColumn();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * showAll
     * 
     * This shows all the links from the database
     * 
     * @return $arr returns array of all the results
     * @return false returns false if nothing exists
     */
    public function showAll() {
        $arr = array();

        $query = $this->dbh->prepare('SELECT link FROM links');
        $query->execute();

        if ($query->rowCount() > 0) {
            foreach($query->fetchAll() as $row) {
                array_push($arr, array('link' => $row[0]));
            }
            return $arr;
        } else {
            return false;
        }
    }

    /**
     * insert
     * 
     * This inserts value to the database
     * 
     * @param string $value URL value to be inserted
     * 
     * @return true if value inserted
     * @return false returns false if value exists or empty
     */
    public function insert($value)
    {
        if(!empty($value)) 
        {
            $data = [
                'link' => $value,
            ];
    
            if($this->getID($value) && $this->getLink($value)) {
                return false;
            } else {
                $query = $this->dbh->prepare('INSERT INTO links (link) VALUES (:link)');
                $query->execute($data);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * update
     * 
     * This updates value to the database
     * 
     * @param string $value URL value to be updated
     * 
     * @return true if value updated
     * @return false returns false if value cannot be updated or empty
     */
    public function update($value, $valueToChange)
    {
        if(!empty($value) && !empty($valueToChange)) 
        {
            $data = [
                'id'   => $this->getID($value),
                'link' => $valueToChange,
            ];
    
            if($this->getID($value) && $this->getLink($value)) {
                $query = $this->dbh->prepare('UPDATE links SET link = :link WHERE id = :id');
                $query->execute($data);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * delete
     * 
     * This deletes value in the database
     * 
     * @param string $value URL value to be updated
     * 
     * @return true if value deleted
     * @return false returns false if value cannot be deleted or empty
     */
    public function delete($value) 
    {
        if(!empty($value)) 
        {
            $data = [
                'id'   => $this->getID($value)
            ];
    
            if($this->getID($value) && $this->getLink($value)) {
                $query = $this->dbh->prepare('DELETE FROM links WHERE id = :id');
                $query->execute($data);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }      

    /**
     * closeConnection
     * 
     * This closes the connection to the database
     */
    public function closeConnection() 
    {
        $this->dbh = null;
    }

}