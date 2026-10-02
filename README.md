# Réservation d’hôtels : classification des chambres

Prototype éducatif qui montre le flux HTML → JavaScript `fetch()` → API PHP → classe PHP → fichier JSON, puis l’affichage et le filtrage des chambres par catégorie. Il n’utilise aucune base de données externe.

## Démarrer

Depuis la racine du projet, lancez le serveur PHP intégré :

```sh
php -S localhost:8000
```

Ouvrez ensuite <http://localhost:8000/frontend/>. N’ouvrez pas le fichier avec `file://`, car le navigateur doit pouvoir appeler l’API PHP.

L’API est disponible à <http://localhost:8000/backend/api/chambres.php>. Les chambres sont enregistrées dans `backend/data/chambres.json`, initialement vide (`[]`).
