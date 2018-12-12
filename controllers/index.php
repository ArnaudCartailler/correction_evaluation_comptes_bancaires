<?php

// On enregistre notre autoload.
function chargerClasse($classname)
{
    if(file_exists('../models/'. $classname.'.php')){
        require '../models/'. $classname.'.php';
    }
    else {
        require '../entities/' . $classname . '.php';
    }
}
spl_autoload_register('chargerClasse');

// Connexion à la base de données
$db = Database::DB();

// On instancie notre manager
$manager = new AccountManager($db);



// Si le formulaire de création de compte est soumis
if(isset($_POST['new'])){

	// Si le champ name est bien rempli, et n'est pas vide
	if(isset($_POST['name']) && !empty($_POST['name'])){

		$name = htmlspecialchars($_POST['name']);

		// Si le nom envoyé correspond aux noms autorisés dans la classe Account
		if (in_array($name, Account::ACCOUNTS)){

			// On instancie un objet $account
			$account = new Account([
				'name' => $name,
			]);

			// On enregistre l'objet $account dans la base de données
			$manager->add($account);

		} 
		// Si le nom ne correspond pas aux noms autorisés dans la classe, on déclare un message d'erreur
		else {
			$error_message = "Le type de compte n'est pas valide";
		}

	} 
	// Si le champ name est vide ou n'existe pas, on déclare un message d'erreur
	else {
		$error_message = "Veuillez renseigner le champ";
	}

}

// Si c'est le formulaire pour ajouter ou retirer de l'argent qui est soumis
elseif(isset($_POST['debit']) || isset($_POST['payment'])){

	if((isset($_POST['id']) && !empty($_POST['id'])) && (isset($_POST['balance']) && !empty($_POST['balance']))){

		// On récupère l'ID du compte et on le convertit en integer
		$id = (int) $_POST['id'];
		// On récupère la somme envoyée et on le convertit en integer
		$balance = (int) $_POST['balance'];

		// On crée une instance $account en fonction de l'ID
		$account = $manager->getAccount($id);

		// Si on clique sur retirer
		if(isset($_POST['debit'])){
			// On retire de "l'argent"
			$account->debit($balance);
		}
		// Sinon on ajoute
		else {
			$account->payment($balance);
		}

		// On met à jour notre base de données avec la nouvelle valeur de l'attribut $balance
		$manager->update($account);
	}
	else {
		$error_message = "Veuillez saisir une somme à ajouter/débiter";
	}
}

// Si c'est le formulaire de transert qui est soumis
elseif(isset($_POST['transfer'])){

	// Si tous les champs sont renseignés
	if((isset($_POST['idDebit']) && !empty($_POST['idDebit'])) && (isset($_POST['idPayment']) && !empty($_POST['idPayment'])) && (isset($_POST['balance']) && !empty($_POST['balance']))){

		$idDebit = (int) $_POST['idDebit'];
		$idPayment = (int) $_POST['idPayment'];
		$balance = (int) $_POST['balance'];

		// On instancie l'objet compte à créditer, et celui à débiter
		$accountDebit = $manager->getAccount($idDebit);
		$accountPayment = $manager->getAccount($idPayment);

		// La méthode tranfer permet d'effectuer le virement
		// Elle est appelé sur le compte à débiter. On passe en argument le compte à créditer, ainsi que la somme
		$accountDebit->transfer($accountPayment, $balance);

		// On met à jour nos deux comptes en base de données
		$manager->update($accountDebit);
		$manager->update($accountPayment);
	}
	else {
		$error_message = "Veuillez saisir une somme à transférer";
	}
}

// Si c'est le formulaire de suppression qui est soumis
elseif(isset($_POST['delete'])){

	if(isset($_POST['id']) && !empty($_POST['id'])){

		$id = (int) $_POST['id'];

		// On le supprime en BDD
		$manager->delete($id);
	}
	else {
		$error_message = "Veuillez choisir un compte à supprimer";
	}

}



// La variable $authorizedAccounts nous sert à lister les comptes possibles à créer dans notre vue
// $authorizedAccounts est un tableau sur lequel on bouclera
$authorizedAccounts = Account::ACCOUNTS;

// On récupère tous les comptes dans la BDD
// $accounts est un tableau contenant tous les objets comptes présents en DB
$accounts = $manager->getAccounts();

// Enfin, on inclut la vue
include "../views/indexView.php";
