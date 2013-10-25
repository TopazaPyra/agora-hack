<?php
//// MODULE FORUM

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["sujet"], $_POST["id_sujet"]);
		$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Sujet - ".$_POST["titre"];
		$theme = db_ligne("SELECT gt_forum_theme.titre FROM gt_forum_theme RIGHT JOIN gt_forum_sujet ON gt_forum_theme.id_theme=gt_forum_sujet.id_theme WHERE id_sujet='".$_POST["id_sujet"]."' AND gt_forum_theme.id_theme!='NULL'");
		$contenu_mail = "";
		if ($theme > 0) {
			$contenu_mail .= $theme[0]." -> ";
		}
		
		$contenu_mail .= $_POST["titre"];
	
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/sujet.php?id_sujet=".$_POST["id_sujet"];
		$contenu_mail .= "<br />".$lien;
		if($_POST["description"]!="")	{ $contenu_mail .= "<br /><br />".$_POST["description"]; }
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}

?>
