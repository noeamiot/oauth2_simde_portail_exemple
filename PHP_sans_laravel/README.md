# Prérequis
Le portail des assos doit fonctionner sur http://localhost:8000/ pour que les exemples fonctionnent. Voir (https://simde.gitlab.utc.fr/documentation/#/portail/dev/installation)

# Installation
- Le projet utilise un client oauth2 PHP pour fonctionner: https://github.com/thephpleague/oauth2-client. La commande composer install permet de l'installer.
- Le fichier config.php contient la définition de l'objet Provider, selon la configuration, les paramètres peuvent changer.
- Pour utiliser Oauth, on utilise un client dans la base de données du portail, dans config.php, le client par défaut du portail est déjà configuré. 

# Bonnes pratiques
Plusieurs choses sont à garder à l'esprit lors de l'utilisation de oauth2. Le but ici est de centraliser les connexions et de mieux respecter la vie privée des utilisateurs.
- Afin de garantir l'intégrité des données, toutes les données pouvant être récupérées sur le portail doivent être redemandées à chaque utilisation car celles-ci peuvent changer.
- L'access token doit être enregistré dans la session et non dans les cookies car c'est le portail qui s'occupe de la connexion permanente (et ce seulement si l'utilisateur demande ce service).
- Sur l'exemple, le client utilisé demande un certain nombre de scopes. Vous devez veiller à utiliser le moins de scopes possibles et restreindre au maximum leur portée. En effet, l'utilisateur peut refuser que vous accediez à ses données si il trouve que vous en demandez trop.

# Elements non pris en compte dans l'exemple
Dans cet exemple, quelques éléments ne sont pas pris en compte ...
En effet, en utilisant des paramètres supplémentaires tels que la visibilité, les résultats fournis par le portail peuvent être modifiés
Par exemple, pour limiter le nombre de réponses du portail, pour les trier dans un ordre particulier ...
Cet exemple montre un client de type user (cas d'utilisation le plus commun) mais des clients de type client existent aussi et on des utilisations non restreintes à un seul utilisateur.
