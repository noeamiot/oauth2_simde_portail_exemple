<?php
  /* Cette page est appellée lorsque l'utilisateur est totalement déconnecté et qu'il souhaite se connecter.
  * Elle redirige l'utilisateur sur une page ou il autorisera votre site à acceder à ses données (ce qui sera automatique si il a déjà accepté).
  * C'est le serveur des assos qui gère en interne toutes les autorisation, vous n'avez aucun utilisateur à enregistrer
  */

  require_once("config.php");

  //Génération du lien d'autorisation sur le serv des assos
  $authorizationUrl = $provider->getAuthorizationUrl();

  //On génère un state qu'on place dans une session pour limiter les attaque CSRF
  $_SESSION['oauth2state'] = $provider->getState();

  //On redirige l'utilisateur sur le portail
  header('Location: ' . $authorizationUrl);
  exit;
?>
