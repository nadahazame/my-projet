# Site de Réservation d'Hôtel

Un système de réservation d'hôtel complet développé en PHP avec MySQL.

## Fonctionnalités

- Inscription et connexion des utilisateurs
- Gestion des chambres et des réservations
- Interface d'administration complète
- Système de réservation en ligne
- Gestion des utilisateurs et des rôles
- Notifications par email

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache, Nginx, etc.)
- Composer (pour les dépendances)

## Installation

1. Clonez le dépôt :
```bash
git clone [url-du-repo]
cd hotel-reservation
```

2. Créez une base de données MySQL :
```sql
CREATE DATABASE hotel_db;
```

3. Importez le schéma de la base de données :
```bash
mysql -u root -p hotel_db < database.sql
```

4. Configurez la connexion à la base de données :
   - Ouvrez le fichier `includes/config.php`
   - Modifiez les paramètres de connexion selon votre configuration

5. Configurez le serveur web :
   - Pointez le document root vers le dossier du projet
   - Assurez-vous que PHP a les permissions nécessaires

## Structure du Projet

```
hotel-reservation/
├── admin/
│   ├── dashboard.php
│   ├── rooms.php
│   ├── reservations.php
│   └── users.php
├── client/
│   └── dashboard.php
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── css/
│   └── style.css
├── js/
│   └── main.js
├── index.php
├── login.php
├── register.php
├── rooms.php
├── reservation.php
└── database.sql
```

## Utilisation

1. Accès au site :
   - Ouvrez votre navigateur et accédez à l'URL du site
   - Créez un compte utilisateur ou connectez-vous

2. Réservation de chambre :
   - Parcourez les chambres disponibles
   - Sélectionnez une chambre et les dates souhaitées
   - Confirmez votre réservation

3. Administration :
   - Connectez-vous avec un compte administrateur
   - Accédez au tableau de bord administrateur
   - Gérez les chambres, les réservations et les utilisateurs

## Compte Administrateur par Défaut

- Email : admin@hoteldeluxe.fr
- Mot de passe : password

## Sécurité

- Toutes les entrées utilisateur sont validées et nettoyées
- Les mots de passe sont hashés avec password_hash()
- Protection contre les injections SQL avec des requêtes préparées
- Sessions sécurisées
- Protection CSRF sur les formulaires

## Maintenance

- Sauvegardez régulièrement la base de données
- Mettez à jour les dépendances
- Surveillez les logs d'erreur
- Effectuez des tests de sécurité réguliers

## Support

Pour toute question ou problème, veuillez créer une issue dans le dépôt GitHub.

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails. 