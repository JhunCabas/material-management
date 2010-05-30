<?php
/**
 * Gets schema information for the selected database
 * 
 * @copyright  Copyright (c) 2007-2009 Will Bond
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * @link       http://flourishlib.com/fSchema
 * 
 * @version    1.0.0b25
 * @changes    1.0.0b25  One-to-one relationships utilizing the primary key as a foreign key are now properly detected [wb, 2009-09-22]
 * @changes    1.0.0b24  Fixed MSSQL support to work with ODBC database connections [wb, 2009-09-18]
 * @changes    1.0.0b23  Fixed a bug where one-to-one relationships were being listed as many-to-one [wb, 2009-07-21]
 * @changes    1.0.0b22  PostgreSQL UNIQUE constraints that are created as indexes and not table constraints are now properly detected [wb, 2009-07-08]
 * @changes    1.0.0b21  Added support for the UUID data type in PostgreSQL [wb, 2009-06-18]
 * @changes    1.0.0b20  Add caching of merged info, improved performance of ::getColumnInfo() [wb, 2009-06-15]
 * @changes    1.0.0b19  Fixed a couple of bugs with ::setKeysOverride() [wb, 2009-06-04]
 * @changes    1.0.0b18  Added missing support for MySQL mediumint columns [wb, 2009-05-18]
 * @changes    1.0.0b17  Fixed a bug with ::clearCache() not properly reseting the tables and databases list [wb, 2009-05-13]
 * @changes    1.0.0b16  Backwards Compatibility Break - ::setCacheFile() changed to ::enableCaching() and now requires an fCache object, ::flushInfo() renamed to ::clearCache(), added Oracle support [wb, 2009-05-04]
 * @changes    1.0.0b15  Added support for the three different types of identifier quoting in SQLite [wb, 2009-03-28]
 * @changes    1.0.0b14  Added support for MySQL column definitions containing the COLLATE keyword [wb, 2009-03-28]
 * @changes    1.0.0b13  Fixed a bug with detecting PostgreSQL columns having both a CHECK constraint and a UNIQUE constraint [wb, 2009-02-27]
 * @changes    1.0.0b12  Fixed detection of multi-column primary keys in MySQL [wb, 2009-02-27]
 * @changes    1.0.0b11  Fixed an issue parsing MySQL tables with comments [wb, 2009-02-25]
 * @changes    1.0.0b10  Added the ::getDatabases() method [wb, 2009-02-24]
 * @changes    1.0.0b9   Now detects unsigned and zerofill MySQL data types that do not have a parenthetical part [wb, 2009-02-16]
 * @changes    1.0.0b8   Mapped the MySQL data type `'set'` to `'varchar'`, however valid values are not implemented yet [wb, 2009-02-01]
 * @changes    1.0.0b7   Fixed a bug with detecting MySQL timestamp columns [wb, 2009-01-28]
 * @changes    1.0.0b6   Fixed a bug with detecting MySQL columns that accept `NULL` [wb, 2009-01-19]
 * @changes    1.0.0b5   ::setColumnInfo(): fixed a bug with not grabbing the real database schema first, made general improvements [wb, 2009-01-19]
 * @changes    1.0.0b4   Added support for MySQL binary data types, numeric data type options unsigned and zerofill, and per-column character set definitions [wb, 2009-01-17]
 * @changes    1.0.0b3   Fixed detection of the data type of MySQL timestamp columns, added support for dynamic default date/time values [wb, 2009-01-11]
 * @changes    1.0.0b2   Fixed a bug with detecting multi-column unique keys in MySQL [wb, 2009-01-03]
 * @changes    1.0.0b    The initial implementation [wb, 2007-09-25]
 */
class fSchema
{
	/**
	 * The place to cache to
	 * 
	 * @var fCache
	 */
	private $cache = NULL;
	
	/**
	 * The cached column info
	 * 
	 * @var array
	 */
	private $column_info = array();
	
	/**
	 * The column info to override
	 * 
	 * @var array
	 */
	private $column_info_override = array();
	
	/**
	 * A reference to an instance of the fDatabase class
	 * 
	 * @var fDatabase
	 */
	private $database = NULL;
	
	/**
	 * The databases on the current database server
	 * 
	 * @var array
	 */
	private $databases = NULL;
	
	/**
	 * The cached key info
	 * 
	 * @var array
	 */
	private $keys = array();
	
	/**
	 * The key info to override
	 * 
	 * @var array
	 */
	private $keys_override = array();
	
	/**
	 * The merged column info
	 * 
	 * @var array
	 */
	private $merged_column_info = array();
	
	/**
	 * The merged key info
	 * 
	 * @var array
	 */
	private $merged_keys = array();
	
	/**
	 * The relationships in the database
	 * 
	 * @var array
	 */
	private $relationships = array();
	
