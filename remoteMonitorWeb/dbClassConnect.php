<?php
	class conn extends db
	{
		/*define your own account*/
		var $host = "localhost";
		var $usrname = "root";
		var $pwd = "123456";
		
		/*define your own database*/
		var $dbname = "test";	
		var $db_code = "utf-8";
		
		function conn()
		{
			$link = mysql_connect($this->host,$this->usrname,$this->pwd) or die("Can't connect to mysql");
			
			mysql_select_db($this->dbname,$link);
			mysql_query("set names ".$this->db_code);
		}
	}
?>
