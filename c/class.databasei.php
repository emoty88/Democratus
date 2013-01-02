<?php
  class databasei {
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
    * Database object constructor
    * @param string Database host
    * @param string Database user name
    * @param string Database user password
    * @param string Database name
    * @param string Common prefix for all tables
    * @param boolean If true and there is an error, go offline
    */
    function connect( $host='localhost', $user, $pass, $db='', $table_prefix='', $goOffline=true ) {
        // perform a number of fatality checks, then die gracefully
        if (!function_exists( 'mysqli_connect' )) {
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        }
        if (!($this->_resource = @mysqli_connect( $host, $user, $pass ))) {
            throw new Exception('db error');
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        }
        
        mysqli_query($this->_resource,"SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
        mysqli_query($this->_resource,"SET CHARACTER SET 'utf8_general_ci'");
        mysqli_query($this->_resource,"SET COLLATION_CONNECTION = 'utf8_general_ci'");
        
/**
        mysqli_query($this->_resource,"SET NAMES 'utf8' COLLATE 'utf8_turkish_ci'");
        mysqli_query($this->_resource,"SET CHARACTER SET 'utf8_turkish_ci'");
        mysqli_query($this->_resource,"SET COLLATION_CONNECTION = 'utf8_turkish_ci'");
/*
* 
init_connect='SET collation_connection = utf8_turkish_ci'
init_connect='SET NAMES utf8' 
init_connect='SET NAMES utf8 COLLATE utf8_turkish_ci'



init_connect='SET NAMES utf8 COLLATE utf8_turkish_ci'

/etc/init.d/mysql restart

 
*/
        
        if ($db != '' && !mysqli_select_db($this->_resource, $db)) {
            throw new Exception('db error: select db');
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        } 
        
        
        $this->_table_prefix = $table_prefix;
        $this->_ticker = 0;
        $this->_log = array();
        $this->server = $host;
    }
    
    function uconnect( $host='localhost', $user, $pass, $db='', $table_prefix='', $goOffline=true ) {
        // perform a number of fatality checks, then die gracefully
        if (!function_exists( 'mysqli_connect' )) {
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        }
        if (!($this->_uresource = @mysqli_connect( $host, $user, $pass ))) {
            throw new Exception('db error');
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        }
        if ($db != '' && !mysqli_select_db($this->_uresource, $db)) {
            throw new Exception('db error: select db');
            if ($goOffline) {
                include COREPATH . 'offline.php';
                exit();
            }
        }
        $this->_table_prefix = $table_prefix;
        $this->_ticker = 0;
        $this->_log = array();
        $this->userver = $host;
    }
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
        return mysqli_real_escape_string( $this->_resource, $text );
    }
    
    function escape( $text ) {
        return mysqli_real_escape_string( $this->_resource, $text );
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
        //global $mosConfig_debug;
        if ($this->_debug) {
            $this->_ticker++;
              $this->_log[] = $this->_sql;
        }
        if ($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        $this->_cursor = mysqli_query( $this->_resource, $this->_sql );
        if (!$this->_cursor) {
            $this->_errorNum = mysqli_errno( $this->_resource );
            $this->_errorMsg = mysqli_error( $this->_resource ) . " SQL=$this->_sql";
            if ($this->_debug) {
                trigger_error( mysqli_error( $this->_resource ), E_USER_NOTICE );
                //echo "<pre>" . $this->_sql . "</pre>\n";
                if ($this->_debug>1 && function_exists( 'debug_backtrace' )) {
                    foreach( debug_backtrace() as $back) {
                        if (@$back['file']) {
                            echo '<br />'.$back['file'].':'.$back['line'];
                        }
                    }
                }
            }
            throw new Exception('db query error');
            return false;
        }
        return $this->_cursor;
    }
    
    /**
    * Execute the query
    * @return mixed A database resource if successful, FALSE if not.
    */
    function uquery() {
        if($this->singleserver) return $this->query();

        if ($this->_debug) {
            $this->_ticker++;
              $this->_log[] = $this->_sql;
        }
        if ($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        $this->_cursor = mysqli_query( $this->_uresource, $this->_sql );
        if (!$this->_cursor) {
            $this->_errorNum = mysqli_errno( $this->_uresource );
            $this->_errorMsg = mysqli_error( $this->_uresource ) . " SQL=$this->_sql";
            if ($this->_debug) {
                trigger_error( mysqli_error( $this->_uresource ), E_USER_NOTICE );
                //echo "<pre>" . $this->_sql . "</pre>\n";
                if ($this->_debug>1 && function_exists( 'debug_backtrace' )) {
                    foreach( debug_backtrace() as $back) {
                        if (@$back['file']) {
                            echo '<br />'.$back['file'].':'.$back['line'];
                        }
                    }
                }
            }
            throw new Exception('db uquery error');
            return false;
        }
        return $this->_cursor;
    }

    /**
     * @return int The number of affected rows in the previous operation
     */
    function getAffectedRows() {
        if($this->singleserver)
            return mysqli_affected_rows( $this->_resource );
        else 
            return mysqli_affected_rows( $this->_uresource );
    }


    /**
    * @return int The number of rows returned from the most recent query.
    */
    function getNumRows( $cur=null ) {
        return mysqli_num_rows( $cur ? $cur : $this->_cursor );
    }

    /**
    * This method loads the first field of the first row returned by the query.
    *
    * @return The value returned in the query or null if the query failed.
    */
    function loadResult() {
        if (!($cur = $this->query())) {
            return null;
        }
        $ret = null;
        if ($row = mysqli_fetch_row( $cur )) {
            $ret = $row[0];
        }
        mysqli_free_result( $cur );
        return $ret;
    }
    /**
    * Load an array of single field results into an array
    */
    function loadResultArray($numinarray = 0) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysqli_fetch_row( $cur )) {
            $array[] = $row[$numinarray];
        }
        mysqli_free_result( $cur );
        return $array;
    }
    
    function loadAssoc( $key='' ) {
        if (!($cur = $this->query())) {
            return null;
        }
        
        $row = mysqli_fetch_assoc( $cur );
        mysqli_free_result( $cur );
        return $row;
    }    
    
    /**
    * Load a assoc list of database rows
    * @param string The field name of a primary key
    * @return array If <var>key</var> is empty as sequential list of returned records.
    */
    function loadAssocList( $key='' ) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysqli_fetch_assoc( $cur )) {
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
    * This global function loads the first row of a query into an object
    *
    * If an object is passed to this function, the returned row is bound to the existing elements of <var>object</var>.
    * If <var>object</var> has a value of null, then all of the returned query fields returned in the object.
    * @param string The SQL query
    * @param object The address of variable
    */
    function loadObject( &$object ) {
        if ($cur = $this->query()) {
            if ($object = mysqli_fetch_object( $cur )) {
                mysqli_free_result( $cur );
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
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysqli_fetch_object( $cur )) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysqli_free_result( $cur );
        return $array;
    }
    /**
    * @return The first row of the query.
    */
    function loadRow() {
        if (!($cur = $this->query())) {
            return null;
        }
        $ret = null;
        if ($row = mysqli_fetch_row( $cur )) {
            $ret = $row;
        }
        mysqli_free_result( $cur );
        return $ret;
    }
    /**
    * Load a list of database rows (numeric column indexing)
    * @param string The field name of a primary key
    * @return array If <var>key</var> is empty as sequential list of returned records.
    * If <var>key</var> is not empty then the returned array is indexed by the value
    * the database key.  Returns <var>null</var> if the query fails.
    */
    function loadRowList( $key='' ) {
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
        if (!$this->uquery()) {
            return false;
        }
        if($this->singleserver)
            $id = mysqli_insert_id( $this->_resource );
        else 
            $id = mysqli_insert_id( $this->_uresource );
            
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
        return $this->uquery();
    }

    /**
    * @param boolean If TRUE, displays the last SQL statement sent to the database
    * @return string A standised error message
    */

    function insertid() {
        
        if($this->singleserver)
            return mysqli_insert_id( $this->_resource );
        else 
            return mysqli_insert_id( $this->_uresource );
    }

    function getVersion() {
        return mysqli_get_server_info( $this->_resource );
    }

    /**
     * @return array A list of all the tables in the database
     */
    function getTableList() {
        $this->setQuery( 'SHOW TABLES' );
        return $this->loadResultArray();
    }
    /**
     * @param array A list of table names
     * @return array A list the create SQL for the tables
     */
    function getTableCreate( $tables ) {
        $result = array();

        foreach ($tables as $tblval) {
            $this->setQuery( 'SHOW CREATE table ' . $this->getEscaped( $tblval ) );
            $rows = $this->loadRowList();
            foreach ($rows as $row) {
                $result[$tblval] = $row[1];
            }
        }

        return $result;
    }
    /**
     * @param array A list of table names
     * @return array An array of fields by table
     */
    function getTableFields( $tables ) {
        $result = array();

        foreach ($tables as $tblval) {
            $this->setQuery( 'SHOW FIELDS FROM ' . $tblval );
            $fields = $this->loadObjectList();
            foreach ($fields as $field) {
                $result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type );
            }
        }

        return $result;
    }

    /**
    * Fudge method for ADOdb compatibility
    */
    function GenID( $foo1=null, $foo2=null ) {
        return '0';
    }
}
?>