<?php
//// MODULE LIEN

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["lien"], $_POST["id_lien"]);
		$objet_mail = $trad["LIEN_mail_nouveau_lien_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = "<a href=\"".$_POST["adresse"]."\" target=\"_blank\">".$_POST["adresse"]."</a>";
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/index.php?id_dossier=".$_POST["id_dossier"];
		
		if($_POST["description"]!="")	{ $contenu_mail .= "<br /><br /> Description du fichier : ".$_POST["description"]; }
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}


?>