	/**
	 * The tables in the database
	 * 
	 * @var array
	 */
	private $tables = NULL;
	
	
	/**
	 * Sets the database
	 * 
	 * @param  fDatabase $database  The fDatabase instance
	 * @return fSchema
	 */
	public function __construct($database)
	{
		$this->database = $database;
	}
	
	
	/**
	 * All requests that hit this method should be requests for callbacks
	 * 
	 * @internal
	 * 
	 * @param  string $method  The method to create a callback for
	 * @return callback  The callback for the method requested
	 */
	public function __get($method)
	{
		return array($this, $method);		
	}
	
	
	/**
	 * Checks to see if a column is part of a single-column `UNIQUE` key
	 * 
	 * @param  string $table   The table the column is located in
	 * @param  string $column  The column to check
	 * @return boolean  If the column is part of a single-column unique key
	 */
	private function checkForSingleColumnUniqueKey($table, $column)
	{        
		foreach ($this->merged_keys[$table]['unique'] as $key) {
			if (array($column) == $key) {
				return TRUE;
			}
		}
		if (array($column) == $this->merged_keys[$table]['primary']) {
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	 * Clears all of the schema info out of the object and, if set, the fCache object
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public function clearCache()
	{
		$this->column_info        = array();
		$this->databases          = NULL;
		$this->keys               = array();
		$this->merged_column_info = array();
		$this->merged_keys        = array();
		$this->relationships      = array();
		$this->tables             = NULL;
		if ($this->cache) {
			$prefix = $this->makeCachePrefix();
			$this->cache->delete($prefix . 'column_info');
			$this->cache->delete($prefix . 'databases');
			$this->cache->delete($prefix . 'keys');
			$this->cache->delete($prefix . 'merged_column_info');
			$this->cache->delete($prefix . 'merged_keys');
			$this->cache->delete($prefix . 'relationships');
			$this->cache->delete($prefix . 'tables');
		}
	}
	
	
	/**
	 * Sets the schema to be cached to the fCache object specified
	 * 
	 * @param  fCache $cache  The cache to cache to
	 * @return void
	 */
	public function enableCaching($cache)
	{
		$this->cache = $cache;
		
		$prefix = $this->makeCachePrefix();
		$this->column_info        = $this->cache->get($prefix . 'column_info',          array());
		$this->databases          = $this->cache->get($prefix . 'databases',            NULL);
		$this->keys               = $this->cache->get($prefix . 'keys',                 array());
		
		if (!$this->column_info_override && !$this->keys_override) {
			$this->merged_column_info = $this->cache->get($prefix . 'merged_column_info',   array());
			$this->merged_keys        = $this->cache->get($prefix . 'merged_keys',          array());  
			$this->relationships      = $this->cache->get($prefix . 'relationships',        array());
		}
		
		$this->tables             = $this->cache->get($prefix . 'tables',               NULL);   
	}
	
	
	/**
	 * Gets the column info from the database for later access
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return void
	 */
	private function fetchColumnInfo($table)
	{
		if (isset($this->column_info[$table])) {
			return;	
		}
		
		switch ($this->database->getType()) {
			case 'mssql':
				$column_info = $this->fetchMSSQLColumnInfo($table);
				break;
			
			case 'mysql':
				$column_info = $this->fetchMySQLColumnInfo($table);
				break;
				
			case 'oracle':
				$column_info = $this->fetchOracleColumnInfo($table);
				break;
			
			case 'postgresql':
				$column_info = $this->fetchPostgreSQLColumnInfo($table);
				break;
				
			case 'sqlite':
				$column_info = $this->fetchSQLiteColumnInfo($table);
				break;
		}
			
		if (!$column_info) {
			return;	
		}
			
		$this->column_info[$table] = $column_info;
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'column_info', $this->column_info);	
		}
	}
	
	
	/**
	 * Gets the `PRIMARY KEY`, `FOREIGN KEY` and `UNIQUE` key constraints from the database
	 * 
	 * @return void
	 */
	private function fetchKeys()
	{
		if ($this->keys) {
			return;	
		}
		
		switch ($this->database->getType()) {
			case 'mssql':
				$keys = $this->fetchMSSQLKeys();
				break;
				
			case 'mysql':
				$keys = $this->fetchMySQLKeys();
				break;
				
			case 'oracle':
				$keys = $this->fetchOracleKeys();
				break;
			
			case 'postgresql':
				$keys = $this->fetchPostgreSQLKeys();
				break;
			
			case 'sqlite':
				$keys = $this->fetchSQLiteKeys();
				break;
		}
			
		$this->keys = $keys;
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'keys', $this->keys);	
		}
	}
	
	
	/**
	 * Gets the column info from a MSSQL database
	 * 
	 * The returned array is in the format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value-may contain special string CURRENT_TIMESTAMP},
	 *         'valid_values'   => (array)   {the valid values for a char/varchar field},
	 *         'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal/numeric/money/smallmoney field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is an identity column}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see method description for details
	 */
	private function fetchMSSQLColumnInfo($table)
	{
		$column_info = array();
		
		$data_type_mapping = array(
			'bit'			    => 'boolean',
			'tinyint'           => 'integer',
			'smallint'			=> 'integer',
			'int'				=> 'integer',
			'bigint'			=> 'integer',
			'datetime'			=> 'timestamp',
			'smalldatetime'     => 'timestamp',
			'datetime2'         => 'timestamp',
			'date'              => 'date',
			'time'              => 'time',
			'varchar'	        => 'varchar',
			'nvarchar'          => 'varchar',
			'char'			    => 'char',
			'nchar'             => 'char',
			'real'				=> 'float',
			'float'             => 'float',
			'money'             => 'float',
			'smallmoney'        => 'float',
			'decimal'			=> 'float',
			'numeric'			=> 'float',
			'binary'			=> 'blob',
			'varbinary'         => 'blob',
			'image'             => 'blob',
			'text'				=> 'text',
			'ntext'             => 'text'
		);
		
		// Get the column info
		$sql = "SELECT
						c.column_name              AS 'column',
						c.data_type                AS 'type',
						c.is_nullable              AS nullable,
						c.column_default           AS 'default',
						c.character_maximum_length AS max_length,
						c.numeric_scale            AS decimal_places,
						CASE
							WHEN
							  COLUMNPROPERTY(OBJECT_ID(QUOTENAME(c.table_schema) + '.' + QUOTENAME(c.table_name)), c.column_name, 'IsIdentity') = 1 AND
							  OBJECTPROPERTY(OBJECT_ID(QUOTENAME(c.table_schema) + '.' + QUOTENAME(c.table_name)), 'IsMSShipped') = 0
							THEN '1'
							ELSE '0'
						  END AS auto_increment,
						cc.check_clause AS 'constraint'
					FROM
						INFORMATION_SCHEMA.COLUMNS AS c LEFT JOIN
						INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE AS ccu ON c.column_name = ccu.column_name AND c.table_name = ccu.table_name AND c.table_catalog = ccu.table_catalog LEFT JOIN
						INFORMATION_SCHEMA.CHECK_CONSTRAINTS AS cc ON ccu.constraint_name = cc.constraint_name AND ccu.constraint_catalog = cc.constraint_catalog
					WHERE
						c.table_name = '" . $table . "' AND
						c.table_catalog = DB_NAME()";
		$result = $this->database->query($sql);
		
		foreach ($result as $row) {
			
			$info = array();
			
			foreach ($data_type_mapping as $data_type => $mapped_data_type) {
				if (stripos($row['type'], $data_type) === 0) {
					$info['type'] = $mapped_data_type;
					break;
				}
			}
			
			if (!isset($info['type'])) {
				$info['type'] = $row['type'];
			}
			
			// Handle decimal places for numeric/decimals
			if (in_array($row['type'], array('numeric', 'decimal'))) {
				$info['decimal_places'] = $row['decimal_places'];
			}
			
			// Handle decimal places for money/smallmoney
			if (in_array($row['type'], array('money', 'smallmoney'))) {
				$info['decimal_places'] = 2;
			}
			
			// Handle the special data for varchar columns
			if (in_array($info['type'], array('char', 'varchar'))) {
				$info['max_length'] = $row['max_length'];
			}
			
			// If the column has a constraint, look for valid values
			if (in_array($info['type'], array('char', 'varchar')) && !empty($row['constraint'])) {
				if (preg_match('#^\(((?:(?: OR )?\[[^\]]+\]\s*=\s*\'(?:\'\'|[^\']+)+\')+)\)$#D', $row['constraint'], $matches)) {
					$valid_values = explode(' OR ', $matches[1]);
					foreach ($valid_values as $key => $value) {
						$value = preg_replace('#^\s*\[' . preg_quote($row['column'], '#') . '\]\s*=\s*\'(.*)\'\s*$#', '\1', $value);
						$valid_values[$key] = str_replace("''", "'", $value);
					}
					// SQL Server turns CHECK constraint values into a reversed list, so we fix it here
					$info['valid_values'] = array_reverse($valid_values);
				}
			}
			
			// Handle auto increment
			if ($row['auto_increment']) {
				$info['auto_increment'] = TRUE;
			}
			
			// Handle default values
			if ($row['default'] !== NULL) {
				if ($row['default'] == '(getdate())') {
					$info['default'] = 'CURRENT_TIMESTAMP';
				} elseif (in_array($info['type'], array('char', 'varchar', 'text', 'timestamp')) ) {
					$info['default'] = substr($row['default'], 2, -2);
				} elseif ($info['type'] == 'boolean') {
					$info['default'] = (boolean) substr($row['default'], 2, -2);
				} elseif (in_array($info['type'], array('integer', 'float')) ) {
					$info['default'] = str_replace(array('(', ')'), '', $row['default']);
				} else {
					$info['default'] = pack('H*', substr($row['default'], 3, -1));
				}
			}
			
			// Handle not null
			$info['not_null'] = ($row['nullable'] == 'NO') ? TRUE : FALSE;
			
			$column_info[$row['column']] = $info;
		}
		
		return $column_info;
	}
	
	
	/**
	 * Fetches the key info for an MSSQL database
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @return array  The key info arrays for every table in the database - see method description for details
	 */
	private function fetchMSSQLKeys()
	{
		$keys = array();
		
		$tables   = $this->getTables();
		foreach ($tables as $table) {
			$keys[$table] = array();
			$keys[$table]['primary'] = array();
			$keys[$table]['unique']  = array();
			$keys[$table]['foreign'] = array();
		}
		
		$sql  = "SELECT
						c.table_name AS 'table',
						kcu.constraint_name AS constraint_name,
						CASE c.constraint_type
							WHEN 'PRIMARY KEY' THEN 'primary'
							WHEN 'FOREIGN KEY' THEN 'foreign'
							WHEN 'UNIQUE' THEN 'unique'
						END AS 'type',
						kcu.column_name AS 'column',
						ccu.table_name AS foreign_table,
						ccu.column_name AS foreign_column,
						REPLACE(LOWER(rc.delete_rule), ' ', '_') AS on_delete,
						REPLACE(LOWER(rc.update_rule), ' ', '_') AS on_update
					FROM
						INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS c INNER JOIN
						INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu ON c.table_name = kcu.table_name AND c.constraint_name = kcu.constraint_name LEFT JOIN
						INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS rc ON c.constraint_name = rc.constraint_name LEFT JOIN
						INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE AS ccu ON ccu.constraint_name = rc.unique_constraint_name
					WHERE
						c.constraint_catalog = DB_NAME() AND
						c.table_name != 'sysdiagrams'
					ORDER BY
						LOWER(c.table_name),
						c.constraint_type,
						LOWER(kcu.constraint_name),
						kcu.ordinal_position,
						LOWER(kcu.column_name)";
		
		$result = $this->database->query($sql);
		
		$last_name  = '';
		$last_table = '';
		$last_type  = '';
		foreach ($result as $row) {
			
			if ($row['constraint_name'] != $last_name) {
				
				if ($last_name) {
					if ($last_type == 'foreign' || $last_type == 'unique') {
						if (!isset($keys[$last_table][$last_type])) {
							$keys[$last_table][$last_type] = array();		
						}
						$keys[$last_table][$last_type][] = $temp;
					} else {
						$keys[$last_table][$last_type] = $temp;
					}
				}
				
				$temp = array();
				if ($row['type'] == 'foreign') {
					
					$temp['column']         = $row['column'];
					$temp['foreign_table']  = $row['foreign_table'];
					$temp['foreign_column'] = $row['foreign_column'];
					$temp['on_delete']      = 'no_action';
					$temp['on_update']      = 'no_action';
					if (!empty($row['on_delete'])) {
						$temp['on_delete'] = $row['on_delete'];
					}
					if (!empty($row['on_update'])) {
						$temp['on_update'] = $row['on_update'];
					}
					
				} else {
					$temp[] = $row['column'];
				}
				
				$last_table = $row['table'];
				$last_name  = $row['constraint_name'];
				$last_type  = $row['type'];
				
			} else {
				$temp[] = $row['column'];
			}
		}
		
		if (isset($temp)) {
			if ($last_type == 'foreign') {
				if (!isset($keys[$last_table][$last_type])) {
					$keys[$last_table][$last_type] = array();		
				}
				$keys[$last_table][$last_type][] = $temp;
			} else {
				$keys[$last_table][$last_type] = $temp;
			}
		}
		
		return $keys;
	}
	
	
	/**
	 * Gets the column info from a MySQL database
	 * 
	 * The returned array is in the format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value-may contain special string CURRENT_TIMESTAMP},
	 *         'valid_values'   => (array)   {the valid values for a char/varchar field},
	 *         'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is auto_increment}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see method description for details
	 */
	private function fetchMySQLColumnInfo($table)
	{
		$data_type_mapping = array(
			'tinyint'			=> 'integer',
			'smallint'			=> 'integer',
			'mediumint'         => 'integer',
			'int'				=> 'integer',
			'bigint'			=> 'integer',
			'datetime'			=> 'timestamp',
			'timestamp'			=> 'timestamp',
			'date'				=> 'date',
			'time'				=> 'time',
			'enum'				=> 'varchar',
			'set'               => 'varchar',
			'varchar'			=> 'varchar',
			'char'				=> 'char',
			'float'				=> 'float',
			'double'			=> 'float',
			'decimal'			=> 'float',
			'binary'            => 'blob',
			'varbinary'         => 'blob',
			'tinyblob'			=> 'blob',
			'blob'				=> 'blob',
			'mediumblob'		=> 'blob',
			'longblob'			=> 'blob',
			'tinytext'			=> 'text',
			'text'				=> 'text',
			'mediumtext'		=> 'text',
			'longtext'			=> 'text'
		);
		
		$column_info = array();
		
		$result     = $this->database->query('SHOW CREATE TABLE ' . $table);
		
		try {
			$row        = $result->fetchRow();
			$create_sql = $row['Create Table'];
		} catch (fNoRowsException $e) {
			return array();			
		}
		
		preg_match_all('#(?<=,|\()\s+(?:"|\`)(\w+)(?:"|\`)\s+(?:([a-z]+)(?:\(([^)]+)\))?(?: unsigned| zerofill){0,2})(?: character set [^ ]+)?(?: collate [^ ]+)?(?: NULL)?( NOT NULL)?(?: DEFAULT ((?:[^, \']*|\'(?:\'\'|[^\']+)*\')))?( auto_increment)?( COMMENT \'(?:\'\'|[^\']+)*\')?( ON UPDATE CURRENT_TIMESTAMP)?\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $match) {
			
			$info = array();
			
			foreach ($data_type_mapping as $data_type => $mapped_data_type) {
				if (stripos($match[2], $data_type) === 0) {
					if ($match[2] == 'tinyint' && $match[3] == 1) {
						$mapped_data_type = 'boolean';	
					}
				
					$info['type'] = $mapped_data_type;
					break;
				}
			}
			if (!isset($info['type'])) {
				$info['type'] = preg_replace('#^([a-z ]+).*$#iD', '\1', $match[2]);
			}
		
			if (stripos($match[2], 'enum') === 0) {
				$info['valid_values'] = preg_replace("/^'|'\$/D", '', explode(",", $match[3]));
				$match[3] = 0;
				foreach ($info['valid_values'] as $valid_value) {
					if (strlen(utf8_decode($valid_value)) > $match[3]) {
						$match[3] = strlen(utf8_decode($valid_value));
					}
				}
			}
			
			// The set data type is currently only supported as a varchar
			// with a max length of all valid values concatenated by ,s
			if (stripos($match[2], 'set') === 0) {
				$values = preg_replace("/^'|'\$/D", '', explode(",", $match[3]));
				$match[3] = strlen(join(',', $values));
			}
			
			// Type specific information
			if (in_array($info['type'], array('char', 'varchar'))) {
				$info['max_length'] = $match[3];
			}
			
			// Grab the number of decimal places
			if (stripos($match[2], 'decimal') === 0) {
				if (preg_match('#^\s*\d+\s*,\s*(\d+)\s*$#D', $match[3], $data_type_info)) {
					$info['decimal_places'] = $data_type_info[1];
				}
			}
			
			// Not null
			$info['not_null'] = (!empty($match[4])) ? TRUE : FALSE;
		
			// Default values
			if (!empty($match[5]) && $match[5] != 'NULL') {
				$info['default'] = preg_replace("/^'|'\$/D", '', $match[5]);
			}
			
			if ($info['type'] == 'boolean' && isset($info['default'])) {
				$info['default'] = (boolean) $info['default'];
			}
		
			// Auto increment fields
			if (!empty($match[6])) {
				$info['auto_increment'] = TRUE;
			}
		
			$column_info[$match[1]] = $info;
		}
		
		return $column_info;
	}
	
	
	/**
	 * Fetches the keys for a MySQL database
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @return array  The keys arrays for every table in the database - see method description for details
	 */
	private function fetchMySQLKeys()
	{
		$tables   = $this->getTables();
		$keys = array();
		
		foreach ($tables as $table) {
			
			$keys[$table] = array();
			$keys[$table]['primary'] = array();
			$keys[$table]['foreign'] = array();
			$keys[$table]['unique']  = array();
			
			$result = $this->database->query('SHOW CREATE TABLE `' . substr($this->database->escape('string', $table), 1, -1) . '`');
			$row    = $result->fetchRow();
			
			// Primary keys
			preg_match_all('/PRIMARY KEY\s+\("(.*?)"\),?\n/U', $row['Create Table'], $matches, PREG_SET_ORDER);
			if (!empty($matches)) {
				$keys[$table]['primary'] = explode('","', $matches[0][1]);
			}
			
			// Unique keys
			preg_match_all('/UNIQUE KEY\s+"([^"]+)"\s+\("(.*?)"\),?\n/U', $row['Create Table'], $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				$keys[$table]['unique'][] = explode('","', $match[2]);
			}
			
			// Foreign keys
			preg_match_all('#FOREIGN KEY \("([^"]+)"\) REFERENCES "([^"]+)" \("([^"]+)"\)(?:\sON\sDELETE\s(SET\sNULL|SET\sDEFAULT|CASCADE|NO\sACTION|RESTRICT))?(?:\sON\sUPDATE\s(SET\sNULL|SET\sDEFAULT|CASCADE|NO\sACTION|RESTRICT))?#', $row['Create Table'], $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				$temp = array('column'         => $match[1],
							  'foreign_table'  => $match[2],
							  'foreign_column' => $match[3],
							  'on_delete'      => 'no_action',
							  'on_update'      => 'no_action');
				if (isset($match[4])) {
					$temp['on_delete'] = strtolower(str_replace(' ', '_', $match[4]));
				}
				if (isset($match[5])) {
					$temp['on_update'] = strtolower(str_replace(' ', '_', $match[5]));
				}
				$keys[$table]['foreign'][] = $temp;
			}
		}
		
		return $keys;
	}
	
	
	/**
	 * Gets the column info from an Oracle database
	 * 
	 * The returned array is in the format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value-may contain special strings CURRENT_TIMESTAMP, CURRENT_TIME or CURRENT_DATE},
	 *         'valid_values'   => (array)   {the valid values for a char/varchar field},
	 *         'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is auto_increment}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see method description for details
	 */
	private function fetchOracleColumnInfo($table)
	{
		$table = strtoupper($table);
		
		$column_info = array();
		
		$data_type_mapping = array(
			'boolean'			=> 'boolean',
			'integer'			=> 'integer',
			'timestamp'			=> 'timestamp',
			'date'				=> 'date',
			'varchar2'          => 'varchar',
			'nvarchar2'			=> 'varchar',
			'char'              => 'char',
			'nchar'             => 'char',
			'float'				=> 'float',
			'binary_float'      => 'float',
			'binary_double'     => 'float',
			'blob'				=> 'blob',
			'bfile'             => 'varchar',
			'clob'				=> 'text',
			'nclob'             => 'text'
		);
		
		$sql = "SELECT
						LOWER(UTC.COLUMN_NAME) COLUMN_NAME,
						CASE
							WHEN
								UTC.DATA_TYPE = 'NUMBER' AND
								UTC.DATA_PRECISION IS NULL AND
								UTC.DATA_SCALE = 0
							THEN
								'integer'
							WHEN
								UTC.DATA_TYPE = 'NUMBER' AND
								UTC.DATA_PRECISION = 1 AND
								UTC.DATA_SCALE = 0
							THEN
								'boolean'
							WHEN
								UTC.DATA_TYPE = 'NUMBER' AND
								UTC.DATA_PRECISION IS NOT NULL AND
								UTC.DATA_SCALE != 0 AND
								UTC.DATA_SCALE IS NOT NULL
							THEN
								'float'
							ELSE
								LOWER(UTC.DATA_TYPE)
							END DATA_TYPE,
						CASE
							WHEN
								UTC.CHAR_LENGTH <> 0
							THEN
								UTC.CHAR_LENGTH
							WHEN
								UTC.DATA_TYPE = 'NUMBER' AND
								UTC.DATA_PRECISION != 1 AND
								UTC.DATA_SCALE != 0	AND
								UTC.DATA_PRECISION IS NOT NULL
							THEN
								UTC.DATA_SCALE	
							ELSE
								NULL
							END LENGTH,
						UTC.NULLABLE,
						UTC.DATA_DEFAULT,
						UC.SEARCH_CONDITION CHECK_CONSTRAINT
					FROM
						USER_TAB_COLUMNS UTC LEFT JOIN
						USER_CONS_COLUMNS UCC ON
							UTC.COLUMN_NAME = UCC.COLUMN_NAME AND
							UTC.TABLE_NAME = UCC.TABLE_NAME AND
							UCC.POSITION IS NULL LEFT JOIN
						USER_CONSTRAINTS UC ON
							UC.CONSTRAINT_NAME = UCC.CONSTRAINT_NAME AND
							UC.CONSTRAINT_TYPE = 'C' AND
							UC.STATUS = 'ENABLED'
					WHERE
						UTC.TABLE_NAME = %s
					ORDER BY
						UTC.TABLE_NAME ASC,
						UTC.COLUMN_ID ASC";
		$result = $this->database->query($sql, $table);
		
		foreach ($result as $row) {
			
			$column = $row['column_name'];
			
			// Since Oracle stores check constraints in LONG columns, it is
			// not possible to check or modify the constraints in SQL which
			// ends up causing multiple rows with duplicate data except for
			// the check constraint
			$duplicate = FALSE;
			
			if (isset($column_info[$column])) {
				$info = $column_info[$column];
				$duplicate = TRUE;	
			} else {
				$info = array();	
			}
			
			if (!$duplicate) {
				// Get the column type
				foreach ($data_type_mapping as $data_type => $mapped_data_type) {
					if (stripos($row['data_type'], $data_type) === 0) {
						$info['type'] = $mapped_data_type;
						break;
					}
				}
				
				if (!isset($info['type'])) {
					$info['type'] = $row['data_type'];
				}
				
				// Handle the length of decimal/numeric fields
				if ($info['type'] == 'float' && $row['length']) {
					$info['decimal_places'] = (int) $row['length'];
				}
				
				// Handle the special data for varchar fields
				if (in_array($info['type'], array('char', 'varchar'))) {
					$info['max_length'] = (int) $row['length'];
				}
			}
			
			// Handle check constraints that are just simple lists
			if (in_array($info['type'], array('varchar', 'char')) && $row['check_constraint']) {
				if (preg_match('/^\s*' . preg_quote($column, '/') . '\s+in\s+\((.*?)\)\s*$/i', $row['check_constraint'], $match)) {
					if (preg_match_all("/(?<!')'((''|[^']+)*)'/", $match[1], $matches, PREG_PATTERN_ORDER)) {
						$info['valid_values'] = str_replace("''", "'", $matches[1]);
					}			
				}
			}
			
			if (!$duplicate) {
				// Handle default values
				if ($row['data_default'] !== NULL) {
					if (in_array($info['type'], array('char', 'varchar', 'text'))) {
						$info['default'] = str_replace("''", "'", substr(trim($row['data_default']), 1, -1));
						
					} elseif ($info['type'] == 'boolean') {
						$info['default'] = (boolean) trim($row['data_default']);
						
					} elseif (in_array($info['type'], array('integer', 'float'))) {
						$info['default'] = trim($row['data_default']);
						
					} else {
						$info['default'] = $row['data_default'];
					}
				}
			
				// Not null values
				$info['not_null'] = ($row['nullable'] == 'N') ? TRUE : FALSE;
			}
			
			$column_info[$column] = $info;
		}
		
		$sql = "SELECT
						TRIGGER_BODY
					FROM
						USER_TRIGGERS
					WHERE
						TRIGGERING_EVENT = 'INSERT' AND
						STATUS = 'ENABLED' AND
						TRIGGER_NAME NOT LIKE 'BIN\$%' AND
						TABLE_NAME = %s";
						
		foreach ($this->database->query($sql, $table) as $row) {
			if (preg_match('#SELECT\s+(\w+).nextval\s+INTO\s+:new\.(\w+)\s+FROM\s+dual#i', $row['trigger_body'], $matches)) {
				$column = strtolower($matches[2]);
				$column_info[$column]['auto_increment'] = TRUE;
			}
		}
		
		return $column_info;
	}
	
	
	/**
	 * Fetches the key info for an Oracle database
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @return array  The keys arrays for every table in the database - see method description for details
	 */
	private function fetchOracleKeys()
	{
		$keys = array();
		
		$tables = $this->getTables();
		foreach ($tables as $table) {
			$keys[$table] = array();
			$keys[$table]['primary'] = array();
			$keys[$table]['unique']  = array();
			$keys[$table]['foreign'] = array();
		}
		
		$sql  = "SELECT
						 LOWER(UC.TABLE_NAME) \"TABLE\",
						 UC.CONSTRAINT_NAME CONSTRAINT_NAME,
						 CASE UC.CONSTRAINT_TYPE
							 WHEN 'P' THEN 'primary'
							 WHEN 'R' THEN 'foreign'
							 WHEN 'U' THEN 'unique'
							 END TYPE,
						 LOWER(UCC.COLUMN_NAME) \"COLUMN\",
						 LOWER(FKC.TABLE_NAME) FOREIGN_TABLE,
						 LOWER(FKC.COLUMN_NAME) FOREIGN_COLUMN,
						 CASE WHEN FKC.TABLE_NAME IS NOT NULL THEN REPLACE(LOWER(UC.DELETE_RULE), ' ', '_') ELSE NULL END ON_DELETE
					 FROM
						 USER_CONSTRAINTS UC INNER JOIN
						 USER_CONS_COLUMNS UCC ON UC.CONSTRAINT_NAME = UCC.CONSTRAINT_NAME LEFT JOIN
						 USER_CONSTRAINTS FK ON UC.R_CONSTRAINT_NAME = FK.CONSTRAINT_NAME LEFT JOIN
						 USER_CONS_COLUMNS FKC ON FK.CONSTRAINT_NAME = FKC.CONSTRAINT_NAME
					 WHERE
						 UC.CONSTRAINT_TYPE IN ('U', 'P', 'R') AND
						 UC.STATUS = 'ENABLED' AND
						 SUBSTR(UC.TABLE_NAME, 1, 4) <> 'BIN\$'
					 ORDER BY
						 UC.TABLE_NAME ASC,
						 UC.CONSTRAINT_TYPE ASC,
						 UC.CONSTRAINT_NAME ASC,
						 UCC.POSITION ASC";
		
		$result = $this->database->query($sql);
		
		$last_name  = '';
		$last_table = '';
		$last_type  = '';
		foreach ($result as $row) {
			
			if ($row['constraint_name'] != $last_name) {
				
				if ($last_name) {
					if ($last_type == 'foreign' || $last_type == 'unique') {
						$keys[$last_table][$last_type][] = $temp;
					} else {
						$keys[$last_table][$last_type] = $temp;
					}
				}
				
				$temp = array();
				if ($row['type'] == 'foreign') {
					
					$temp['column']         = $row['column'];
					$temp['foreign_table']  = $row['foreign_table'];
					$temp['foreign_column'] = $row['foreign_column'];
					$temp['on_delete']      = 'no_action';
					$temp['on_update']      = 'no_action';
					
					if (!empty($row['on_delete'])) {
						$temp['on_delete'] = $row['on_delete'];
					}
					
				} else {
					$temp[] = $row['column'];
				}
				
				$last_table = $row['table'];
				$last_name  = $row['constraint_name'];
				$last_type  = $row['type'];
				
			} else {
				$temp[] = $row['column'];
			}
		}
		
		if (isset($temp)) {
			if ($last_type == 'foreign' || $last_type == 'unique') {
				$keys[$last_table][$last_type][] = $temp;
			} else {
				$keys[$last_table][$last_type] = $temp;
			}
		}
		
		return $keys;
	}
	
	
	/**
	 * Gets the column info from a PostgreSQL database
	 * 
	 * The returned array is in the format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value-may contain special strings CURRENT_TIMESTAMP, CURRENT_TIME or CURRENT_DATE},
	 *         'valid_values'   => (array)   {the valid values for a char/varchar field},
	 *         'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is auto_increment}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see method description for details
	 */
	private function fetchPostgreSQLColumnInfo($table)
	{
		$column_info = array();
		
		$data_type_mapping = array(
			'boolean'			=> 'boolean',
			'smallint'			=> 'integer',
			'int'				=> 'integer',
			'bigint'			=> 'integer',
			'serial'			=> 'integer',
			'bigserial'			=> 'integer',
			'timestamp'			=> 'timestamp',
			'date'				=> 'date',
			'time'				=> 'time',
			'uuid'              => 'varchar',
			'character varying'	=> 'varchar',
			'character'			=> 'char',
			'real'				=> 'float',
			'double'			=> 'float',
			'numeric'			=> 'float',
			'bytea'				=> 'blob',
			'text'				=> 'text',
			'mediumtext'		=> 'text',
			'longtext'			=> 'text'
		);
		
		// PgSQL required this complicated SQL to get the column info
		$sql = "SELECT
						pg_attribute.attname                                        AS column,
						format_type(pg_attribute.atttypid, pg_attribute.atttypmod)  AS data_type,
						pg_attribute.attnotnull                                     AS not_null,
						pg_attrdef.adsrc                                            AS default,
						pg_get_constraintdef(pg_constraint.oid)                     AS constraint
					FROM
						pg_attribute LEFT JOIN
						pg_class ON pg_attribute.attrelid = pg_class.oid LEFT JOIN
						pg_type ON pg_type.oid = pg_attribute.atttypid LEFT JOIN
						pg_constraint ON pg_constraint.conrelid = pg_class.oid AND
										 pg_attribute.attnum = ANY (pg_constraint.conkey) AND
										 pg_constraint.contype = 'c' LEFT JOIN
						pg_attrdef ON pg_class.oid = pg_attrdef.adrelid AND
									  pg_attribute.attnum = pg_attrdef.adnum
					WHERE
						NOT pg_attribute.attisdropped AND
						pg_class.relname = %s AND
						pg_type.typname NOT IN ('oid', 'cid', 'xid', 'cid', 'xid', 'tid')
					ORDER BY
						pg_attribute.attnum,
						pg_constraint.contype";
		$result = $this->database->query($sql, $table);
		
		foreach ($result as $row) {
			
			$info = array();
			
			// Get the column type
			preg_match('#([\w ]+)\s*(?:\(\s*(\d+)(?:\s*,\s*(\d+))?\s*\))?#', $row['data_type'], $column_data_type);
			
			foreach ($data_type_mapping as $data_type => $mapped_data_type) {
				if (stripos($column_data_type[1], $data_type) === 0) {
					$info['type'] = $mapped_data_type;
					break;
				}
			}
			
			if (!isset($info['type'])) {
				$info['type'] = $column_data_type[1];
			}
			
			// Handle the length of decimal/numeric fields
			if ($info['type'] == 'float' && isset($column_data_type[3]) && strlen($column_data_type[3]) > 0) {
				$info['decimal_places'] = (int) $column_data_type[3];
			}
			
			// Handle the special data for varchar fields
			if (in_array($info['type'], array('char', 'varchar')) && !empty($column_data_type[2])) {
				$info['max_length'] = $column_data_type[2];
			}
			
			// In PostgreSQL, a UUID can be the 32 digits, 32 digits plus 4 hyphens or 32 digits plus 4 hyphens and 2 curly braces
			if ($row['data_type'] == 'uuid') {
				$info['max_length'] = 38;	
			}
			
			// Handle check constraints that are just simple lists
			if (in_array($info['type'], array('varchar', 'char')) && !empty($row['constraint'])) {
				if (preg_match('/CHECK[\( "]+' . $row['column'] . '[a-z\) ":]+\s+=\s+/i', $row['constraint'])) {
					if (preg_match_all("/(?!').'((''|[^']+)*)'/", $row['constraint'], $matches, PREG_PATTERN_ORDER)) {
						$info['valid_values'] = str_replace("''", "'", $matches[1]);
					}
				}
			}
			
			// Handle default values and serial data types
			if ($info['type'] == 'integer' && stripos($row['default'], 'nextval(') !== FALSE) {
				$info['auto_increment'] = TRUE;
				
			} elseif ($row['default'] !== NULL) {
				if ($row['default'] == 'now()') {
					$info['default'] = 'CURRENT_TIMESTAMP';
				} elseif ($row['default'] == "('now'::text)::date") {
					$info['default'] = 'CURRENT_DATE';
				} elseif ($row['default'] == "('now'::text)::time with time zone") {
					$info['default'] = 'CURRENT_TIME';	
				} else {
					$info['default'] = str_replace("''", "'", preg_replace("/^'(.*)'::[a-z ]+\$/iD", '\1', $row['default']));
					if ($info['type'] == 'boolean') {
						$info['default'] = ($info['default'] == 'false' || !$info['default']) ? FALSE : TRUE;
					}
				}
			}
			
			// Not null values
			$info['not_null'] = ($row['not_null'] == 't') ? TRUE : FALSE;
			
			$column_info[$row['column']] = $info;
		}
		
		return $column_info;
	}
	
	
	/**
	 * Fetches the key info for a PostgreSQL database
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @return array  The keys arrays for every table in the database - see method description for details
	 */
	private function fetchPostgreSQLKeys()
	{
		$keys = array();
		
		$tables   = $this->getTables();
		foreach ($tables as $table) {
			$keys[$table] = array();
			$keys[$table]['primary'] = array();
			$keys[$table]['unique']  = array();
			$keys[$table]['foreign'] = array();
		}
		
		$sql  = "(
				 SELECT
						 t.relname AS table,
						 con.conname AS constraint_name,
						 CASE con.contype
							 WHEN 'f' THEN 'foreign'
							 WHEN 'p' THEN 'primary'
							 WHEN 'u' THEN 'unique'
						 END AS type,
						 col.attname AS column,
						 ft.relname AS foreign_table,
						 fc.attname AS foreign_column,
						 CASE con.confdeltype
							 WHEN 'c' THEN 'cascade'
							 WHEN 'a' THEN 'no_action'
							 WHEN 'r' THEN 'restrict'
							 WHEN 'n' THEN 'set_null'
							 WHEN 'd' THEN 'set_default'
						 END AS on_delete,
						 CASE con.confupdtype
							 WHEN 'c' THEN 'cascade'
							 WHEN 'a' THEN 'no_action'
							 WHEN 'r' THEN 'restrict'
							 WHEN 'n' THEN 'set_null'
							 WHEN 'd' THEN 'set_default'
						 END AS on_update,
						CASE WHEN con.conkey IS NOT NULL THEN position('-'||col.attnum||'-' in '-'||array_to_string(con.conkey, '-')||'-') ELSE 0 END AS column_order
					 FROM
						 pg_attribute AS col INNER JOIN
						 pg_class AS t ON col.attrelid = t.oid INNER JOIN
						 pg_constraint AS con ON (col.attnum = ANY (con.conkey) AND
												  con.conrelid = t.oid) LEFT JOIN
						 pg_class AS ft ON con.confrelid = ft.oid LEFT JOIN
						 pg_attribute AS fc ON (fc.attnum = ANY (con.confkey) AND
												ft.oid = fc.attrelid)
					 WHERE
						 NOT col.attisdropped AND
						 (con.contype = 'p' OR
						  con.contype = 'f' OR
						  con.contype = 'u')
				) UNION (
				SELECT
						t.relname AS table,
						ic.relname AS constraint_name,
						'unique' AS type,
						col.attname AS column,
						NULL AS foreign_table,
						NULL AS foreign_column,
						NULL AS on_delete,
						NULL AS on_update,
						CASE WHEN ind.indkey IS NOT NULL THEN position('-'||col.attnum||'-' in '-'||array_to_string(ind.indkey, '-')||'-') ELSE 0 END AS column_order
					FROM
						pg_class AS t INNER JOIN
						pg_index AS ind ON ind.indrelid = t.oid INNER JOIN
						pg_namespace AS n ON t.relnamespace = n.oid INNER JOIN
						pg_class AS ic ON ind.indexrelid = ic.oid LEFT JOIN
						pg_constraint AS con ON con.conrelid = t.oid AND con.contype = 'u' AND con.conname = ic.relname INNER JOIN
						pg_attribute AS col ON col.attrelid = t.oid AND col.attnum = ANY (ind.indkey)  
					WHERE
						n.nspname NOT IN ('pg_catalog', 'pg_toast') AND
						indisunique = TRUE AND
						indisprimary = FALSE AND
						con.oid IS NULL
				) ORDER BY 1, 3, 2, 9";
		
		$result = $this->database->query($sql);
		
		$last_name  = '';
		$last_table = '';
		$last_type  = '';
		foreach ($result as $row) {
			
			if ($row['constraint_name'] != $last_name) {
				
				if ($last_name) {
					if ($last_type == 'foreign' || $last_type == 'unique') {
						$keys[$last_table][$last_type][] = $temp;
					} else {
						$keys[$last_table][$last_type] = $temp;
					}
				}
				
				$temp = array();
				if ($row['type'] == 'foreign') {
					
					$temp['column']         = $row['column'];
					$temp['foreign_table']  = $row['foreign_table'];
					$temp['foreign_column'] = $row['foreign_column'];
					$temp['on_delete']      = 'no_action';
					$temp['on_update']      = 'no_action';
					
					if (!empty($row['on_delete'])) {
						$temp['on_delete'] = $row['on_delete'];
					}
					
					if (!empty($row['on_update'])) {
						$temp['on_update'] = $row['on_update'];
					}
					
				} else {
					$temp[] = $row['column'];
				}
				
				$last_table = $row['table'];
				$last_name  = $row['constraint_name'];
				$last_type  = $row['type'];
				
			} else {
				$temp[] = $row['column'];
			}
		}
		
		if (isset($temp)) {
			if ($last_type == 'foreign' || $last_type == 'unique') {
				$keys[$last_table][$last_type][] = $temp;
			} else {
				$keys[$last_table][$last_type] = $temp;
			}
		}
		
		return $keys;
	}
	
	
	/**
	 * Gets the column info from a SQLite database
	 * 
	 * The returned array is in the format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value-may contain special strings CURRENT_TIMESTAMP, CURRENT_TIME or CURRENT_DATE},
	 *         'valid_values'   => (array)   {the valid values for a char/varchar field},
	 *         'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is auto_increment}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see method description for details
	 */
	private function fetchSQLiteColumnInfo($table)
	{
		$column_info = array();
		
		$data_type_mapping = array(
			'boolean'			=> 'boolean',
			'serial'            => 'integer',
			'smallint'			=> 'integer',
			'int'				=> 'integer',
			'integer'           => 'integer',
			'bigint'			=> 'integer',
			'timestamp'			=> 'timestamp',
			'date'				=> 'date',
			'time'				=> 'time',
			'varchar'			=> 'varchar',
			'char'				=> 'char',
			'real'				=> 'float',
			'numeric'           => 'float',
			'float'             => 'float',
			'double'			=> 'float',
			'decimal'			=> 'float',
			'blob'				=> 'blob',
			'text'				=> 'text'
		);
		
		$result = $this->database->query("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = %s", $table);
		
		try {
			$row        = $result->fetchRow();
			$create_sql = $row['sql'];
		} catch (fNoRowsException $e) {
			return array();			
		}
		
		preg_match_all('#(?<=,|\()\s*(?:`|"|\[)?(\w+)(?:`|"|\])?\s+([a-z]+)(?:\(\s*(\d+)(?:\s*,\s*(\d+))?\s*\))?(?:(\s+NOT\s+NULL)|(?:\s+NULL)|(?:\s+DEFAULT\s+([^, \']*|\'(?:\'\'|[^\']+)*\'))|(\s+UNIQUE)|(\s+PRIMARY\s+KEY(?:\s+AUTOINCREMENT)?)|(\s+CHECK\s*\(\w+\s+IN\s+\(\s*(?:(?:[^, \']+|\'(?:\'\'|[^\']+)*\')\s*,\s*)*\s*(?:[^, \']+|\'(?:\'\'|[^\']+)*\')\)\)))*(\s+REFERENCES\s+\w+\s*\(\s*\w+\s*\)\s*(?:\s+(?:ON\s+DELETE|ON\s+UPDATE)\s+(?:CASCADE|NO\s+ACTION|RESTRICT|SET\s+NULL|SET\s+DEFAULT))*(?:\s+(?:DEFERRABLE|NOT\s+DEFERRABLE))?)?\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $match) {
			$info = array();
			
			foreach ($data_type_mapping as $data_type => $mapped_data_type) {
				if (stripos($match[2], $data_type) === 0) {
					$info['type'] = $mapped_data_type;
					break;
				}
			}
		
			// Type specific information
			if (in_array($info['type'], array('char', 'varchar')) && !empty($match[3])) {
				$info['max_length'] = $match[3];
			}
			
			// Figure out how many decimal places for a decimal
			if (in_array(strtolower($match[2]), array('decimal', 'numeric')) && !empty($match[4])) {
				$info['decimal_places'] = $match[4];
			}
			
			// Not null
			$info['not_null'] = (!empty($match[5]) || !empty($match[8])) ? TRUE : FALSE;
		
			// Default values
			if (isset($match[6]) && $match[6] != '' && $match[6] != 'NULL') {
				$info['default'] = preg_replace("/^'|'\$/D", '', $match[6]);
			}
			if ($info['type'] == 'boolean' && isset($info['default'])) {
				$info['default'] = ($info['default'] == 'f' || $info['default'] == 0 || $info['default'] == 'false') ? FALSE : TRUE;
			}
		
			// Check constraints
			if (isset($match[9]) && preg_match('/CHECK\s*\(\s*' . $match[1] . '\s+IN\s+\(\s*((?:(?:[^, \']*|\'(?:\'\'|[^\']+)*\')\s*,\s*)*(?:[^, \']*|\'(?:\'\'|[^\']+)*\'))\s*\)/i', $match[9], $check_match)) {
				$info['valid_values'] = str_replace("''", "'", preg_replace("/^'|'\$/D", '', preg_split("#\s*,\s*#", $check_match[1])));
			}
		
			// Auto increment fields
			if (!empty($match[8]) && (stripos($match[8], 'autoincrement') !== FALSE || $info['type'] == 'integer')) {
				$info['auto_increment'] = TRUE;
			}
		
			$column_info[$match[1]] = $info;
		}
		
		return $column_info;
	}
	
	
	/**
	 * Fetches the key info for an SQLite database
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @return array  The keys arrays for every table in the database - see method description for details
	 */
	private function fetchSQLiteKeys()
	{
		$tables = $this->getTables();
		$keys   = array();
		
		foreach ($tables as $table) {
			$keys[$table] = array();
			$keys[$table]['primary'] = array();
			$keys[$table]['foreign'] = array();
			$keys[$table]['unique']  = array();
			
			$result     = $this->database->query("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = %s", $table);
			$row        = $result->fetchRow();
			$create_sql = $row['sql'];
			
			// Get column level key definitions
			preg_match_all('#(?<=,|\()\s*(\w+)\s+(?:[a-z]+)(?:\((?:\d+)\))?(?:(?:\s+NOT\s+NULL)|(?:\s+DEFAULT\s+(?:[^, \']*|\'(?:\'\'|[^\']+)*\'))|(\s+UNIQUE)|(\s+PRIMARY\s+KEY(?:\s+AUTOINCREMENT)?)|(?:\s+CHECK\s*\(\w+\s+IN\s+\(\s*(?:(?:[^, \']+|\'(?:\'\'|[^\']+)*\')\s*,\s*)*\s*(?:[^, \']+|\'(?:\'\'|[^\']+)*\')\)\)))*(\s+REFERENCES\s+(\w+)\s*\(\s*(\w+)\s*\)\s*(?:(?:\s+(?:ON\s+DELETE\s+(CASCADE|NO\s+ACTION|RESTRICT|SET\s+NULL|SET\s+DEFAULT)))|(?:\s+(?:ON\s+UPDATE\s+(CASCADE|NO\s+ACTION|RESTRICT|SET\s+NULL|SET\s+DEFAULT))))*(?:\s+(?:DEFERRABLE|NOT\s+DEFERRABLE))?)?\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $match) {
				if (!empty($match[2])) {
					$keys[$table]['unique'][] = array($match[1]);
				}
				
				if (!empty($match[3])) {
					$keys[$table]['primary'] = array($match[1]);
				}
				
				if (!empty($match[4])) {
					$temp = array('column'         => $match[1],
								  'foreign_table'  => $match[5],
								  'foreign_column' => $match[6],
								  'on_delete'      => 'no_action',
								  'on_update'      => 'no_action');
					if (isset($match[7])) {
						$temp['on_delete'] = strtolower(str_replace(' ', '_', $match[7]));
					}
					if (isset($match[8])) {
						$temp['on_update'] = strtolower(str_replace(' ', '_', $match[8]));
					}
					$keys[$table]['foreign'][] = $temp;
				}
			}
			
			// Get table level primary key definitions
			preg_match_all('#(?<=,|\()\s*PRIMARY\s+KEY\s*\(\s*((?:\s*\w+\s*,\s*)*\w+)\s*\)\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $match) {
				$keys[$table]['primary'] = preg_split('#\s*,\s*#', $match[1]);
			}
			
			// Get table level foreign key definitions
			preg_match_all('#(?<=,|\()\s*FOREIGN\s+KEY\s*(?:(\w+)|\((\w+)\))\s+REFERENCES\s+(\w+)\s*\(\s*(\w+)\s*\)\s*(?:\s+(?:ON\s+DELETE\s+(CASCADE|NO\s+ACTION|RESTRICT|SET\s+NULL|SET\s+DEFAULT)))?(?:\s+(?:ON\s+UPDATE\s+(CASCADE|NO\s+ACTION|RESTRICT|SET\s+NULL|SET\s+DEFAULT)))?(?:\s+(?:DEFERRABLE|NOT\s+DEFERRABLE))?\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $match) {
				if (empty($match[1])) { $match[1] = $match[2]; }
				$temp = array('column'         => $match[1],
							  'foreign_table'  => $match[3],
							  'foreign_column' => $match[4],
							  'on_delete'      => 'no_action',
							  'on_update'      => 'no_action');
				if (isset($match[5])) {
					$temp['on_delete'] = strtolower(str_replace(' ', '_', $match[5]));
				}
				if (isset($match[6])) {
					$temp['on_update'] = strtolower(str_replace(' ', '_', $match[6]));
				}
				$keys[$table]['foreign'][] = $temp;
			}
			
			// Get table level unique key definitions
			preg_match_all('#(?<=,|\()\s*UNIQUE\s*\(\s*((?:\s*\w+\s*,\s*)*\w+)\s*\)\s*(?:,|\s*(?=\)))#mi', $create_sql, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $match) {
				$keys[$table]['unique'][] = preg_split('#\s*,\s*#', $match[1]);
			}
		}
		
		return $keys;
	}
	
	
	/**
	 * Finds many-to-many relationship for the table specified
	 * 
	 * @param  string $table  The table to find the relationships on
	 * @return void
	 */
	private function findManyToManyRelationships($table)
	{
		if (!$this->isJoiningTable($table)) {
			return;
		}
		
		list ($key1, $key2) = $this->merged_keys[$table]['foreign'];
		
		$temp = array();
		$temp['table']               = $key1['foreign_table'];
		$temp['column']              = $key1['foreign_column'];
		$temp['related_table']       = $key2['foreign_table'];
		$temp['related_column']      = $key2['foreign_column'];
		$temp['join_table']          = $table;
		$temp['join_column']         = $key1['column'];
		$temp['join_related_column'] = $key2['column'];
		$temp['on_update']           = $key1['on_update'];
		$temp['on_delete']           = $key1['on_delete'];
		$this->relationships[$key1['foreign_table']]['many-to-many'][] = $temp;
		
		$temp = array();
		$temp['table']               = $key2['foreign_table'];
		$temp['column']              = $key2['foreign_column'];
		$temp['related_table']       = $key1['foreign_table'];
		$temp['related_column']      = $key1['foreign_column'];
		$temp['join_table']          = $table;
		$temp['join_column']         = $key2['column'];
		$temp['join_related_column'] = $key1['column'];
		$temp['on_update']           = $key2['on_update'];
		$temp['on_delete']           = $key2['on_delete'];
		$this->relationships[$key2['foreign_table']]['many-to-many'][] = $temp;
	}
	
	
	/**
	 * Finds one-to-many relationship for the table specified
	 * 
	 * @param  string $table  The table to find the relationships on
	 * @return void
	 */
	private function findOneToManyRelationships($table)
	{
		foreach ($this->merged_keys[$table]['foreign'] as $key) {
			$type = ($this->checkForSingleColumnUniqueKey($table, $key['column'])) ? 'one-to-one' : 'one-to-many';
			$temp = array();
			$temp['table']          = $key['foreign_table'];
			$temp['column']         = $key['foreign_column'];
			$temp['related_table']  = $table;
			$temp['related_column'] = $key['column'];
			if ($type == 'one-to-many') {
				$temp['on_delete']      = $key['on_delete'];
				$temp['on_update']      = $key['on_update'];
			}
			$this->relationships[$key['foreign_table']][$type][] = $temp;
		}
	}
	
	
	/**
	 * Finds one-to-one and many-to-one relationship for the table specified
	 * 
	 * @param  string $table  The table to find the relationships on
	 * @return void
	 */
	private function findStarToOneRelationships($table)
	{
		foreach ($this->merged_keys[$table]['foreign'] as $key) {
			$temp = array();
			$temp['table']          = $table;
			$temp['column']         = $key['column'];
			$temp['related_table']  = $key['foreign_table'];
			$temp['related_column'] = $key['foreign_column'];
			$type = ($this->checkForSingleColumnUniqueKey($table, $key['column'])) ? 'one-to-one' : 'many-to-one';
			$this->relationships[$table][$type][] = $temp;
		}
	}
	
	
	/**
	 * Finds the one-to-one, many-to-one, one-to-many and many-to-many relationships in the database
	 * 
	 * @return void
	 */
	private function findRelationships()
	{
		$this->relationships = array();
		$tables = $this->getTables();
		
		foreach ($tables as $table) {
			$this->relationships[$table]['one-to-one']   = array();
			$this->relationships[$table]['many-to-one']  = array();
			$this->relationships[$table]['one-to-many']  = array();
			$this->relationships[$table]['many-to-many'] = array();
		}
		
		// Calculate the relationships
		foreach ($this->merged_keys as $table => $keys) {
			$this->findManyToManyRelationships($table);
			if ($this->isJoiningTable($table)) {
				continue;
			}
			
			$this->findStarToOneRelationships($table);
			$this->findOneToManyRelationships($table);
		}
		
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'relationships', $this->relationships);	
		}
	}
	
	
	/**
	 * Returns column information for the table specified
	 * 
	 * If only a table is specified, column info is in the following format:
	 * 
	 * {{{
	 * array(
	 *     (string) {column name} => array(
	 *         'type'           => (string)  {data type},
	 *         'not_null'       => (boolean) {if value can't be null},
	 *         'default'        => (mixed)   {the default value},
	 *         'valid_values'   => (array)   {the valid values for a varchar field},
	 *         'max_length'     => (integer) {the maximum length in a varchar field},
	 *         'decimal_places' => (integer) {the number of decimal places for a decimal/numeric/money/smallmoney field},
	 *         'auto_increment' => (boolean) {if the integer primary key column is a serial/autoincrement/auto_increment/indentity column}
	 *     ), ...
	 * )
	 * }}}
	 * 
	 * If a table and column are specified, column info is in the following format:
	 * 
	 * {{{
	 * array(
	 *     'type'           => (string)  {data type},
	 *     'not_null'       => (boolean) {if value can't be null},
	 *     'default'        => (mixed)   {the default value-may contain special strings CURRENT_TIMESTAMP, CURRENT_TIME or CURRENT_DATE},
	 *     'valid_values'   => (array)   {the valid values for a varchar field},
	 *     'max_length'     => (integer) {the maximum length in a char/varchar field},
	 *     'decimal_places' => (integer) {the number of decimal places for a decimal/numeric/money/smallmoney field},
	 *     'auto_increment' => (boolean) {if the integer primary key column is a serial/autoincrement/auto_increment/indentity column}
	 * )
	 * }}}
	 * 
	 * If a table, column and element are specified, returned value is the single element specified.
	 * 
	 * The `'type'` element is homogenized to a value from the following list:
	 * 
	 *  - `'varchar'`
	 *  - `'char'`
	 *  - `'text'`
	 *  - `'integer'`
	 *  - `'float'`
	 *  - `'timestamp'`
	 *  - `'date'`
	 *  - `'time'`
	 *  - `'boolean'`
	 *  - `'blob'`
	 * 
	 * @param  string $table    The table to get the column info for
	 * @param  string $column   The column to get the info for
	 * @param  string $element  The element to return: `'type'`, `'not_null'`, `'default'`, `'valid_values'`, `'max_length'`, `'decimal_places'`, `'auto_increment'`
	 * @return mixed  The column info for the table/column/element specified - see method description for format
	 */
	public function getColumnInfo($table, $column=NULL, $element=NULL)
	{
		// Return the saved column info if possible
		if (!$column && isset($this->merged_column_info[$table])) {
			return $this->merged_column_info[$table];
		}
		if ($column && isset($this->merged_column_info[$table][$column])) {
			if ($element !== NULL) {
				if (!isset($this->merged_column_info[$table][$column][$element]) && !array_key_exists($element, $this->merged_column_info[$table][$column])) {
					throw new fProgrammerException(
						'The element specified, %1$s, is invalid. Must be one of: %2$s.',
						$element,
						join(', ', array('type', 'not_null', 'default', 'valid_values', 'max_length', 'decimal_places', 'auto_increment'))
					);	
				}
				return $this->merged_column_info[$table][$column][$element];
			}
			return $this->merged_column_info[$table][$column];
		}
		
		if (!in_array($table, $this->getTables())) {
			throw new fProgrammerException(
				'The table specified, %s, does not exist in the database',
				$table
			);
		}
		
		$this->fetchColumnInfo($table);
		$this->mergeColumnInfo();
		
		if ($column && !isset($this->merged_column_info[$table][$column])) {
			throw new fProgrammerException(
				'The column specified, %1$s, does not exist in the table %2$s',
				$column,
				$table
			);
		}
		
		if ($column) {
			if ($element) {
				return $this->merged_column_info[$table][$column][$element];
			}
			
			return $this->merged_column_info[$table][$column];
		}
		
		return $this->merged_column_info[$table];
	}
	
	
	/**
	 * Returns the databases on the current server
	 * 
	 * @return array  The databases on the current server
	 */
	public function getDatabases()
	{
		if ($this->databases !== NULL) {
			return $this->databases;
		}
		
		$this->databases = array();
		
		switch ($this->database->getType()) {
			case 'mssql':
				$sql = 'SELECT
								DISTINCT CATALOG_NAME
							FROM
								INFORMATION_SCHEMA.SCHEMATA
							ORDER BY
								LOWER(CATALOG_NAME)';
				break;
			
			case 'mysql':
				$sql = 'SHOW DATABASES';
				break;
			
			case 'postgresql':
				$sql = "SELECT
								datname
							FROM
								pg_database
							ORDER BY
								LOWER(datname)";
				break;
								
			case 'sqlite':
				$this->databases[] = $this->database->getDatabase();
				return $this->databases;
		}
		
		$result = $this->database->query($sql);
		
		foreach ($result as $row) {
			$keys = array_keys($row);
			$this->databases[] = $row[$keys[0]];
		}
		
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'databases', $this->databases);		
		}
		
		return $this->databases;
	}
	
	
	/**
	 * Returns a list of primary key, foreign key and unique key constraints for the table specified
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *      'primary' => array(
	 *          {column name}, ...
	 *      ),
	 *      'unique'  => array(
	 *          array(
	 *              {column name}, ...
	 *          ), ...
	 *      ),
	 *      'foreign' => array(
	 *          array(
	 *              'column'         => {column name},
	 *              'foreign_table'  => {foreign table name},
	 *              'foreign_column' => {foreign column name},
	 *              'on_delete'      => {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *              'on_update'      => {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *          ), ...
	 *      )
	 * )
	 * }}}
	 * 
	 * @param  string $table     The table to return the keys for
	 * @param  string $key_type  The type of key to return: `'primary'`, `'foreign'`, `'unique'`
	 * @return array  An array of all keys, or just the type specified - see method description for format
	 */
	public function getKeys($table, $key_type=NULL)
	{
		$valid_key_types = array('primary', 'foreign', 'unique');
		if ($key_type !== NULL && !in_array($key_type, $valid_key_types)) {
			throw new fProgrammerException(
				'The key type specified, %1$s, is invalid. Must be one of: %2$s.',
				$key_type,
				join(', ', $valid_key_types)
			);
		}
		
		// Return the saved column info if possible
		if (!$key_type && isset($this->merged_keys[$table])) {
			return $this->merged_keys[$table];
		}
		
		if ($key_type && isset($this->merged_keys[$table][$key_type])) {
			return $this->merged_keys[$table][$key_type];
		}
		
		if (!in_array($table, $this->getTables())) {
			throw new fProgrammerException(
				'The table specified, %s, does not exist in the database',
				$table
			);
		}
		
		$this->fetchKeys();
		$this->mergeKeys();
		
		if ($key_type) {
			return $this->merged_keys[$table][$key_type];
		}
		
		return $this->merged_keys[$table];
	}
	
	
	/**
	 * Returns a list of one-to-one, many-to-one, one-to-many and many-to-many relationships for the table specified
	 * 
	 * The structure of the returned array is:
	 * 
	 * {{{
	 * array(
	 *     'one-to-one' => array(
	 *         array(
	 *             'table'          => (string) {the name of the table this relationship is for},
	 *             'column'         => (string) {the column in the specified table},
	 *             'related_table'  => (string) {the related table},
	 *             'related_column' => (string) {the related column}
	 *         ), ...
	 *     ),
	 *     'many-to-one' => array(
	 *         array(
	 *             'table'          => (string) {the name of the table this relationship is for},
	 *             'column'         => (string) {the column in the specified table},
	 *             'related_table'  => (string) {the related table},
	 *             'related_column' => (string) {the related column}
	 *         ), ...
	 *     ),
	 *     'one-to-many' => array(
	 *         array(
	 *             'table'          => (string) {the name of the table this relationship is for},
	 *             'column'         => (string) {the column in the specified table},
	 *             'related_table'  => (string) {the related table},
	 *             'related_column' => (string) {the related column},
	 *             'on_delete'      => (string) {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *             'on_update'      => (string) {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *         ), ...
	 *     ),
	 *     'many-to-many' => array(
	 *         array(
	 *             'table'               => (string) {the name of the table this relationship is for},
	 *             'column'              => (string) {the column in the specified table},
	 *             'related_table'       => (string) {the related table},
	 *             'related_column'      => (string) {the related column},
	 *             'join_table'          => (string) {the table that joins the specified table to the related table},
	 *             'join_column'         => (string) {the column in the join table that references 'column'},
	 *             'join_related_column' => (string) {the column in the join table that references 'related_column'},
	 *             'on_delete'           => (string) {the ON DELETE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'},
	 *             'on_update'           => (string) {the ON UPDATE action: 'no_action', 'restrict', 'cascade', 'set_null', or 'set_default'}
	 *         ), ...
	 *     )
	 * )
	 * }}}
	 * 
	 * @param  string $table              The table to return the relationships for
	 * @param  string $relationship_type  The type of relationship to return: `'one-to-one'`, `'many-to-one'`, `'one-to-many'`, `'many-to-many'`
	 * @return array  An array of all relationships, or just the type specified - see method description for format
	 */
	public function getRelationships($table, $relationship_type=NULL)
	{
		$valid_relationship_types = array('one-to-one', 'many-to-one', 'one-to-many', 'many-to-many');
		if ($relationship_type !== NULL && !in_array($relationship_type, $valid_relationship_types)) {
			throw new fProgrammerException(
				'The relationship type specified, %1$s, is invalid. Must be one of: %2$s.',
				$relationship_type,
				join(', ', $valid_relationship_types)
			);
		}
		
		// Return the saved column info if possible
		if (!$relationship_type && isset($this->relationships[$table])) {
			return $this->relationships[$table];
		}
		
		if ($relationship_type && isset($this->relationships[$table][$relationship_type])) {
			return $this->relationships[$table][$relationship_type];
		}
		
		if (!in_array($table, $this->getTables())) {
			throw new fProgrammerException(
				'The table specified, %s, does not exist in the database',
				$table
			);
		}
		
		$this->fetchKeys();
		$this->mergeKeys();
		
		if ($relationship_type) {
			return $this->relationships[$table][$relationship_type];
		}
		
		return $this->relationships[$table];
	}
	
	
	/**
	 * Returns the tables in the current database
	 * 
	 * @return array  The tables in the current database
	 */
	public function getTables()
	{
		if ($this->tables !== NULL) {
			return $this->tables;
		}
		
		switch ($this->database->getType()) {
			case 'mssql':
				$sql = "SELECT
								TABLE_NAME
							FROM
								INFORMATION_SCHEMA.TABLES
							WHERE
								TABLE_NAME != 'sysdiagrams'
							ORDER BY
								LOWER(TABLE_NAME)";
				break;
			
			case 'mysql':
				$sql = 'SHOW TABLES';
				break;
			
			case 'oracle':
				$sql = "SELECT
								LOWER(TABLE_NAME)
							FROM
								USER_TABLES
							WHERE
								SUBSTR(TABLE_NAME, 1, 4) <> 'BIN\$'
							ORDER BY
								TABLE_NAME ASC";
				break;
			
			case 'postgresql':
				$sql = "SELECT
								 tablename
							FROM
								 pg_tables
							WHERE
								 tablename !~ '^(pg|sql)_'
							ORDER BY
								LOWER(tablename)";
				break;
								
			case 'sqlite':
				$sql = "SELECT
								name
							FROM
								sqlite_master
							WHERE
								type = 'table' AND
								name NOT LIKE 'sqlite_%'
							ORDER BY
								name ASC";
				break;
		}
		
		$result = $this->database->query($sql);
		
		$this->tables = array();
		
		foreach ($result as $row) {
			$keys = array_keys($row);
			$this->tables[] = $row[$keys[0]];
		}
		
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'tables', $this->tables);
		}
		
		return $this->tables;
	}
		
	
	/**
	 * Determines if a table is a joining table
	 * 
	 * @param  string $table  The table to check
	 * @return boolean  If the table is a joining table
	 */
	private function isJoiningTable($table)
	{
		$primary_key_columns = $this->merged_keys[$table]['primary'];
		
		if (sizeof($primary_key_columns) != 2) {
			return FALSE;	
		}
		
		if (empty($this->merged_column_info[$table])) {
			$this->getColumnInfo($table);	
		}
		if (sizeof($this->merged_column_info[$table]) != 2) {
			return FALSE;	
		}
		
		$foreign_key_columns = array();
		foreach ($this->merged_keys[$table]['foreign'] as $key) {
			$foreign_key_columns[] = $key['column'];
		}
		
		return sizeof($foreign_key_columns) == 2 && !array_diff($foreign_key_columns, $primary_key_columns);
	}
	
	
	/**
	 * Creates a unique cache prefix to help prevent cache conflicts
	 * 
	 * @return void
	 */
	private function makeCachePrefix()
	{
		$prefix  = 'fSchema::' . $this->database->getType() . '::';
		if ($this->database->getHost()) {
			$prefix .= $this->database->getHost() . '::';	
		}
		if ($this->database->getPort()) {
			$prefix .= $this->database->getPort() . '::';	
		}
		$prefix .= $this->database->getDatabase() . '::';
		if ($this->database->getUsername()) {
			$prefix .= $this->database->getUsername() . '::';	
		}
		return $prefix;	
	}
	
	
	/**
	 * Merges the column info with the column info override
	 * 
	 * @return void
	 */
	private function mergeColumnInfo()
	{
		$this->merged_column_info = $this->column_info;
		
		foreach ($this->column_info_override as $table => $columns) {
			// Remove a table if the columns are set to NULL
			if ($columns === NULL) {
				unset($this->merged_column_info[$table]);
				continue;	
			}
			
			if (!isset($this->merged_column_info[$table])) {
				$this->merged_column_info[$table] = array();
			}
			
			foreach ($columns as $column => $info) {
				// Remove a column if it is set to NULL
				if ($info === NULL) {
					unset($this->merged_column_info[$table][$column]);	
					continue;
				}
				
				if (!isset($this->merged_column_info[$table][$column])) {
					$this->merged_column_info[$table][$column] = array();
				}
				
				$this->merged_column_info[$table][$column] = array_merge($this->merged_column_info[$table][$column], $info);
			}
		}
		
		$optional_elements = array('not_null', 'default', 'valid_values', 'max_length', 'decimal_places', 'auto_increment');
		
		foreach ($this->merged_column_info as $table => $column_array) {
			foreach ($column_array as $column => $info) {
				if (empty($info['type'])) {
					throw new fProgrammerException('The data type for the column %1$s is empty', $column);	
				}
				foreach ($optional_elements as $element) {
					if (!isset($this->merged_column_info[$table][$column][$element])) {
						$this->merged_column_info[$table][$column][$element] = ($element == 'auto_increment') ? FALSE : NULL;
					}
				}
			}
		}
		
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'merged_column_info', $this->merged_column_info);	
		}
	}
		
	
	/**
	 * Merges the keys with the keys override
	 * 
	 * @return void
	 */
	private function mergeKeys()
	{
		// Handle the database and override key info
		$this->merged_keys = $this->keys;
		
		foreach ($this->keys_override as $table => $info) {
			if (!isset($this->merged_keys[$table])) {
				$this->merged_keys[$table] = array();
			}
			$this->merged_keys[$table] = array_merge($this->merged_keys[$table], $info);
		}
		
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'merged_keys', $this->merged_keys);	
		}
		
		$this->findRelationships();
	}
	
	
	/**
	 * Allows overriding of column info
	 * 
	 * Performs an array merge with the column info detected from the database.
	 * 
	 * To erase a whole table, set the `$column_info` to `NULL`. To erase a
	 * column, set the `$column_info` for that column to `NULL`.
	 * 
	 * If the `$column_info` parameter is not `NULL`, it should be an
	 * associative array containing one or more of the following keys. Please
	 * see ::getColumnInfo() for a description of each.
	 *  - `'type'`
	 *  - `'not_null'`
	 *  - `'default'`
	 *  - `'valid_values'`
	 *  - `'max_length'`
	 *  - `'decimal_places'`
	 *  - `'auto_increment'`
	 * 
	 * The following keys may be set to `NULL`:
	 *  - `'not_null'`
	 *  - `'default'`
	 *  - `'valid_values'`
	 *  - `'max_length'`
	 *  - `'decimal_places'`
	 *  
	 * The key `'auto_increment'` should be a boolean.
	 * 
	 * The `'type'` key should be one of:
	 *  - `'blob'`
	 *  - `'boolean'`
	 *  - `'char'`
	 *  - `'date'`
	 *  - `'float'`
	 *  - `'integer'`
	 *  - `'text'`
	 *  - `'time'`
	 *  - `'timestamp'`
	 *  - `'varchar'`
	 * 
	 * @param  array  $column_info  The modified column info - see method description for format
	 * @param  string $table        The table to override
	 * @param  string $column       The column to override
	 * @return void
	 */
	public function setColumnInfoOverride($column_info, $table, $column=NULL)
	{
		if (!isset($this->column_info_override[$table])) {
			$this->column_info_override[$table] = array();
		}
		
		if (!empty($column)) {
			$this->column_info_override[$table][$column] = $column_info;
		} else {
			$this->column_info_override[$table] = $column_info;
		}
		
		$this->fetchColumnInfo($table);
		$this->mergeColumnInfo();
	}
	
	
	/**
	 * Allows overriding of key info. Replaces existing info, so be sure to provide full key info for type selected or all types.
	 * 
	 * @param  array  $keys      The modified keys - see ::getKeys() for format
	 * @param  string $table     The table to override
	 * @param  string $key_type  The key type to override: `'primary'`, `'foreign'`, `'unique'`
	 * @return void
	 */
	public function setKeysOverride($keys, $table, $key_type=NULL)
	{
		$valid_key_types = array('primary', 'foreign', 'unique');
		if (!in_array($key_type, $valid_key_types)) {
			throw new fProgrammerException(
				'The key type specified, %1$s, is invalid. Must be one of: %2$s.',
				$key_type,
				join(', ', $valid_key_types)
			);
		}
		
		if (!isset($this->keys_override[$table])) {
			$this->keys_override[$table] = array();
		}
		
		if (!empty($key_type)) {
			$this->keys_override[$table][$key_type] = $keys;
		} else {
			$this->keys_override[$table] = $keys;
		}
		
		$this->fetchKeys();
		$this->mergeKeys();
	}
}



/**
 * Copyright (c) 2007-2009 Will Bond <will@flourishlib.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */