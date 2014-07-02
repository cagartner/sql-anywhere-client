<?php namespace Cagartner;

use Cagartner\SQLAnywhereQuery AS SQLAnywhereQuery;

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

					$__query = preg_replace_callback ("/(\?)/", function ($matches) use ($c, $array, $k) {
						return "'" . sasql_escape_string($c, $array[$k++]) . "'";
					}, $__query);
					break;
				}
			}

			if(isset($tempf))
				$__query = str_replace($tempf, $tempr, $__query);
		}
		$this->__result = $this->__uquery($__query);
		$this->__boundParams = array();		$this->__boundParams = array();

		return $this->__result;
	}

	public function paramCount()
	{
		return sasql_stmt_param_count( $this->statement );
	}

	public function __uquery(&$query) {
		if(!$query = sasql_query( $this->__connection, $query )) {
			$this->__setErrors('SQLER');
			return false;
		}
		$this->__result = new SQLAnywhereQuery( $query, $this->__connection );		
		return $this->__result;
	}

	/**
	 * Returns number os rows of the query.
	 * @return integer
	 */
	public function rowCount()
	{
		return $this->__result->rowCount();
	}

	/**
	 * Returns number os rows of the query.
	 * @return integer
	 */
	public function fieldCount()
	{
		return sasql_num_fields( $this->__connection );
	}

	/**
	 * The number of rows affected.
	 * @return integer
	 */
	public function affectedRows()
	{
		return sasql_affected_rows( $this->__connection );
	}

	/**
	 * Returns number os rows of the query.
	 * This function is for simple of name
	 * @return integer
	 */
	public function count($type='row')
	{
		if ($type=='row')
			return $this->__result->rowCount();
		return sasql_num_fields( $this->__connection );
	}

	/**
	 * Return one row of __result
	 * @param  constant $type Format of return
	 * @return array|object       
	 */
	public function fetch($type=SQLAnywhereClient::FETCH_ASSOC)
	{
		return $this->__result->fetch($type);
	}

	/**
	 * Return All values of Results in one choose format
	 * @param  constant $type Format of return
	 * @return array       
	 */
	public function fetchAll($type=SQLAnywhereClient::FETCH_ASSOC)
	{
		return $this->__result->fetchAll($type);
	}

	/**
	 * Return value of de __result in Onject
	 * @return object __results
	 */
	public function fetchObject()
	{
		return $this->__result->fetchObject();
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
		return $this->__result->resultAll($table_format, $header_format, $row_format, $cell_format);
	}

	/**
	 * Frees database resources associated with a result resource returned from sasql_query.
	 * @return boolean 			TRUE on success or FALSE on error.
	 */
	public function freeResults()
	{
		return $this->__result->freeResults();
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
		$this->__result = false;
	}

}