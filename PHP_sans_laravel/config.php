<?php
  //Active les sessions pour stocker l'access token
  session_start();

  // Inclu le client oauth pour PHP
  require_once("vendor/autoload.php");

  // l'adresse du serveur sur lequel tourne le portail des assos
  const ASSO_SERV_URL = "http://localhost:8000/";

  //Oauth provider --> portail des assos
  //RedirectUri doit aussi être identique (au slash près) au champ "redirect" dans la table oauth_client du portail
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '53616d79-206a-6520-7427-61696d652021', // L'id du client fourni par le portail (dans la base de test, celui-ci est valide) (table: oauth_client)
    'clientSecret'            => 'password',   // Le mot de passe qui corresponds au client (table: oauth_client)
    'redirectUri'             => 'http://localhost/oauth2_simde_portail_exemple/PHP_sans_laravel/retour_oauth2.php', // L'url de retour après l'autorisation. Elle doit transformer le code oauth en access_token.
    'urlAuthorize'            => ASSO_SERV_URL . "oauth/authorize",
    'urlAccessToken'          => ASSO_SERV_URL . "oauth/token",
    'urlResourceOwnerDetails' => ASSO_SERV_URL . "api/v1/user"
  ]);


  if(isset($_SESSION["access_token"]) && !empty($_SESSION["access_token"])) {
    //Si l'access token existe en session, on le récupère
    $accessToken = new \League\OAuth2\Client\Token\AccessToken($_SESSION["access_token"]);
    if ($accessToken->hasExpired()) {
      //Si il a expiré, on demande le renouvellement (avec le refresh token)
      $newAccessToken = $provider->getAccessToken('refresh_token', [
          'refresh_token' => $accessToken->getRefreshToken()
      ]);

      // Supprime l'ancien access token et sauvegarde le nouveau
      $accessToken = $newAccessToken;
      $_SESSION["access_token"] = $newAccessToken->jsonSerialize();
    }
  }
