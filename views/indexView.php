<?php

include('includes/header.php');

?>

<div class="container">

	<header class="flex">
		<p class="margin-right">Bonjour utilisateur anonyme</p>
	</header>


	<?php
		if(isset($error_message)){
	?>
		<p class="error-message"> <?php echo $error_message; ?></p>
	<?php
		}
	?>

	<h1>Mon application bancaire</h1>

	<form class="newAccount" action="index.php" method="post">
		<select class="" name="name" required>
			<option value="" disabled>Choisissez le type de compte à ouvrir</option>
			
			<?php 
			// Je boucle sur tous les différents comptes possible à créer
			foreach ($authorizedAccounts as $authorizedAccount) 
			{ 
			?>
				<option value="<?php echo $authorizedAccount ;?>"><?php echo $authorizedAccount ;?></option>
			<?php 
			} 
			?>
		</select>
		<input type="submit" name="new" value="Ouvrir un nouveau compte">
	</form>

	<hr>

	<div class="main-content flex">

	<?php
	// Je boucle sur $accounts
	// À chaque tour, je récupère un compte présent en base de données
	foreach ($accounts as $account) 
	{
	?>

		<div class="card-container">

			<div class="card">
				<h3><strong><?php echo $account->getName(); ?></strong></h3>
				<div class="card-content">


					<p>Somme disponible : <?php echo $account->getBalance(); ?> €</p>

					<h4>Dépot / Retrait</h4>
					<form action="index.php" method="post">
						<input type="hidden" name="id" value="<?php echo $account->getId(); ?>"  required>
						<input type="number" name="balance" placeholder="Entrer une somme à débiter/créditer" required>
						<input type="submit" name="payment" value="Créditer">
						<input type="submit" name="debit" value="Débiter">
					</form>

			 		<form action="index.php" method="post">

						<h4>Transfert</h4>
						<input type="number" name="balance" placeholder="somme à transférer"  required>
						<input type="hidden" name="idDebit" placeholder="Compte à débiter" value="<?php echo $account->getId(); ?>" required>
						<select name="idPayment" required>
							<option value="" disabled>Choisir un compte</option>
							<?php
								// Je boucle sur tous les comptes créés
								foreach ($accounts as $accountSelect) 
								{
									// Si l'id du compte en cours est différent du compte sur lequel transférer l'argent, je l'affiche
									if($account->getId() != $accountSelect->getId())
									{
							?>
							<option value="<?php echo $accountSelect->getId(); ?>" ><?php echo $accountSelect->getName(); ?></option>
							<?php
									}
								}
					 		?>
						</select>

						<input type="submit" name="transfer" value="Transférer l'argent">
					</form>

			 		<form class="delete" action="index.php" method="post">
				 		<input type="hidden" name="id" value="<?php echo $account->getId(); ?>"  required>
				 		<input type="submit" name="delete" value="Supprimer le compte">
			 		</form>

				</div>
			</div>
		</div>

		<?php
			}
		?>
	</div>

</div>

<?php

include('includes/footer.php');

 ?>
