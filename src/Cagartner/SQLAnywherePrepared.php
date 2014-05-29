<?php namespace Cagartner;

/**
* @author Carlos A Gartner <contato@carlosgartner.com.br>
*/
class SQLAnywherePrepared
{
	protected $statement; 		
	protected $connection; 		

	function __construct( $stmnt, $connection )
	{
		$this->statement  = $stmnt;
		$this->connection = $connection;
	}

	/**
	 * Bind Param
	 * @param  string $types A string that contains one or more characters specifying the types of the corresponding bind. This can be any of: s for string, i for integer, d for double, b for blobs. The length of the $types string must match the number of parameters that follow the $types parameter ($var_1, $var_2, ...). The number of characters should also match the number of parameter markers (question marks) in the prepared statement.
	 * @param  array  $data  The variable references.
	 * @return boolean       TRUE if binding the variables was successful or FALSE otherwise.
	 */
	public function bindParam()
	{
		$args = func_get_args();

		array_unshift($args, $this->statement);

		// echo "<pre>";
		// var_dump($args);
		// echo "</pre>";
		// exit;

		return call_user_func_array( 'sasql_stmt_bind_param' , $args );
	}

	public function paramCount()
	{
		return sasql_stmt_param_count( $this->statement );
	}

	public function fetch()
	{
		return sasql_stmt_fetch( $this->statement );
	}

	/**
	 * Executes the prepared statement. The sasql_stmt_result_metadata can be used to check whether the statement returns a result set.
	 * @return boolean       TRUE if binding the variables was successful or FALSE otherwise.
	 */
	public function execute()
	{
		return sasql_stmt_execute( $this->statement );
	}

}