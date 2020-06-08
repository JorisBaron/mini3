<?php

namespace Mini\Core;

use PDO;
use PDOException;

class Model
{
    /**
     * @var null Database Connection
     */
    public static $sDb = null;
    public $db = null;

	/**
	 * Model constructor.
	 * Design pattern Singleton
	 */
    function __construct() {
		if(!isset(self::$sDb)) {
			try {
				self::openDatabaseConnection();
			}
			catch(PDOException $e) {
				exit('Database connection could not be established.');
			}
		}

		$this->db = self::$sDb;
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name"] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        
        // setting the encoding is different when using PostgreSQL
        if (DB_TYPE == "pgsql") {
            $databaseEncodingenc = " options='--client_encoding=" . DB_CHARSET . "'";
        } else {
            $databaseEncodingenc = "; charset=" . DB_CHARSET;
        }

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        self::$sDb = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.$databaseEncodingenc, DB_USER, DB_PASS, $options);
    }
}
