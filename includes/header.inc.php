<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<!--  AGORA-PROJECT is under the GNU General Public License (http://www.gnu.org/licenses/gpl.html)  -->
	<title><?php echo $_SESSION["agora"]["nom"]; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="<?php echo $trad["HEADER_HTTP"]; ?>" />
	<meta name="Description" content="<?php echo $_SESSION["agora"]["description"]." - ".@$_SESSION["espace"]["description"]; ?>">
	<link rel="icon" type="image/gif" href="<?php echo PATH_TPL; ?>divers/icone.gif" />
	<?php
	////	STYLE CSS
	include_once PATH_TPL."style.css.php";
	////	EDITION DES ELEMENTS DANS UN POPUP/IFRAME? DANS L'ESPACE?
	echo "<script type=\"text/javascript\">  edition_popup='".@$_SESSION["agora"]["edition_popup"]."';  </script>";
	?>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>jquery/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>jquery/jquery-ui-1.9.0.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>jquery/effects.js"></script>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>jquery/pulsate.js"></script>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>jquery/floating.js"></script>
	<script type="text/javascript" src="<?php echo PATH_COMMUN; ?>javascript_2.16.2.js"></script> <!-- toujours après Jquery.. -->
</head>


<body onkeyup="action_clavier(event);">

	<?php
	////	IMAGE BACKGROUND (UTILISE "str_replace" AU CAS OU L'ON SE TROUVE EN PAGE DE CONNEXION..)
	if(IS_MAIN_PAGE==true)	echo "<div class='img_background'><img src=\"".str_replace("../",ROOT_PATH,$_SESSION["agora"]["path_fond_ecran"])."\" class='noprint'/></div>";
	?>

	<div id="infobulle" class="infobulle noprint">&nbsp;</div>
	<div id="div_loading" class="img_loading"><img src="<?php echo PATH_TPL."divers/".LOADING_IMG; ?>" /></div>

	<div id="page_fantome" class="page_fantome">
		<button onClick="page_fantome_close();" id="page_fantome_fermer" class="button page_fantome_fermer"><?php echo $trad["fermer"]; ?> <img src="<?php echo PATH_TPL; ?>divers/supprimer.png" /></button>
		<div class="page_fantome_table">
			<div id="page_fantome_contenu"></div>
			<iframe id="page_fantome_iframe" name="page_fantome_iframe" allowtransparency="true" frameborder="0">NO IFRAME</iframe>
		</div>
	</div>
