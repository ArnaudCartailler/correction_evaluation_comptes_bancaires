<?php

declare(strict_types = 1);

class Account
{

	protected $id,
			  $name,
			  $balance = 80;

	const ACCOUNTS = ['Compte Courant','Livret A', 'PEL', 'Compte Joint'];

	/**
	 * Constructor
	 *
	 * @param array $array
	 */
	public function __construct(array $array)
	{
		$this->hydrate($array);
	}

	/**
	 * Hydratation
	 *
	 * @param array $array
	 *
	 */
	public function hydrate(array $array)
	{
		foreach ($array as $key => $value)
		{
			// On récupère le nom du setter correspondant à l'attribut.
			$method = 'set'.ucfirst($key);

			// Si le setter correspondant existe.
			if (method_exists($this, $method))
			{
				// On appelle le setter.
				$this->$method($value);
			}
		}
	}

	//////////////////    SETTERS    //////////////////

	/**
	 * Set the value of $id
	 *
	 * @param integer $id
	 * @return self
	 */
	public function setId($id)
	{
		$id = (int) $id;
		if($id > 0){
			$this->id = $id;
		}
		return $this;
	}

	/**
	 * Set the value of $name
	 *
	 * @param string $name
	 * @return self
	 */
	public function setName(string $name)
	{
		// Si le nom entré fait partie des types de comptes autorisés
		if (in_array($name, self::ACCOUNTS))
		{
			$this->name = $name;
		}
		return $this;
	}

	/**
	 * Set the value of $balance
	 *
	 * @param integer $balance
	 * @return self
	 */
	public function setBalance($balance)
	{
		$balance = (int) $balance;
		$this->balance = $balance;
		return $this;
	}


	//////////////////    GETTERS    //////////////////

	/**
	 * Get the value of $id
	 *
	 * @return $id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the value of $name
	 *
	 * @return $name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the value of $balance
	 *
	 * @return $balance
	 */
	public function getBalance()
	{
		return $this->balance;
	}


	/**
	 * Add money to the account
	 *
	 * @param integer $balance
	 * 
	 */
	public function payment(int $balance)
	{
		$balance = (int) $balance;
		return $this->balance += $balance;
	}

	/**
	 * Withdraw money
	 *
	 * @param integer $balance
	 *
	 */
	public function debit(int $balance)
	{
		$balance = (int) $balance;
		return $this->balance -= $balance;
	}

	/**
	 * Transfer money from one account to another
	 *
	 * @param Account $account
	 * @param integer $balance
	 */
	public function transfer(Account $account, $balance)
	{
		$balance = (int) $balance;
		$this->debit($balance);
		$account->payment($balance);
	}

}
