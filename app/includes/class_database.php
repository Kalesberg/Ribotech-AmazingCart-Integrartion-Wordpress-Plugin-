<?php

class JDatabase
{
	/// the server to connect to
	var $server;
	/// the database to use
	var $dbname;
	/// the username to use
	var $user;
	/// the password to use
	var $password;

	var $on_error = 'report';
	var $just_set_error = false;
	var $ise = false;

	var $connection;
	var $queryresult;

	/*!
		Constructs a new Db object, connects to the db server and
		selects the desired database.
	*/
	function __construct($host, $dbname, $dbuser, $dbpasswd, $oe = 'report', $just_set_error = false)
	{
		$this->on_error = $oe;
		$this->just_set_error = $just_set_error;
		$this->ise = false;

		$this->server = $host;
		$this->dbname = $dbname;
		$this->user = $dbuser;
		$this->password = $dbpasswd;

		$this->connect();
	}

	function connect()
	{

		$this->connection = @mysql_connect( $this->server, $this->user, $this->password ) or $this->error( "could not connect to the database server ($this->server, $this->user)." );

		@mysql_select_db( $this->dbname ) or $this->error( "could not select the database ($this->DB)." );

		//@mysql_set_charset('utf8',$this->connection); 
	}

	/*!
		executes query, returns query result handle
	*/
	function &query($q, $print = false)
	{
		if ($print) { echo "<br><b>query: </b>" . htmlentities($q) . "<br>";}

		($this->queryresult = mysql_query($q, $this->connection)) or $this->error("<b>bad SQL query</b>: " . htmlentities($q) . "<br><b>". mysql_error() ."</b>");

		return $this->queryresult;
	}

	// js, 2001.07.10, array -> assoc
	function get_array()
	{
		return mysql_fetch_assoc($this->queryresult);
	}

	function free_result()
	{
		return mysql_free_result($this->queryresult);
	}

	/*!
		returns two dimensional assoc array
		frees mysql result
	*/
	function &get_result( $sql = '' )
	{
		if ($sql)
		{
			$this->query($sql);
		}
		
		$c = 0;
		
		$res = array();
		
		while ($row = mysql_fetch_assoc($this->queryresult))
		{
			$res[$c] = $row;
			$c++;
		}
		
		mysql_free_result($this->queryresult);
		return $res;
	}

	function &get_row_count($sql = ''){
		if ($sql) { $this->query($sql); }

		return mysql_num_rows($this->queryresult);
	}

	function &get_result_array( $sql = '' )
	{
		if ($sql) { $this->query($sql); }
		$c = 0;
		while ($row = mysql_fetch_array($this->queryresult))
		{
			$res[$c] = $row;
			$c++;
		}
		mysql_free_result($this->queryresult);
		return $res;
	}

	/*!
		hom many rows affected
	*/
	function affected($sql = '')
	{
		if ($sql) { $this->query($sql); }
		$ret = mysql_affected_rows();

		return $ret;
	}

	/*!
		is query result set empty ?
	*/
	function is_empty($sql = '')
	{
		if ($sql) { $this->query($sql); }
		if (0 == mysql_num_rows($this->queryresult))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function not_empty($sql = '')
	{
		if ( $sql ) { $this->query( $sql ); }
		if ( 0 == mysql_num_rows( $this->queryresult ) )
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function close()
	{
		mysql_close( $this->connection );
	}

	function get_insert_id()
	{
		return mysql_insert_id();
	}

	function insert_query($mas, $table, $ignore=false, $update=false)
	{
		
		//debug_me($mas);
		foreach ($mas as $k=>$v)
		{
			$to[] = $k;
			$val[] = addslashes($v);
			//echo "<li>".$k." = ".addslashes($v);
		}

		if ($ignore)
		{
			$sql = "INSERT IGNORE INTO $table (`".implode('`,`',$to)."`) VALUES ('".implode("','",$val)."');";
		}
		else if ($update)
		{
			$update_sql = "";
			if (sizeof($to)>0)
			{
				for ($i=0; $i<sizeof($to); $i++)
				{
					if (strlen($update_sql)>0)
					{
						$update_sql .= ",";
					}
					$update_sql .= "`".$to[$i]."`='".$val[$i]."'";  
				}
			}
			
			$sql = "INSERT INTO $table (`".implode('`,`',$to)."`) VALUES ('".implode("','",$val)."') ON DUPLICATE KEY UPDATE ".$update_sql.";";
		}
		else
		{
			$sql = "INSERT INTO $table (`".implode('`,`',$to)."`) VALUES ('".implode("','",$val)."');";			
		}
		
		$result =& $this->query( $sql );

		if ($result) { return true; } else { return false; }
	}

	function update_query($mas, $table, $id, $id_name="id")
	{
		if (is_array($id))
		{
			while(list($idn,$idv)=each($id))
			{
				$where[] = $idn."='$idv'";
			}
		}
		else
		{
			$where[] = $id_name."=$id";
		}

		while(list($k,$v)=each($mas))
		{
			$to[] = $k."='$v'";
		}

		$sql = "UPDATE $table SET ".implode(',',$to)." WHERE ".implode(" AND ",$where);

		$result =& $this->query( $sql );
		
		return $result;
	}

	function replace_query($mas, $table, $print = false)
	{
		while(list($k,$v)=each($mas))
		{
			$to[] = $k;
			$val[] = $v;
		}

		$sql = "REPLACE INTO $table  (".implode(',',$to).") VALUES ('".implode("','",$val)."')";

		$result =& $this->query( $sql, $print );

		return $result;
	}

	/*!
	  Prints the error message.
	*/
	function error($errmsg)
	{
		if ($this->just_set_error) 
		{
			$this->ise = true;
			echo "error: $errmsg\n";
			return false;
		}
		
		die(mysql_error());
		exit(1);
	}
}

?>
