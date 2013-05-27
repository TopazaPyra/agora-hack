<?php
//// MODULE FICHIER

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["fichier"], $_POST["id_fichier"]);
		$objet_mail = $trad["FICHIER_mail_fichier_modifie"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = "Nom du fichier : ".$_POST["nom"].$_POST["extension"];
		
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/index.php?id_dossier=".$_POST["id_dossier"];
		
		if($_POST["description"]!="")	{ $contenu_mail .= "<br /><br /> Description du fichier : ".$_POST["description"]."<br />";		}
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}

?>