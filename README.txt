Les scripts de ce répertoire visent à apporter quelques améliorations au logiciel de gestion de projet Agora Project.

Pour commencer, envoyez le dossier "hack_Topaza" sur votre serveur, dans le répertoire d'installation d'Agora Project.

﻿Le dossier "notification" contient les améliorations apportées à la notification par mail d'Agora.
Pour les installer, il faut supprimer dans chaque module concerné le code contenu entre les commentaires
'//// ENVOI DE NOTIFICATION PAR MAIL' et '//// FERMETURE DU POPUP ', puis le remplacer par les lignes indiquées ci-dessous
(correspondant à l'inclusion du fichier php et l'appel à la fonction associée) :

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


Le dossier "export" contient les scripts permettant l'export des taches sous format de fichier csv. Cela permet de récupérer
les données importantes et de les organiser plus finement avec des logiciels pouvant faire des diagrammes de Gantt complets.
Pour l'installer, il faudra ajouter les fichiers export_taches.php et export_tache.inc.php au dossier divers d'agora. 
Le code ci-dessous doit également être inséré dans le fichier index.php du module tâche, juste avant le commentaire //// MENU ELEMENTS :
	
 	////	EXPORTER LES TACHES
	if($droit_acces_dossier>=1.5 && $_SESSION["user"]["id_utilisateur"]>0) 
		echo "<div class='menu_gauche_ligne lien' onclick=\"popup('".PATH_DIVERS."export_taches.php?id_dossier=".$_GET["id_dossier"]."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/export_import.png\" /></div><div class='menu_gauche_txt'>"."Exporter Taches du Dossier"."</div></div>";
		echo "<hr />";

