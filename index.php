<?php
////	INIT
define("ROOT_PATH","./");
define("IS_MAIN_PAGE",true);
define("CONTROLE_SESSION",false);
require_once "./includes/global.inc.php";
require_once PATH_INC."header.inc.php";
$login_surbrillance = $trad["mail"]." / ".$trad["login"];


////	REINITIALISATION DU MOT DE PASSE
////
if(isset($_GET["id_newpassword"]) && isset($_GET["id_utilisateur"]))
{
	$user_tmp = db_ligne("SELECT * FROM gt_utilisateur WHERE id_newpassword=".db_format($_GET["id_newpassword"])." AND id_utilisateur='".intval($_GET["id_utilisateur"])."'");
	////	"id_password" expiré
	if(count($user_tmp)==0)  { alert($trad["PASS_OUBLIE_id_newpassword_expire"]); }
	////	"id_password" valide
	else
	{
		// On demande un nouveau mot de passe  (récup les $_GET avec $_SERVER["QUERY_STRING"])
		if(!isset($_GET["password"]))	{ echo "<script type='text/javascript'>  prompt_page_fantome(\"".$trad["PASS_OUBLIE_prompt_changer_pass"]."\", \"redir('index.php?".$_SERVER["QUERY_STRING"]."&password='+get_value('prompt_result'));\");  </script>"; }
		// Enregistrement du nouveau mot de passe
		else{
			db_query("UPDATE gt_utilisateur SET pass='".sha1_pass($_GET["password"])."', id_newpassword=null WHERE id_utilisateur='".$user_tmp["id_utilisateur"]."'");
			$_COOKIE["AGORAP_LOG"] = $user_tmp["identifiant"];
			alert($trad["PASS_OUBLIE_password_reinitialise"]);
		}
	}
}


////	CONFIRMER INVITATION
////
if(isset($_GET["id_invitation"]) && isset($_GET["mail"]))
{
	$invitation_tmp = db_ligne("SELECT * FROM gt_invitation WHERE id_invitation='".intval($_GET["id_invitation"])."' AND mail=".db_format($_GET["mail"],"insert_ext"));
	////	"id_invitation" expiré
	if(count($invitation_tmp)==0)  { alert($trad["UTILISATEURS_id_invitation_expire"]); }
	////	"id_invitation" valide
	else
	{
		// Choix du mot de passe avant validation
		if(!isset($_GET["password"]))	{ echo "<script type='text/javascript'>  prompt_page_fantome(\"".$trad["UTILISATEURS_invitation_confirmer_password"]."\", \"redir('index.php?".$_SERVER["QUERY_STRING"]."&password='+get_value('prompt_result'));\", \"text\", \"".$invitation_tmp["pass"]."\");  </script>"; }
		// Enregistrement du nouvel utilisateur (utilise "addslashes" car les données ne sont pas "GPC")
		elseif(nb_users_depasse(false,false)!=true)
		{
			$id_user_tmp = creer_utilisateur(addslashes(@$invitation_tmp["nom"]), addslashes(@$invitation_tmp["prenom"]), addslashes($invitation_tmp["mail"]), addslashes($_GET["password"]), addslashes($invitation_tmp["id_espace"]), addslashes($invitation_tmp["mail"]));
			if($id_user_tmp > 0){
				db_query("DELETE FROM gt_invitation WHERE id_invitation=".db_format($_GET["id_invitation"]));
				$_COOKIE["AGORAP_LOG"] = user_infos($id_user_tmp,"identifiant"); //Préremplis le champ 'login'
				alert($trad["UTILISATEURS_invitation_valide"]);
			}
		}
	}
}
?>


<style>
.block_connexion	{ <?php echo STYLE_BLOCK.STYLE_FONT_BOLD; ?> width:700px; margin:auto; text-align:center; display:none; margin-bottom:80px; }
.block_icone		{ position:absolute; margin-top:-20px; margin-left:-20px; }
.options_connexion	{ margin-left:15px; }
</style>


