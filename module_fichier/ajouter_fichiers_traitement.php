<?php
////	INITIALISATION  &  ESPACE OCCUPE  &  CHEMIN VERS LE DOSSIER DE DESTINATION  &  REPARAMETRAGE PHP
////
require "commun.inc.php";
$espace_occupe = taille_stock_fichier(true);
$chemin_dossier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$_POST["id_dossier"],"url");
modif_php_ini();

////	RECUPERATION DES FICHIERS ENVOYES EN HTML5 (VIA DOSSIER TEMPORAIRE)
////
if(preg_match("/plupload/i",$_POST["type_selection"]))
{
	if(!isset($_FILES))	$_FILES = array();
	$dossier_tmp_upload = PATH_TMP.$_POST["dossier_tmp_upload"];
	if(is_dir($dossier_tmp_upload)){
		$dir_tmp = opendir($dossier_tmp_upload);
		while($file = readdir($dir_tmp))	{ if(is_file($dossier_tmp_upload."/".$file))  $_FILES[] = array("error"=>0, "upload_html5"=>"1", "tmp_name"=>$dossier_tmp_upload."/".$file, "name"=>$file, "size"=>filesize($dossier_tmp_upload."/".$file)); }
	}
}

////	NOUVELLE VERSION D'UN FICHIER
////
if(@$_POST["id_fichier_version"]>0)
{
	// Récup' des infos du fichier
	$fichier_old = objet_infos($objet["fichier"],$_POST["id_fichier_version"]);
	// Alerte si la nouvelle extension est différente de l'ancienne
	foreach($_FILES as $fichier)	{ if($fichier["name"]!="" && extension($fichier["name"])!=$fichier_old["extension"])  alert($trad["MSG_ALERTE_type_version"]." : ".extension($fichier["name"])); }
}


