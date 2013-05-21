Le dossier Notification contient les améliorations apportées à la notification par mail d'Agora.
Pour l'installer il faut :
	- Supprimer dans chaque module concerné, dans chaque fichier contenant '_edit' le code contenu entre les commentaires
	'//// ENVOI DE NOTIFICATION PAR MAIL' et '//// FERMETURE DU POPUP ' et les remplacer parles lignes suivantes qui sont
	l'inclusion du fichier php et l'appel à la fonction associée