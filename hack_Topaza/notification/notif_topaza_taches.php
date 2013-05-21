<?php
//// MODULE TACHE
function notif_taches(){
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["tache"], $_POST["id_tache"]);
		$objet_mail = $trad["TACHE_mail_nouvelle_tache_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$respons = db_tableau("SELECT gt_utilisateur.nom,gt_utilisateur.prenom FROM gt_utilisateur RIGHT JOIN gt_tache_responsable ON gt_tache_responsable.id_utilisateur=gt_utilisateur.id_utilisateur WHERE id_tache=".$_POST["id_tache"].";");
		$contenu_mail = "";

 		foreach($respons as $respons_tmp) {			
			$list_resp .= $respons_tmp["nom"]." ".$respons_tmp["prenom"].", ";
		}
		$list_resp = substr($list_resp, 0, -2);
		$contenu_mail .= "Nom de la tache : ".$_POST["titre"]."<br /> Responsable(s) : ".$list_resp; 
		
 		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= MODULE_DOSSIER;
		
		$contenu_mail .= "<br />".$lien."<br />"; 
		
		if($_POST["description"]!="")	$contenu_mail .= "<br /><br />".$_POST["description"];
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), array("notif"=>true));
	}
}
?>