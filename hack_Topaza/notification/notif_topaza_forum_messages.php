<?php
//// MODULE FORUM

	$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Message - ".$_POST["titre"];
	$theme = db_ligne("SELECT gt_forum_theme.titre FROM gt_forum_theme RIGHT JOIN gt_forum_sujet ON gt_forum_theme.id_theme=gt_forum_sujet.id_theme WHERE id_sujet='".$_POST["id_sujet"]."' AND gt_forum_theme.id_theme!='NULL'");
	$contenu_mail = "";
	if ($theme > 0) {
		$contenu_mail .= $theme[0]." -> ";
	}
	$sujet = db_ligne("SELECT gt_forum_sujet.titre FROM gt_forum_sujet RIGHT JOIN gt_forum_message ON gt_forum_sujet.id_sujet=gt_forum_message.id_sujet WHERE gt_forum_sujet.id_sujet='".$_POST["id_sujet"]."'");
	$contenu_mail .= $sujet[0]." -> ".$_POST["titre"];
	
	$lien = $_SESSION["agora"]["adresse_web"];
	if(strpos($lien, "index.php")){
		$lien = rtrim($lien, "index.php");
	}
	$lien .= MODULE_DOSSIER."/sujet.php?id_sujet=".$_POST["id_sujet"];
	$contenu_mail .= "<br />".$lien;
	
	if($_POST["description"]!="")	$contenu_mail .= "<br /><br />".$_POST["description"];
	// Notif pour les destinataires qui sont affectes au sujet (ou destinataires specifies dans les box)
	if(isset($_POST["notification"])){
		$liste_id_destinataires = users_affectes($objet["sujet"], $_POST["id_sujet"]);
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}
	// Envoi aux personnes "abonnees" aux notifs (n'envoie pas 2 fois le mail!)
	$users_notifier_dernier_message = objet_infos($objet["sujet"], $_POST["id_sujet"], "users_notifier_dernier_message");
	if($users_notifier_dernier_message!="")
	{
		$liste_id_destinataires2 = array();
		foreach(explode("u",$users_notifier_dernier_message) as $det_tmp){
			if(is_numeric($det_tmp) && in_array($det_tmp,$liste_id_destinataires)==false)	$liste_id_destinataires2[] = $det_tmp;
		}
		if(count($liste_id_destinataires2)>0)	envoi_mail($liste_id_destinataires2, $objet_mail, magicquotes_strip($contenu_mail), array("message_alert"=>false));
	}

?>