<script type="text/javascript">
////	AFFICHAGE LA PAGE DE CONNEXION AVEC "FADE"  +  PREPARATION DE L'INPUT "login"  +  INFOS SUR LE NAVIGATEUR
////
$(document).ready(function(){
	// identifiant par défaut : Cookie / "votre identifiant"
	if("<?php echo @$_COOKIE["AGORAP_LOG"]; ?>"!="")	element('login').value = "<?php echo @$_COOKIE["AGORAP_LOG"]; ?>";
	else												login_shadow(true);
	// Infos sur le navigateur dans le formulaire de connexion
	element("infos_navigateur").innerHTML = "<input type='hidden' name='resolution_width' value='"+$(document).width()+"' /><input type='hidden' name='resolution_height' value='"+$(document).height()+"' /><input type='hidden' name='navigateur' value='"+navigateur()+"' />";
	// navigateur obsolète ?
	if(navigateur()=="ie" && version_ie()<7)	alert("<?php echo $trad["version_ie"]; ?>");
	// apparition en "fade" du formulaire
	$("#div_entete").fadeIn(500);
	$("#div_connexion").fadeIn(500);
	$("#div_espaces_publics").fadeIn(500);
});


////	STYLE DES CHAMPS DE CONNEXION
////
function login_shadow(afficher)
{
	if(afficher==false && element('login').value=="<?php echo $login_surbrillance; ?>"){
		element('login').value = "";
		element('login').style.color = "#000";
	}
	if(afficher==true && element('login').value=="") {
		element('login').value = "<?php echo $login_surbrillance; ?>";
		element('login').style.color = "#aaa";
	}
}


////	ACCES INVITE  (accès direct ou avec mot de passe)
////
function Espace_Public(id_espace, mot_de_passe)
{
	// Config du navigateur
	url_config_navigateur = "&resolution_width="+$(document).width()+"&resolution_height="+$(document).height()+"&navigateur="+navigateur();
	// Accès direct sans password  /  Saisie du mot de passe  /  Controle Ajax du mot de passe
	if(mot_de_passe=="0")			{ redir("index.php?id_espace_acces="+id_espace+url_config_navigateur); }
	else if(mot_de_passe=="1")		{ prompt_page_fantome("<?php echo $trad["pass"]; ?>", "Espace_Public('"+id_espace+"',get_value('prompt_result'));", "password"); }
	else if(mot_de_passe.length > 2){
		requete_ajax("divers/espace_password_verif.php?password="+urlencode(mot_de_passe)+"&id_espace="+id_espace);
		if(trouver("oui",Http_Request_Result))	{ redir('index.php?id_espace_acces='+id_espace+'&password='+mot_de_passe+url_config_navigateur); }
		else									{ alert("<?php echo $trad["espace_password_erreur"]; ?>"); return false; }
	}
}
</script>



<table id="div_entete" style="display:none;padding:5px;width:100%;height:25px;background-image:url('<?php echo PATH_TPL."divers/".(@$_SESSION["agora"]["skin"]=="blanc"?"header_droite.png":"header_droite_noir.png"); ?>');"><tr>
	<td style="text-align:left;font-size:16px;font-weight:bold;"><?php echo @$_SESSION["agora"]["nom"]; ?></td>
	<td style="text-align:right;"><?php if(@$_SESSION["agora"]["description"]!="") echo $_SESSION["agora"]["description"]; ?></td>
</tr></table>
<hr style="visibility:hidden;margin-top:150px;" />


