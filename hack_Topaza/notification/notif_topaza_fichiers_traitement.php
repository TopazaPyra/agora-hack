<?php

	if(isset($_POST["notification"]) && control_upload()==true)
	{
		// On prends les droits d'acces du dernier fichier OU du fichier auquel on ajoute une version
		$id_fichier_tmp = (@$_POST["id_fichier_version"]>0)   ?  $fichier_old["id_fichier"]  :  $fichier["id_fichier"];
		$liste_id_destinataires = users_affectes($objet["fichier"], $id_fichier_tmp);
		$objet_mail = $trad["FICHIER_mail_nouveau_fichier_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $trad["FICHIER_mail_nouveau_fichier_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"]." :<br /><br />";
		$contenu_mail .= "Nom(s) du/des fichier(s) ajoute(s) : <br />";
		foreach($_FILES as $fichier)	{ if($fichier["name"]!="")	$contenu_mail .= $fichier["name"]."<br />"; }
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/index.php?id_dossier=".$_POST["id_dossier"];
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, $contenu_mail, array("notif"=>true));
	}

?>