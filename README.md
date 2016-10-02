<img src="https://cdn.rawgit.com/juliendargelos/BetterSched2/master/assets/logo-brand.svg" alt="BetterSched'"/>

---

<p align="right"><a href="https://codeclimate.com/github/juliendargelos/BetterSched2"><img src="https://codeclimate.com/github/juliendargelos/BetterSched2/badges/gpa.svg" alt="Code Climate"/></a></p>
> *BetterSched' fournit une interface élégante et intuitive aux étudiants de Bordeaux qui souhaitent
> consulter leur emploi du temps.*

Ce repository concerne la deuxième version de BetterSched', l'application est actuellement en beta développement.

<a href="https://github.com/juliendargelos/BetterSched2/blob/master/preview/desktop-login.png"><img width="441" src="https://raw.githubusercontent.com/juliendargelos/BetterSched2/master/preview/desktop-login.png" alt="BetterSched' login screen"/></a>
<a href="https://github.com/juliendargelos/BetterSched2/blob/master/preview/desktop-schedule.png"><img width="441" src="https://raw.githubusercontent.com/juliendargelos/BetterSched2/master/preview/desktop-schedule.png" alt="BetterSched' schedule screen"/></a>

## Principe de fonctionnement
BetterSched' joue le rôle de proxy entre un client et un serveur web Satellys. Il normalise le format des requêtes, et les transforme pour être compris par Satellys. Quand le serveur répond, BetterSched' se charge de mettre correctement en forme les données reçues.

## Nouveautés
- Avec cette nouvelle version, BetterSched' désigne deux entités différentes:
  - L'API BetterSched' qui fait l'intermédiaire avec Satellys en simplifiant le format des requêtes et en produisant des réponses organisées en JSON.
  - L'application BetterSched' qui fait usage de l'API pour générer une mise en forme élégante à partir des informations obtenues.

- L'application fonctionne désormais selon le schéma MVC qui donne plus de clarté au projet.

- La version 2 a bénéficié d'une optimisation en profondeur des échanges avec Satellys. Les temps de chargement ont ainsi été réduits de façon substantielle.

- BetterSched' a également été optimisé dans les échanges directs entre le client et le serveur de l'application:
  - Le nombre et le poids des assets ont été réduits au minimum.
  - Les échanges avec BetterSched' sont majoritairement asychrones et évitent ainsi le téléchargement de fichiers inutiles par le client.
  - Il est prévu d'exploiter le cache pour diminuer encore la quantité de données téléchargées par le client.

- Déployement de l'application sur Heroku.

## API publique
<sub>URL (HTTP)</sub> | <sub>Méthode</sub> | <sub>Paramètres</sub> | <sub>Réponse</sub> | <sub>Description</sub>
-----------|---------|------------|---------|-------------
<sub>/api/login</sub> | <sub>`POST`</sub> | <sub>`username`<br>`password`<br>`institute`</sub> | <sub>`{`<br>&nbsp;&nbsp;&nbsp;`"status":`&nbsp;`true,`<br>&nbsp;&nbsp;&nbsp;` "message":`&nbsp;`"Connexion réussie"`<br>`}`</sub> | <sub>Ouvre une session utilisateur sur le serveur BetterSched'. Cette étape est essentielle pour pouvoir récupérer des données ultérieurement. Les identifiants sont identiques à ceux de Satellys, le paramètre `institute` doit être égale au nom de l'[une des filles](https://github.com/juliendargelos/BetterSched2/tree/master/app/Api) de la classe `BetterSched\Api` (en ommentant le namespace). `status` prendra la valeur `true` en cas de succès et `false` en cas d'échec.</sub>
<sub>/api/logged</sub> | <sub>`GET`</sub> | | <sub>`{`<br>&nbsp;&nbsp;&nbsp;`"status:`&nbsp;`true`<br>`}`</sub> | <sub>Indique si une session est ouverte sur le serveur BetterSched'. `status` prendra la valeur `true` si c'est le cas et `false` sinon.</sub>
<sub>/api/logout</sub> | <sub>`GET`</sub> | | <sub>`{`<br>&nbsp;&nbsp;&nbsp;`"status":`&nbsp;`true,`<br>&nbsp;&nbsp;&nbsp;` "message":`&nbsp;`"Vous avez été déconnecté"`<br>`}`</sub> | <sub>Ferme la session sur le serveur BetterSched'. `status` prendra la valeur `true` si une session était ouverte et `false` sinon.</sub>
<sub>/api/sched/`year`/`week`/`group`</sub><br><sub>/api/sched/`year`/`week`/`group`/`filter`</sub> | <sub>`GET`</sub> | | <sub>`{`<br>&nbsp;&nbsp;&nbsp;`"status":`&nbsp;`true,`<br>&nbsp;&nbsp;&nbsp;` "sched":`&nbsp;`{`<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`"stats":`&nbsp;`{`...`}`<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`"sched":`&nbsp;`{`...`}`<br>&nbsp;&nbsp;&nbsp;`}`<br>`}`</sub> | <sub>Retourne l'emploi du temps de l'établissement indiqué lors de la connexion en fonction de l'année `year`, la semaine `week` (ISO-8601) et la filière `group` dont les différentes valeurs sont clés du tableau statique `$groups` pour chacune des classe [API](https://github.com/juliendargelos/BetterSched2/tree/master/app/Api). `status` prend la valeur `true` en cas de succès et `false` sinon. `stats` regroupe des statistiques sur l'emploi du temps (sera probablement écarté en version finale, tout comme le paramètre d'url `filter`), `days` est un objet dont les clés sont les jours de la semaine en français, chacune fait référence à un tableau de cours. Je vous invite a executer vous même la requête pour observer la structure afin que ceci reste lisible.</sub>
