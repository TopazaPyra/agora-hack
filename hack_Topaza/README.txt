Le dossier Notification contient les améliorations apportées à la notification par mail d'Agora.
Pour l'installer il faut supprimer dans chaque module concerné, le code contenu entre les commentaires
'//// ENVOI DE NOTIFICATION PAR MAIL' et '//// FERMETURE DU POPUP ' et les remplacer par les lignes indiquées ci-dessous
 qui sont l'inclusion du fichier php et l'appel à la fonction associée :

	-Module Agenda : dans le fichier evenement_edit.php insérer : 
		include_once('../hack_Topaza/notification/notif_topaza_agenda.php');


	-Module Contact : dans le fichier contact_edit.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_contacts.php');

	-Module Fichier : dans le fichier ajouter_fichier_traitement.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_fichiers_traitement.php');

	et dans le fichier fichier_edit.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_fichiers.php');

	-Module Forum : dans le fichier sujet_edit.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_forum_sujets.php');

	et dans le fichier message_edit.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_forum_messages.php');

	-Module Lien : dans le fichier lien_edit.php insérer : 
		include_once('../hack_Topaza/notification/notif_topaza_liens.php');

	-Module Taches : dans le fichier tache_edit.php insérer :
		include_once('../hack_Topaza/notification/notif_topaza_taches.php');

	Le dossier export contient les fichiers permettant l'export des taches sous format de fichier csv pour pouvoir
	récupérer les données importantes et offrir la possibilité de mieux les organiser avec des logiciels pouvant faire des diagrammes
	de Gantt complets.
	Pour l'installer il faudra ajouter le code donné ci-dessous juste avant le commentaire ////	MENU ELEMENTS :
	
		if($droit_acces_dossier>=1.5 && $_SESSION["user"]["id_utilisateur"]>0) 
			echo "<div class='menu_gauche_ligne lien' onclick=\"popup('../hack_Topaza/export/export_taches.php?id_dossier=".$_GET["id_dossier"]."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/export_import.png\" /></div><div class='menu_gauche_txt'>"."Exporter Taches du Dossier"."</div></div>";
		echo "<hr />"; 

