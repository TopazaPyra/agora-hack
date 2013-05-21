<?php
//// MODULE AGENDA
function notif_evenements(){
	if(isset($_POST["notification"]) && $evt_tmp["droit_acces"]==3)
	{
		// Fichier .Ical (temporaire)
		$evt_ical = objet_infos($objet["evenement"],$_POST["id_evenement"]);
		$nom_fichier = suppr_carac_spe($evt_ical["titre"],"normale").".ics";
		$fichier_tmp = PATH_TMP.uniqid(mt_rand()).$nom_fichier;
		$fp = fopen($fichier_tmp, "w");
		fwrite($fp, fichier_ical(array($evt_ical)));
		fclose($fp);
		$_FILES[] = array("error"=>0, "type"=>"text/Calendar", "name"=>$nom_fichier, "tmp_name"=>$fichier_tmp);
		// Destinataires + titre + description
		$tab_id_user_notif = (isset($_POST["notif_destinataires"]) && count($_POST["notif_destinataires"])>0)  ?  $_POST["notif_destinataires"]  :  $tab_id_user_notif;
		$objet_mail = $trad["AGENDA_mail_nouvel_evenement_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = "Nom de l'evenement : ".$_POST["titre"]."<br />"."Date et plage horaire : ".temps($date_debut,"normal",$date_fin);
 		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/index.php?date_affiche=".strtotime(strftime("%Y-%m-%d",$config["agenda_debut"]));
		
		$contenu_mail .= "<br /> Lien : ".$lien."<br />"; 		
		if($_POST["description"]!="")	$contenu_mail .= "<br /><br />".$_POST["description"];
		// envoi du mail et supprime le fichier .Ical
		envoi_mail($tab_id_user_notif, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true,"envoi_fichiers"=>true));
		unlink($fichier_tmp);
	}
}
?>