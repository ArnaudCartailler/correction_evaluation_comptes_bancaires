<?php
declare(strict_types = 1);

/**
 *  Classe permettant de gérer les opérations en base de données concernant les objets Account
 */
class AccountManager
{

	private $_db;

	/**
	 * Constructor
	 *
	 * @param PDO $db
	 */
	public function __construct(PDO $db) 
	{
		$this->setDb($db);
	}

	/**
	 * Set the value of $_db
	 *
	 * @param PDO $db
	 * return self
	 */
	public function setDb(PDO $db) 
	{
		$this->_db = $db;
		return $this;
	}

	/**
	 * Get the value of $_db
	 *
	 * @return $_db
	 */
	public function getDb()
	{
		return $this->_db;
	}

	/**
	 * Add account to the database
	 *
	 * @param Account $account
	 */
	public function add(Account $account)
	{
		$query = $this->getDb()->prepare('INSERT INTO accounts(name, balance) VALUES (:name, :balance)');
		$query->bindValue("name", $account->getName(), PDO::PARAM_STR);
		$query->bindValue("balance", $account->getBalance(), PDO::PARAM_INT);
		$query->execute();
	}

	/**
	 * Get all accounts
	 *
	 */
	public function getAccounts()
	{

		$arrayOfAccounts = [];
		$query = $this->getDb()->query('SELECT * FROM accounts');

		// On récupère un tableau contenant plusieurs tableaux associatifs
		$dataAccounts = $query->fetchAll(PDO::FETCH_ASSOC);

		// A chaque tour de boucle, on récupère un tableau associatif concernant un seul compte
		foreach ($dataAccounts as $dataAccount) 
		{
			// On crée un nouvel objet grâce au tableau associatif, qu'on stocke dans $arrayOfAccounts
			$arrayOfAccounts[] = new Account($dataAccount);
		}
		return $arrayOfAccounts;
	}

	/**
	 * Get an account by id
	 *
	 * @param integer $id
	 * @return Account
	 */
	public function getAccount(int $id)
	{
		$id = (int) $id;
		$query = $this->getDb()->prepare('SELECT * FROM accounts WHERE id = :id');
		$query->bindValue("id", $id, PDO::PARAM_INT);
		$query->execute();

		$dataAccount = $query->fetch(PDO::FETCH_ASSOC);
		return new Account($dataAccount);
	}

	/**
	 * Update account
	 *
	 * @param Account $account
	 */
	public function update(Account $account)
	{
		$query = $this->getDb()->prepare('UPDATE accounts SET balance = :balance WHERE id = :id');
		$query->bindValue("balance", $account->getBalance(), PDO::PARAM_INT);
		$query->bindValue("id", $account->getId(), PDO::PARAM_INT);
		$query->execute();
	}

	/**
	 * Delete account
	 *
	 * @param integer $id
	 */
	public function delete(int $id)
	{
		$query = $this->getDb()->prepare('DELETE FROM accounts WHERE id = :id');
		$query->bindValue("id", $id, PDO::PARAM_INT);		
		$query->execute();
	}
}
