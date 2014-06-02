<?php namespace Cagartner;

/**
* @author Carlos A Gartner <contato@carlosgartner.com.br>
*/
class SQLAnywherePrepared
{
	protected $__query; 		
	protected $__connection; 		
	protected $__dbinfo;
	protected $__boundParams = array();
	protected $__result;

	function __construct( $sql_string, $connection, array $dbinfo )
	{
		$this->__query = $sql_string;
		$this->__connection = $connection;
		$this->__dbinfo     = $dbinfo;
	}

	/**
	 * Bind Param
	 * @param  string $types A string that contains one or more characters specifying the types of the corresponding bind. This can be any of: s for string, i for integer, d for double, b for blobs. The length of the $types string must match the number of parameters that follow the $types parameter ($var_1, $var_2, ...). The number of characters should also match the number of parameter markers (question marks) in the prepared statement.
	 * @param  array  $data  The variable references.
	 * @return boolean       TRUE if binding the variables was successful or FALSE otherwise.
	 */
	public function bindParam($mixed, &$variable, $type = null, $lenght = null) {
		if(is_string($mixed))
			$this->__boundParams[$mixed] = $variable;
		else
			array_push($this->__boundParams, $variable);
	}

	/**
	 * Public method:
	 *	Checks if query was valid and returns how may fields returns
     *       	this->columnCount( void ):Void
	 */
	function columnCount() {
		$result = 0;
		if(!is_null($this->__connection))
			$result = sasql_num_fields($this->__connection);
		return $result; 
	}

	public function execute($array = array()) {
		$c = $this->__connection;
		if(count($this->__boundParams) > 0)
			$array = &$this->__boundParams;
		$__query = $this->__query;
		if(count($array) > 0) {
			foreach($array as $k => $v) {
				if(!is_int($k) && substr($k, 0, 1) === ':') {
					if(!isset($tempf))
						$tempf = $tempr = array();
					array_push($tempf, $k);
					array_push($tempr, "'".sasql_escape_string($c,$v)."'");
				} else if (!is_int($k)) {
					if(!isset($tempf))
						$tempf = $tempr = array();
					array_push($tempf, ':'.$k);
					array_push($tempr, "'".sasql_escape_string($c,$v)."'");
				}
				else {
					$parse = function($con, $val) {
					    return "'" . sasql_escape_string($con,$val) . "'";
					};
					$__query = preg_replace("/(\?)/e", '$parse($c,$array[$k++]);', $__query);
					break;
				}
			}
			if(isset($tempf))
				$__query = str_replace($tempf, $tempr, $__query);
		}

		if($this->__result = $this->__uquery($__query))
			$keyvars = false;
		else
			$keyvars = true;
		$this->__boundParams = array();

		return $keyvars;
	}

	public function paramCount()
	{
		return sasql_stmt_param_count( $this->statement );
	}

	public function __uquery(&$query) {
		if(!@$query = sasql_query($this->__connection, $query)) {
			$this->__setErrors('SQLER');
			$query = null;
		}
		$this->__result = $query;		
		return $this->__result;
	}



	/**
	 * Returns number os rows of the query.
	 * @return integer
	 */
	public function rowCount()
	{
		return sasql_num_rows( $this->__result );
	}

	/**
	 * Returns number os rows of the query.
	 * @return integer
	 */
	public function fieldCount()
	{
		return sasql_num_fields( $this->connection );
	}

	/**
	 * The number of rows affected.
	 * @return integer
	 */
	public function affectedRows()
	{
		return sasql_affected_rows( $this->connection );
	}

	/**
	 * Returns number os rows of the query.
	 * This function is for simple of name
	 * @return integer
	 */
	public function count($type='row')
	{
		if ($type=='row')
			return sasql_num_rows( $this->__result );
		return sasql_num_fields( $this->connection );
	}

	/**
	 * Return one row of __result
	 * @param  constant $type Format of return
	 * @return array|object       
	 */
	public function fetch($type=SQLAnywhereClient::FETCH_ASSOC)
	{
		$data = null;
		if ($this->__result) {
			switch ($type) {
				case 'array':
					$data = sasql_fetch_array( $this->__result );
				break;

				case 'assoc':
					$data = sasql_fetch_assoc( $this->__result );
				break;

				case 'row':
					$data = sasql_fetch_row( $this->__result );
				break;

				case 'field':
					$data = sasql_fetch_field( $this->__result );
				break;

				case 'object':
					$data = sasql_fetch_object( $this->__result );
				break;
				
				default:
					$data = sasql_fetch_array( $this->__result );
				break;
			}		
		}
		return $data;
	}

	/**
	 * Return All values of Results in one choose format
	 * @param  constant $type Format of return
	 * @return array       
	 */
	public function fetchAll($type=SQLAnywhereClient::FETCH_ASSOC)
	{
		$data = array();
		
		if ($this->__result) {
			switch ($type) {
				case 'array':
					while ($row = sasql_fetch_array( $this->__result ))
						array_push($data, $row);
				break;

				case 'assoc':
					while ($row = sasql_fetch_assoc( $this->__result ))
						array_push($data, $row);
				break;

				case 'row':
					while ($row = sasql_fetch_row( $this->__result ))
						array_push($data, $row);
				break;

				case 'field':
					while ($row = sasql_fetch_field( $this->__result ))
						array_push($data, $row);
				break;

				case 'object':
					while ($row = sasql_fetch_object( $this->__result ))
						array_push($data, $row);			
				break;
				
				default:
					while ($row = sasql_fetch_array( $this->__result ))
						array_push($data, $row);
				break;
			}		
		}

		return $data;
	}

	/**
	 * Return value of de __result in Onject
	 * @return object __results
	 */
	public function fetchObject()
	{
		return sasql_fetch_object( $this->__result );
	}

	/**
	 * Fetches all results of the $result and generates an HTML output table with an optional formatting string.
	 * @param  string $table_format  Format in HTML of table, EX: "border=1,cellpadding=5"
	 * @param  string $header_format Format HTML of header of Table
	 * @param  string $row_format    Format HTML of row table
	 * @param  string $cell_format   Format HTML of cell
	 * @return boolean               TRUE on success or FALSE on failure.
	 */
	public function resultAll( $table_format=null, $header_format=null, $row_format=null, $cell_format=null )
	{
		return sasql_result_all( $this->__result, $table_format, $header_format, $row_format, $cell_format );
	}

	/**
	 * Frees database resources associated with a result resource returned from sasql_query.
	 * @return boolean 			TRUE on success or FALSE on error.
	 */
	public function freeResults()
	{
		return sasql_free_result( $this->__result );
	}

	public function __setErrors($er) {
		if(!is_resource($this->__connection)) {
			$errno = sasql_errorcode();
			$errst = sasql_error();
		}
		else {
			$errno = sasql_errorcode($this->__connection);
			$errst = sasql_error($this->__connection);
		}
		$this->__errorCode = &$er;
		$this->__errorInfo = array($this->__errorCode, $errno, $errst);
		$this->__result = null;
	}

}