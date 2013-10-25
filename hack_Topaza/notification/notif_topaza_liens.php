<?php
//// MODULE LIEN

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["lien"], $_POST["id_lien"]);
		$nomdossier = db_ligne("SELECT nom FROM gt_lien_dossier WHERE id_dossier=".$_POST["id_dossier"].";");
		if($nomdossier['nom']=="") { $nomdossier['nom']="Racine";}
		$descript = substr($_POST["description"],0,16).'...';
		$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Lien - ".$descript;
		$contenu_mail = "<br />Dans le dossier ".$nomdossier['nom'];
		$contenu_mail .= "<br /><a href=\"".$_POST["adresse"]."\" target=\"_blank\">".$_POST["adresse"]."</a>";
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= "/".MODULE_PATH."/index.php?id_dossier=".$_POST["id_dossier"];
		
		if($_POST["description"]!="")	{ $contenu_mail .= "<br /><br /> Description du fichier : ".$_POST["description"]; }
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}


?>