<form action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_connexion('<?php echo addslashes($trad["specifier_login_password"]) ?>','<?php echo $login_surbrillance; ?>','<?php echo $trad["pass"]; ?>');" id="div_connexion" class="block_connexion">
	<div class="block_icone"><img src="<?php echo PATH_TPL; ?>divers/connexion.png" /></div>
	<div style="text-align:center;padding:40px;padding-top:50px;">
		<input type="text" name="login" value="<?php echo @$_COOKIE["AGORAP_LOG"]; ?>" style="width:180px;margin-right:20px;" onFocus="login_shadow(false);" onBlur="login_shadow(true);" />
		<input type="password" name="password" value="<?php echo $trad["pass"]; ?>" style="width:140px;margin-right:20px;" <?php echo infobulle($trad["pass"]); ?>  onFocus="this.value='';" />
		<input type="submit" value="<?php echo $trad["connexion"]; ?>" class="button" style="width:140px;" />
		<div id="infos_navigateur" style="diplay:none;"></div>
	</div>
	<div style="text-align:right;padding:3px;">
		<?php
		////	S'INSCRIRE
		if(db_valeur("select count(*) from gt_espace where inscription_users='1'")>0)
			echo "<span class='lien options_connexion' onClick=\"iframe_page_fantome('./module_utilisateurs/utilisateur_inscription.php','500px');\" ".infobulle($trad["inscription_users_info"],500).">".$trad["inscription_users"]."&nbsp; <img src=\"".PATH_TPL."divers/check.png\" style='height:15px;' /></span> ";
		////	PASSWORD OUBLIE
		echo "<span class='lien  options_connexion' id='password_oublie' onClick=\"popup('".PATH_DIVERS."password_oublie.php');\" ".infobulle($trad["password_oublie_info"],500).">".$trad["password_oublie"]."</span> ";
		if(@$_GET["msg_alerte"]=="identification")	echo "<script>  $('#password_oublie').css('color','#d00');  $('#password_oublie').effect('pulsate',{times:5},1000); </script>";
		////	RESTER CONNECTE
		echo "<span class='options_connexion' ".infobulle($trad["connexion_auto_info"],500)." >";
			echo "<span class='lien' id='txt_connexion_auto' onClick=\"check_txt_box(this.id,'connexion_auto');\">".$trad["connexion_auto"]."</span>";
			echo "<input type='checkbox' name='connexion_auto' value='1' id='box_connexion_auto' onClick=\"check_txt_box(this.id,'connexion_auto');\" />";
		echo "</span>";
		////	MEMORISER ID
		echo "<span class='options_connexion' ".infobulle($trad["memoriser_identifiant_info"])." >";
			echo "<span class='lien' id='txt_memoriser_identifiant' onClick=\"check_txt_box(this.id,'memoriser_identifiant');\">".$trad["memoriser_identifiant"]."</span>";
			echo "<input type='checkbox' name='memoriser_identifiant' value='1' id='box_memoriser_identifiant' onClick=\"check_txt_box(this.id,'memoriser_identifiant');\" />";
		echo "</span>";
		?>
	</div>
</form>


<?php
////	ESPACES PUBLICS
////
$infos_user["id_utilisateur"] = 0;
$liste_espaces = espaces_affectes_user($infos_user);
if(count($liste_espaces)>0)
{
	echo "<div id='div_espaces_publics' class='block_connexion pas_selection'>";
		echo "<div class='block_icone'><img src=\"".PATH_TPL."divers/connexion_public.png\" /></div>";
		echo "<div style='display:table;margin:auto;'>";
			echo "<div style='display:table-cell;padding:15px;font-size:14px;'>".$trad["acces_invite"]."</div>";
			echo "<div style='display:table-cell;padding:15px;text-align:left;'>";
			foreach($liste_espaces as $infos_espace){
				echo "<div onClick=\"Espace_Public('".$infos_espace["id_espace"]."','".($infos_espace["password"]!=""?"1":"0")."');\" class='lien' style='font-size:14px;padding:5px;'><img src=\"".PATH_TPL."divers/fleche_droite.png\" />&nbsp; ".$infos_espace["nom"]."</div>";
			}
			echo "</div>";
		echo "</div>";
	echo "</div>";
}


////	FOOTER
require PATH_INC."footer.inc.php";
?>