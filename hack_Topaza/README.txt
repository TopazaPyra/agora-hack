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

