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
          'http://localhost:8000/api/v1/users/' . $resourceOwner["id"] . '/assos/', //Url à appeler (ici on demande la liste des associations de l'utilisateur)
          $accessToken //l'access token
          //["foo" => "bar"], les paramètres pour la requête
        );
        $response = $provider->getParsedResponse($request); //Lancer la requête
        for($i = 0; $i<count($response); $i++) {
		echo "<p>asso: id : " . $response[$i]["id"] . ", nom : " . $response[$i]["shortname"] . ", rôle de l'utilisateur : " . $response[$i]["pivot"]["role_id"] . "</p>";
      	}
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
