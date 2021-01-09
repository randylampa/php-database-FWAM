<?php

namespace TgDatabase;

/**
 * A simple class to keep all DAOs in one place
 *
 * @author ralph
 *        
 */
class DataModel {

    private $database;

    private $models;

    /**
     * Constructor.
     * @param Database $database - the database instance
     */
    public function __construct($database) {
        $this->database = $database;
        $this->models = array();
        $this->init($database);
    }

    /**
     * Initializes all DAOs.
     * <p>This method does nothing. Descendants shall override here and create and register their DAOs.</p>
     * @param Database $database - the database object
     */
    protected function init($database) {
    }

    /**
     * Returns a DAO registered under a certain name.
     * @param String name - name of model
     * return DAO the DAO registered or NULL
     */
    public function get($name) {
        return $this->models[$name];
    }

    /**
     * Returns the database object.
     * @return Database the database object.
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * Registers a DAO under a name.
     * @param string $name - the name of the model
     * @param DAO $dao - the DAO to be registered
     */
    public function register($name, $dao) {
        $this->models[$name] = $dao;
    }

	/**
	 * Performs a check of each DAO whether underlying tables exists and creates them if required.
	 * @return object with the result(s):
	 * {
	 *   "tableChecks" : {
	 *      "<dao-registered-name>" : TRUE|FALSE,
	 *      ...
	 *   },
	 *   "success" : TRUE|FALSE
	 * }
	 */
	public function checkTables() {
		$rc = new \stdClass;
		$rc->tableChecks = new \stdClass;
		$rc->success     = TRUE;
		foreach ($this->models AS $name => $dao) {
			$rc->tableChecks->$name = $dao->checkTable();
			if (!$rc->tableChecks->$name) $rc->success = FALSE;
		}
		return $rc;
	}
}