////	TRAITEMENT DES FICHIERS
////
////	ERREUR (aucun fichier uploadé / dossier en lecture seule)
if(!isset($_FILES) || count($_FILES)==0)	{ alert($trad["MSG_ALERTE_taille_fichier"]); }
elseif(!is_writable($chemin_dossier))		{ alert($trad["MSG_ALERTE_chmod_stock_fichiers"]); }
////	UPLOAD OK
else
{
	foreach($_FILES as $id_input_fichier => $fichier)
	{
		////	ERREUR (fichier trop grop / pas assez d'espace disque  /  interdit)
		if($fichier["error"]==1 || $fichier["error"]==2)					{ alert($trad["MSG_ALERTE_taille_fichier"]." : ".$fichier["name"]); }
		elseif(controle_fichier("fichier_interdit",$fichier["name"]))		{ alert($trad["MSG_ALERTE_type_interdit"]." : ".$fichier["name"]); }
		elseif(($espace_occupe+$fichier["size"]) > limite_espace_disque)	{ alert($trad["MSG_ALERTE_espace_disque"]);  break; }
		////	FICHIER OK
		elseif($fichier["error"]==0)
		{
			////	Incrémente la taille de l'espace disque  +  Description (formulaire simple)  +  Extension
			$espace_occupe += $fichier["size"];
			$fichier["description"] = @$_POST[str_replace("fichier","description",$id_input_fichier)];
			$fichier["extension"] = extension($fichier["name"]);

			////	Infos SQL principales
			$sql_nom = "nom=".db_format($fichier["name"]).", extension=".db_format($fichier["extension"]);
			$sql_details = "description=".db_format($fichier["description"]).", taille_octet='".$fichier["size"]."'";
			$sql_version = "date_crea='".db_insert_date()."', id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]);

			////	NOUVELLE VERSION DU FICHIER
			if(@$_POST["id_fichier_version"]>0)
			{
				$fichier["id_fichier"] = $fichier_old["id_fichier"];
				db_query("UPDATE gt_fichier SET  ".$sql_nom.", ".$sql_details."  WHERE id_fichier=".db_format($fichier["id_fichier"]));
				add_logs("modif", $objet["fichier"], $fichier["id_fichier"]);
			}
			////	NOUVEAU FICHIER
			else
			{
				// fichier existe déjà avec le meme nom ?
				$fichier_exist = db_valeur("SELECT count(*) FROM gt_fichier WHERE id_dossier='".intval($_POST["id_dossier"])."' AND nom='".addslashes($fichier["name"])."'");
				if($fichier_exist>0)	alert($trad["MSG_ALERTE_nom_fichier"]." : ".$fichier["name"]);
				// Enregistre le fichier
				db_query("INSERT INTO gt_fichier SET id_dossier=".db_format($_POST["id_dossier"]).", ".$sql_nom.", ".$sql_details.", ".$sql_version.", raccourci=".db_format(@$_POST["raccourci"],"bool"));
				$fichier["id_fichier"] = db_last_id();
				add_logs("ajout", $objet["fichier"], $fichier["id_fichier"]);
				// Affectation des droits d'accès !!
				affecter_droits_acces($objet["fichier"],$fichier["id_fichier"]);
			}

			////	Nom réel / chemins du fichier  +  Enregistre la version du fichier  +  Transfert vers le dossier final
			$nom_reel_fichier = $fichier["id_fichier"]."_".time().$fichier["extension"];
			$chemin_fichier	  = $chemin_dossier.$nom_reel_fichier;
			db_query("INSERT INTO gt_fichier_version SET id_fichier=".db_format($fichier["id_fichier"]).", nom=".db_format($fichier["name"]).", nom_reel=".db_format($nom_reel_fichier).", ".$sql_details.", ".$sql_version);
			if(isset($fichier["upload_html5"]))		@copy($fichier["tmp_name"], $chemin_fichier);
			else									move_uploaded_file($fichier["tmp_name"], $chemin_fichier);
			chmod($chemin_fichier,0775);

			////	Optimise l'image ?
			if(isset($_POST["optimiser"]) && controle_fichier("image_gd",$chemin_fichier) && is_writable($chemin_fichier))
			{
				reduire_image($chemin_fichier, $chemin_fichier, $_POST["optimiser_taille"], $_POST["optimiser_taille"], 85);
				clearstatcache();//efface le cache linux pour MAJ la taille du fichier
				$fichier["size"] = filesize($chemin_fichier);
				db_query("UPDATE gt_fichier SET taille_octet='".$fichier["size"]."' WHERE id_fichier='".$fichier["id_fichier"]."'");
				db_query("UPDATE gt_fichier_version SET taille_octet='".$fichier["size"]."' WHERE id_fichier='".$fichier["id_fichier"]."' AND nom_reel='".$nom_reel_fichier."'");
			}

			////	Créé une vignette d'image ou de PDF : limité aux fichiers inférieurs à 3Mo
			if($fichier["size"] < (3*1048576)  &&  is_writable($chemin_fichier))
			{
				// Vignette Image
				if(controle_fichier("image_gd",$nom_reel_fichier))
				{
					$fichier["vignette"] = $fichier["id_fichier"].$fichier["extension"];
					reduire_image($chemin_fichier, PATH_MOD_FICHIER2.$fichier["vignette"], 200, 200);
				}
				// Vignette PDF
				elseif(controle_fichier("pdf",$nom_reel_fichier) && @class_exists('Imagick'))
				{
					$fichier["vignette"] = $fichier["id_fichier"].".jpg";
					$image_tmp = new Imagick($chemin_fichier);
					$image_tmp->setIteratorIndex(0);//select 1ère page
					$image_tmp->resizeImage(200,200,Imagick::FILTER_POINT,1);
					$image_tmp->writeImage(PATH_MOD_FICHIER2.$fichier["vignette"]);
				}
				if(isset($fichier["vignette"]))  db_query("UPDATE gt_fichier SET vignette='".$fichier["vignette"]."' WHERE id_fichier='".$fichier["id_fichier"]."'");
			}
		}
	}

	////	ENVOI DE NOTIFICATION PAR MAIL
	////
	include_once('../hack_Topaza/notification/notif_topaza_fichiers_traitement.php');
}


////	NETTOYAGE DES DOSSIERS TMP  +  ENREGISTRE PREF. D'OPTIMISATION  +  FERMETURE DU POPUP  +  RECALCULE  $_SESSION["agora"]["taille_stock_fichier"]
////
if(isset($dossier_tmp_upload))  rm($dossier_tmp_upload);
nettoyer_tmp();
pref_user("optimiser_taille");
taille_stock_fichier(true);
reload_close();
?>
	<div style="text-align:right;margin-top:20px;">
		<input type="hidden" name="id_dossier" value="<?php echo $fichier_tmp["id_dossier"]; ?>" />
	</div>