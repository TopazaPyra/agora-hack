<?php
// CONNEXION À LA BDD
define("db_host", "localhost");
define("db_login", "root");
define("db_password", "admin");
define("db_name", "agora");

// EN MAINTENANCE ?  CONTROLE L'ADRESSE IP ?
define("agora_maintenance", false);
define("controle_ip", true);

// ESPACE DISQUE / NB USERS / SALT (A L'INSTALL!)
define("limite_espace_disque", "21474836480");
define("limite_nb_users", "10000");

// DUREE DE CONNEXION AU LIVECOUNTER + MESSENGER
define("duree_livecounter", "45");
define("duree_messages_messenger", 7200);
?>
