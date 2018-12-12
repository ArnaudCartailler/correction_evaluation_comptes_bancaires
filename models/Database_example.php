<?php

/**
 * Class to connect to the data base
 */
class Database
{

	const HOST = "localhost",
		  DBNAME = "",
		  LOGIN = "",
		  PWD = "";

	/**
	 * Connect to the database
	 *
	 * @return PDO $db
	 */
	static public function DB()
	{
		try
		{
			$db = new PDO("mysql:host=" . self::HOST .";dbname=" . self::DBNAME , self::LOGIN, self::PWD);
			return $db;
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

}
