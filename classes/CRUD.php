<?php

include 'RssFeedsConnect.php';

/**
 * This class acts as a controller between the database and the view,
 * checks for the value passed and returns result accordingly.
 * 
 * @author  Sukh
 */
class CRUD 
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
     */
    public function __construct(PDO $dbh) {
       $this->dbh = new RssFeedsConnect($dbh);
    }

    /**
     * show
     * 
     * This finds connects to the database and returns all results from the database
     * 
     * @return $this->dbh->showAll() shows all results from db.
     */
    public function show() {
        return $this->dbh->showAll();
    }

    /**
     * insert
     * 
     * This tries to insert the URL value if validated.
     * 
     * @param string $value URL value
     * 
     * @return true if value inserted
     * @return false if value not inserted or fails validation
     */
    public function insert($value) {
        $clean_value = $this->validateSanitiseURL($value);
        if($clean_value) {
            if($this->dbh->insert($clean_value)) {
                return true;
            }else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * update
     * 
     * This updates the previous URL value to the new one once validated.
     * 
     * @param string $value URL value
     * @param string $valueToChange Value to be changed to
     * 
     * @return true if value updated
     * @return false if value not updated or fails validation
     */
    public function update($value, $valueToChange) {
        $clean_old_value = $this->validateSanitiseURL($value);
        $clean_new_value = $this->validateSanitiseURL($valueToChange);
        if($clean_old_value && $clean_new_value) {
            if($this->dbh->update($clean_old_value, $clean_new_value)) {
                return true;
            }else {
                return false;
            }
        } else {
            return false;
        }
        return $this->dbh->update($value, $valueToChange);
    }

    /**
     * delete
     * 
     * This tries to delete the URL value once validated.
     * 
     * @param string $value URL value
     * 
     * @return true if value deleted
     * @return false if value not deleted or fails validation
     */
    public function delete($value) {
        $clean_value = $this->validateSanitiseURL($value);
        if($clean_value) {
            if($this->dbh->delete($clean_value)) {
                return true;
            }else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * validateSanitiseURL
     * 
     * This validates then sanitises the value passed for security reasons.
     * 
     * @param string $url Pass the url for validation
     * 
     * @return $sanitised_url if value validated
     * @return false if value not validated
     */
    private function validateSanitiseURL($url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $validated_url = filter_var($url, FILTER_VALIDATE_URL);
            if(filter_var($validated_url, FILTER_SANITIZE_URL)) {
                $sanitised_url = filter_var($validated_url, FILTER_SANITIZE_URL);
                return $sanitised_url;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}