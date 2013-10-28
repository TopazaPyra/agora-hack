<?php
//// MODULE FICHIER

	if(isset($_POST["notification"]) && control_upload()==true && @$last_id_fichier>0)
	{
		// On prends les droits d'acces du dernier fichier
		$liste_id_destinataires = users_affectes($objet["fichier"], $last_id_fichier);
		$nomdossier = db_ligne("SELECT nom FROM gt_fichier_dossier WHERE id_dossier=".$_POST["id_dossier"].";");
		if($nomdossier['nom']=="") { $nomdossier['nom']="Racine";}
		$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Fichier - Dossier ".$nomdossier['nom'];
		$contenu_mail = $trad["FICHIER_mail_nouveau_fichier_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"]." :<br /><br />";
		$contenu_mail .= "Nom(s) du/des fichier(s) ajoute(s) : <br />";
		foreach($_FILES as $fichier)	{ if($fichier["name"]!="")	$contenu_mail .= $fichier["name"]."<br />"; }
		$options = array("notif"=>true);
		if(empty($_POST["notif_joindre_fichiers"]))		$options["fichiers_joints"] = false;

		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= "/".MODULE_PATH."/index.php?id_dossier=".$_POST["id_dossier"];
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, $contenu_mail, $options);
	}

?>
