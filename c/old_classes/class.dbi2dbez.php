<?php
  class dbi2dbez {
    /** @var string Internal variable to hold the query sql */
    var $_sql            = '';
    /** @var int Internal variable to hold the database error number */
    var $_errorNum        = 0;
    /** @var string Internal variable to hold the database error message */
    var $_errorMsg        = '';
    /** @var string Internal variable to hold the prefix used on all database tables */
    var $_table_prefix    = '';
    /** @var Internal variable to hold the connector resource */
    var $_resource        = '';
    /** @var Internal variable to hold the connector resource for update and inserts */
    var $_uresource        = '';
    /** @var Internal variable to hold the last query cursor */
    var $_cursor          = null;
    /** @var boolean Debug option */
    var $_debug           = DEBUG;
    /** @var int The limit for the query */
    var $_limit           = 0;
    /** @var int The for offset for the limit */
    var $_offset          = 0;
    /** @var int A counter for the number of queries performed by the object instance */
    var $_ticker          = 0;
    /** @var array A log of queries */
    var $_log             = null;
    /** @var string The null/zero date string */
    var $_nullDate        = '0000-00-00 00:00:00';
    /** @var string Quote for named objects */
    var $_nameQuote       = '`';
    var $server       = '';
    var $userver      = '';
    var $singleserver = 0;

    /**
     * @param int
     */
    function debug( $level ) {
        $this->_debug = intval( $level );
    }
    /**
     * @return int The error number for the most recent query
     */
    function getErrorNum() {
        return $this->_errorNum;
    }
    /**
    * @return string The error message for the most recent query
    */
    function getErrorMsg() {
        return str_replace( array( "\n", "'" ), array( '\n', "\'" ), $this->_errorMsg );
    }
    /**
    * Get a database escaped string
    * @return string
    */
    function getEscaped( $text ) {
    	global $dbez;
        return $dbez->escape( $text );
    }
    
    function escape( $text ) {
    	global $dbez;
        return $dbez->escape( $text );
    }
    /**
    * Get a quoted database escaped string
    * @return string
    */
    //function Quote( $text ) {
    function quote( $text ) {
        return '\'' . $this->getEscaped( $text ) . '\'';
    }
    /**
     * Quote an identifier name (field, table, etc)
     * @param string The name
     * @return string The quoted name
     */
    function NameQuote( $s ) {
        $q = $this->_nameQuote;
        if (strlen( $q ) == 1) {
            return $q . $s . $q;
        } else {
            return $q{0} . $s . $q{1};
        }
    }
    /**
     * @return string The database prefix
     */
    function getPrefix() {
        return $this->_table_prefix;
    }
    /**
     * @return string Quoted null/zero date string
     */
    function getNullDate() {
        return $this->_nullDate;
    }
    /**
    * Sets the SQL query string for later execution.
    *
    * This function replaces a string identifier <var>$prefix</var> with the
    * string held is the <var>_table_prefix</var> class variable.
    *
    * @param string The SQL query
    * @param string The offset to start selection
    * @param string The number of results to return
    * @param string The common table prefix
    */
    function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' ) {
        $this->_sql = $this->replacePrefix( $sql, $prefix );
        $this->_limit = intval( $limit );
        $this->_offset = intval( $offset );
    }

    /**
     * This function replaces a string identifier <var>$prefix</var> with the
     * string held is the <var>_table_prefix</var> class variable.
     *
     * @param string The SQL query
     * @param string The common table prefix
     * @author thede, David McKinnis
     */
    function replacePrefix( $sql, $prefix='#__' ) {
        $sql = trim( $sql );

        $escaped = false;
        $quoteChar = '';

        $n = strlen( $sql );

        $startPos = 0;
        $literal = '';
        while ($startPos < $n) {
            $ip = strpos($sql, $prefix, $startPos);
            if ($ip === false) {
                break;
            }

            $j = strpos( $sql, "'", $startPos );
            $k = strpos( $sql, '"', $startPos );
            if (($k !== FALSE) && (($k < $j) || ($j === FALSE))) {
                $quoteChar    = '"';
                $j            = $k;
            } else {
                $quoteChar    = "'";
            }

            if ($j === false) {
                $j = $n;
            }

            $literal .= str_replace( $prefix, $this->_table_prefix, substr( $sql, $startPos, $j - $startPos ) );
            $startPos = $j;

            $j = $startPos + 1;

            if ($j >= $n) {
                break;
            }

            // quote comes first, find end of quote
            while (TRUE) {
                $k = strpos( $sql, $quoteChar, $j );
                $escaped = false;
                if ($k === false) {
                    break;
                }
                $l = $k - 1;
                while ($l >= 0 && $sql{$l} == '\\') {
                    $l--;
                    $escaped = !$escaped;
                }
                if ($escaped) {
                    $j    = $k+1;
                    continue;
                }
                break;
            }
            if ($k === FALSE) {
                // error in the query - no end quote; ignore it
                break;
            }
            $literal .= substr( $sql, $startPos, $k - $startPos + 1 );
            $startPos = $k+1;
        }
        if ($startPos < $n) {
            $literal .= substr( $sql, $startPos, $n - $startPos );
        }
        return $literal;
    }
    /**
    * @return string The current value of the internal SQL vairable
    */
    function getQuery() {
        return "<pre>" . htmlspecialchars( $this->_sql ) . "</pre>";
    }
    /**
    * Execute the query
    * @return mixed A database resource if successful, FALSE if not.
    */
    function query() {
    	global $dbez;
        //global $mosConfig_debug;

        if ($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        //$this->_cursor = mysqli_query( $this->_resource, $this->_sql );
        $this->_cursor=$dbez->query($this->_sql); 
        return $this->_sql;
    }
    function uquery()
	{
		global $dbez;
		$dbez->query($this->_sql); 
	}

    /**
     * @return int The number of affected rows in the previous operation
     */

    /**
    * @return int The number of rows returned from the most recent query.
    */
    function getNumRows( $cur=null ) {
    	global $dbez;
        return count($dbez->get_results( $cur ? $cur : $this->_cursor ));
    }

    /**
    * This method loads the first field of the first row returned by the query.
    *
    * @return The value returned in the query or null if the query failed.
    */
    function loadResult() {
        global $dbez;
        if (!($cur = $this->query())) {
            return null;
        }
        return $dbez->get_var($cur);
    }
    /**
    * Load an array of single field results into an array
    */
    function loadResultArray($numinarray = 0) {
       global $dbez;
	   if (!($cur = $this->query())) {
            return null;
        }
        return $dbez->get_results( $cur );
    }
    
    


    /**
    * This global function loads the first row of a query into an object
    *
    * If an object is passed to this function, the returned row is bound to the existing elements of <var>object</var>.
    * If <var>object</var> has a value of null, then all of the returned query fields returned in the object.
    * @param string The SQL query
    * @param object The address of variable
    */
    function loadObject( &$object ) {
    	global $dbez;
       	if ($cur = $this->query()) {
       		
            if ($object = $dbez->get_row($cur)) {
                return true;
            } else {
                $object = null;
                return false;
            }
        } else {
            return false;
        }
    }
    /**
    * Load a list of database objects
    * @param string The field name of a primary key
    * @return array If <var>key</var> is empty as sequential list of returned records.
    * If <var>key</var> is not empty then the returned array is indexed by the value
    * the database key.  Returns <var>null</var> if the query fails.
    */
    function loadObjectList( $key='' ) {
        global $dbez;
	   	if (!($cur = $this->query())) {
            return null;
        }
		$list=$dbez->get_results( $cur );
		if(count($list)==0)
		{
			return array ();
		}
		if ($key!='' ) {
			foreach($list as $l)
			{
				$kList[$l->$key]=$l;
			}
			return $kList;
		}else{
			return $list;
		}
    }
    /**
    * @return The first row of the query.
    */
    function loadRow() {
        global $dbez;
	   	if (!($cur = $this->query())) {
            return null;
        }
        return $dbez->get_row($cur);
    }
    /**
    * Load a list of database rows (numeric column indexing)
    * @param string The field name of a primary key
    * @return array If <var>key</var> is empty as sequential list of returned records.
    * If <var>key</var> is not empty then the returned array is indexed by the value
    * the database key.  Returns <var>null</var> if the query fails.
    */
    function loadRowList( $key='' ) {
    	echo "loadRowList function in class dbi2dbez";
		die; 
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysqli_fetch_row( $cur )) {
            if ($key) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysqli_free_result( $cur );
        return $array;
    }
    /**
    * Document::db_insertObject()
    *
    * { Description }
    *
    * @param [type] $keyName
    * @param [type] $verbose
    */
    function insertObject( $table, &$object, $keyName = NULL, $verbose=false ) {
        $fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";
        $fields = array();
        foreach (get_object_vars( $object ) as $k => $v) {
            if (is_array($v) or is_object($v) or $v === NULL) {
                continue;
            }
            if ($k[0] == '_') { // internal field
                continue;
            }
            $fields[] = $this->NameQuote( $k );
            $values[] = $this->Quote( $v );
        }
        $this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
        ($verbose) && print "$sql<br />\n";
        if (!$this->query()) {
            return false;
        }
        $id = $this->insertid();  
        //$id = mysqli_insert_id( $this->_resource );
        ($verbose) && print "id=[$id]<br />\n";
        if ($keyName && $id) {
            $object->$keyName = $id;
        }
        return true;
    }

    /**
    * Document::db_updateObject()
    *
    * { Description }
    *
    * @param [type] $updateNulls
    */
    function updateObject( $table, &$object, $keyName, $updateNulls=true ) {
        $fmtsql = "UPDATE $table SET %s WHERE %s";
        $tmp = array();
        foreach (get_object_vars( $object ) as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            if( $k == $keyName ) { // PK not to be updated
                $where = $keyName . '=' . $this->Quote( $v );
                continue;
            }
            if ($v === NULL && !$updateNulls) {
                continue;
            }
            if( $v === 0 ) {
                $val = $this->Quote( $v );
            } elseif( $v == '' ) {
                $val = "NULL";
            } else {
                $val = $this->Quote( $v );
            }
            $tmp[] = $this->NameQuote( $k ) . '=' . $val;
        }
        $this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
        return $this->query();
    }

    /**
    * @param boolean If TRUE, displays the last SQL statement sent to the database
    * @return string A standised error message
    */

    function insertid() {
		global $dbez;
        return $dbez->insert_id;
    }

    /**
     * @return array A list of all the tables in the database
     */
    function getTableList() {
        $this->setQuery( 'SHOW TABLES' );
        return $this->loadResultArray();
    }
}
?>
