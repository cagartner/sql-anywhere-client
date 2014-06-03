<?php namespace Cagartner;

use Cagartner\SQLAnywherePrepared AS SQLAnywherePrepared, 
Cagartner\SQLAnywhereQuery AS SQLAnywhereQuery,
Exception;

/**
* @author Carlos A Gartner <contato@carlosgartner.com.br>
*/
class SQLAnywhereClient
{
	const VERSION      = '1.0';

	public $connected     = false;	
	public $status        = null;

	protected $connection = false;
	protected $server     = null;
	protected $persistent = null ;
	protected $autocommit = null ;
	protected $dns        = null ;
	protected $dbinfo     = array();

	// Types os returns
	const FETCH_ARRAY  = 'array';
	const FETCH_OBJECT = 'object';
	const FETCH_ROW    = 'row';
	const FETCH_FIELD  = 'field';
	const FETCH_ASSOC  = 'assoc';

	// Bind Param
	const INPUT        = 1;
	const OUTPUT       = 2;
	const INPUT_OUTPUT = 3;

	// ----------------------------------------------------
	// Query and Statent
	// ----------------------------------------------------
	protected $query 	  = null;
	protected $sql_string = null;
	protected $num_rows   = 0;
	
	/**
	 * Create connection fybase
	 * @param string  $dns        String connection for sybase
	 * @param boolean $persistent Define connection for persistent
	 */
	function __construct( $dns, $autocommit=true, $persistent=false )
	{
		$this->dns = $dns;
		$this->persistent = $persistent;
		$this->autocommit = $autocommit;

		if (!function_exists('sasql_connect')) 
			throw new Exception("SQL Anywhere model not install in this server!", 100);			

		// Verifica se a conexão é persistente
		if ( $this->persistent ) {
			$this->connection = sasql_pconnect( $this->dns );
		} else {
			$this->connection = sasql_connect( $this->dns );
		}

		if ( !$this->connection )
			throw new Exception("Connection Problem :: " . sasql_error( ), 101);

		// Define option auto_commit
		if ( $this->connection ) {
			sasql_set_option($this->connection, 'auto_commit', ($this->autocommit ? 'on' : 0));
			$this->dbinfo = Array($dns, $autocommit, $persistent);
		}		
	}

	/**
	 * Retunr Array of itens
	 * @param  string $sql_string SQL command
	 * @return array|boolean           
	 */
	public function query($sql_string, $return=self::FETCH_ASSOC)
	{
		$query = self::exec( $sql_string );
		if ( $query ) {
			return $query->fetch($return);
		} 
		return 0;		
	}

	/**
	 * Exec a query os sql comand
	 * @param  string $sql_string SQÇ Command
	 * @return SQLAnywhereQuery|boolean            
	 */
	public function exec($sql_string)
	{
		$this->sql_string = $sql_string;
		$query = sasql_query( $this->connection, $this->sql_string );
		if ( $query ) {
			return new SQLAnywhereQuery( $query, $this->connection );
		} else {
			throw new Exception("SQL String Problem :: " . sasql_error( $this->connection ), 110);		
		}
		return 0;
	}

	/**
	 * Returns the last value inserted into an IDENTITY column or a DEFAULT AUTOINCREMENT column, or zero if the most recent insert was into a table that did not contain an IDENTITY or DEFAULT AUTOINCREMENT column. 
	 * @return integer Last insert ID.
	 */
	public function inserted_id()
	{
		return sasql_insert_id( $this->connection );
	}

	/**
	 * PDO Compability 
	 * Returns the last value inserted into an IDENTITY column or a DEFAULT AUTOINCREMENT column, or zero if the most recent insert was into a table that did not contain an IDENTITY or DEFAULT AUTOINCREMENT column. 
	 * @return integer Last insert ID.
	 */
	public function lastInsertId()
	{
		return sasql_insert_id( $this->connection );
	}
	
	/**
	 * Create a prepared statement an store it in self::stmnt
	 * @param  string $sql_string SQL string
	 * @return \SQLAnywherePrepared         
	 */
	public function prepare($sql_string, $array=array())
	{
		$this->sql_string = $sql_string;
		return new SQLAnywherePrepared( $this->sql_string, $this->connection, $this->dbinfo );
	}

	/**
	 * Return error code for connection
	 * @return int 
	 */
	public function errorCode()
	{
		return sasql_errorcode( $this->connection ? $this->connection : null );
	}

	/**
	 * Returns all error info for connection
	 * @return [type] [description]
	 */
	public function errorInfo()
	{
		return sasql_error( $this->connection ? $this->connection : null );
	}

	// UNSUPPORTED PUBLIC METHODS
	public function beginTransaction() {
		return true;
	}

	/**
	 * Commit the transaction
	 * @return boolean TRUE if is successful or FALSE otherwise.
	 */
	public function commit()
	{
		return sasql_commit( $this->connection );
	}

	/**
	 * Rollback last commit action
	 * @return boolean TRUE if is successful or FALSE otherwise.
	 */
	public function rollback()
	{
		return sasql_rollback( $this->connection );
	}

	public function __destruct(){
	    return sasql_commit( $this->connection );
  	}

  	// UNSUPPORTED PUBLIC METHODS
  	public function quote($data='')
	{
		return true;
	}
}