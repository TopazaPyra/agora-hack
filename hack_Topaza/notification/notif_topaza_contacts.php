<?php
//// MODULE CONTACT
function notif_contacts(){
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["contact"], $_POST["id_contact"]);
		$objet_mail = $trad["CONTACT_mail_nouveau_contact_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = "Contact ajoute : ".$_POST["civilite"]." ".$_POST["nom"]." ".$_POST["prenom"];
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER."/index.php?id_dossier=".$_POST["id_dossier"];
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;	
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}

}
?>