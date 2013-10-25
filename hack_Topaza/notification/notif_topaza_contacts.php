<?php
//// MODULE CONTACT

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["contact"], $_POST["id_contact"]);
		$nomdossier = db_ligne("SELECT nom FROM gt_contact_dossier WHERE id_dossier=".$_POST["id_dossier"].";");
		if($nomdossier['nom']=="") { $nomdossier['nom']="Racine";}
		$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Contact - ".$_POST["nom"]." ".$_POST["prenom"];
		$contenu_mail = "Dans le dossier ".$nomdossier['nom'];
		$contenu_mail .= "Contact ajoute : ".$_POST["civilite"]." ".$_POST["nom"]." ".$_POST["prenom"];
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= "/module_".MODULE_NOM."/index.php?id_dossier=".$_POST["id_dossier"];
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;	
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}


?>
