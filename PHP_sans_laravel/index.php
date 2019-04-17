<?php
  require_once("config.php");
  //Eviter de mélanger php et html, là c'est pour l'exemple ...
?>
<html>
  <head>
    <title>Exemple Oauth2 - portaild des assos</title>
  </head>
  <body>
    <?php
      if(isset($accessToken)&& !empty($accessToken)) {
        //getRessourceOwner renvoie le détail de l'utilisateur qui est connecté (var_dump($resourceOwner) pour afficher tout le détail de l'utilisateur)
        $resourceOwner = $provider->getResourceOwner($accessToken)->toArray();
        echo "Utilisateur connecté: id : " . $resourceOwner["id"] . ", nom : " . $resourceOwner["name"] . "<br>";

        //Pour appeler le serveur des assos, il faut créer une nouvelle requête, ici, on demande les informations sur un utilisateur (les scopes demandés par le client doivent par exemple contenir au moins user-get-email)
        $request = $provider->getAuthenticatedRequest(
          'GET', //Protocol
          'http://localhost:8000/api/v1/users/32ef8cc0-58bb-11e9-83ae-adcbe0a29dea', //Url à appeler (ici l'id d'utilisateur devrait déjà exister dans la bdd)
          $accessToken //l'access token
          //["foo" => "bar"], les paramètres pour la requête
        );
        $response = $provider->getParsedResponse($request); //Lancer la requête
        echo "Utilisateur demandé: id : " . $response["id"] . ", nom : " . $response["name"];
      }
      else {
        ?>
          <p>Utilisateur déconnecté</p>
          <a href="login.php">Se connecter</a>
        <?php
      }
    ?>
  </body>
</html>
