# Manuel Utilisateur - Système de Gestion de Stock

## Table des matières

1. [Introduction](#introduction)
2. [Premiers pas](#premiers-pas)
3. [Gestion des produits](#gestion-des-produits)
4. [Gestion des mouvements de stock](#gestion-des-mouvements-de-stock)
5. [Gestion des inventaires](#gestion-des-inventaires)
6. [Système d'alertes](#système-dalertes)
7. [Prédictions et analyses](#prédictions-et-analyses)
8. [Exports et rapports](#exports-et-rapports)
9. [API REST](#api-rest)
10. [Dépannage](#dépannage)

---

## Introduction

### Présentation du système

Le Système de Gestion de Stock est une application web complète conçue pour optimiser la gestion des stocks dans les entreprises. Elle offre une interface intuitive, des prédictions intelligentes et des alertes automatiques pour maintenir un niveau de stock optimal.

### Fonctionnalités principales

- **Gestion des produits** : Création, modification, suppression avec suivi des stocks
- **Mouvements de stock** : Suivi détaillé des entrées et sorties
- **Inventaires** : Gestion complète des inventaires périodiques
- **Alertes automatiques** : Notifications pour stocks faibles, ruptures, expirations
- **Prédictions** : Algorithmes ML pour anticiper les besoins
- **Dashboard** : Vue d'ensemble avec graphiques et statistiques
- **API REST** : Intégration complète avec documentation Swagger
- **Exports** : Génération de rapports PDF, CSV, Excel

### Configuration requise

- **Navigateur** : Chrome, Firefox, Safari, Edge (versions récentes)
- **Résolution** : Minimum 1024x768, recommandé 1920x1080
- **Connexion** : Internet stable pour l'accès web

---

## Premiers pas

### Connexion au système

1. Ouvrez votre navigateur web
2. Accédez à l'URL de l'application
3. Entrez vos identifiants :
   - **Email** : votre adresse email professionnelle
   - **Mot de passe** : votre mot de passe sécurisé
4. Cliquez sur "Se connecter"

### Interface principale

L'interface se compose de :

- **Barre de navigation** : Accès rapide aux principales sections
- **Dashboard** : Vue d'ensemble avec indicateurs clés
- **Menu latéral** : Navigation détaillée par fonctionnalités
- **Zone de contenu** : Contenu principal de chaque section

### Première configuration

1. **Créer des catégories** : Organisez vos produits par catégories
2. **Ajouter des produits** : Saisissez votre catalogue de produits
3. **Configurer les seuils** : Définissez les stocks minimum et optimal
4. **Importer les données** : Utilisez les imports CSV si nécessaire

---

## Gestion des produits

### Ajouter un produit

1. Allez dans **Produits** → **Nouveau produit**
2. Remplissez les informations obligatoires :
   - **Nom** : Nom du produit (ex: "Laptop Pro 15\"")
   - **Stock minimum** : Quantité minimale alerte
   - **Stock optimal** : Quantité cible
   - **Stock actuel** : Quantité disponible
3. Complétez les informations optionnelles :
   - **Description** : Détails du produit
   - **Code barres** : Code EAN/UPC
   - **Fournisseur** : Nom du fournisseur
   - **Prix** : Prix unitaire
   - **Catégorie** : Classification du produit
   - **Date d'expiration** : Pour les produits périssables
4. Cliquez sur **Enregistrer**

### Modifier un produit

1. Allez dans **Produits** → **Liste des produits**
2. Recherchez le produit (nom, code barres, catégorie)
3. Cliquez sur l'icône ✏️ (modifier)
4. Modifiez les champs nécessaires
5. Cliquez sur **Mettre à jour**

### Supprimer un produit

1. Sélectionnez le produit dans la liste
2. Cliquez sur l'icône 🗑️ (supprimer)
3. Confirmez la suppression

⚠️ **Attention** : La suppression est irréversible et affecte l'historique des mouvements.

### Recherche et filtrage

#### Recherche simple
- Utilisez la barre de recherche en haut
- Tapez le nom, code barres ou fournisseur
- Les résultats s'affichent en temps réel

#### Filtres avancés
- **Catégorie** : Filtrez par catégorie de produit
- **Stock faible** : Affiche les produits sous le seuil minimum
- **Surstock** : Affiche les produits au-dessus du seuil optimal
- **Expiration proche** : Produits expirant dans les 30 jours
- **Tri** : Par nom, prix, stock, date de création

#### Exemples de recherche

| Recherche | Résultat |
|-----------|----------|
| "laptop" | Tous les produits contenant "laptop" |
| "123456789" | Produit avec ce code barres |
| Catégorie: "Informatique" | Tous les produits informatiques |
| Stock faible | Produits nécessitant un réapprovisionnement |

---

## Gestion des mouvements de stock

### Types de mouvements

- **Entrée (IN)** : Réception de marchandise, retour client
- **Sortie (OUT)** : Vente, perte, casse, transfert

### Enregistrer un mouvement

1. Allez dans **Mouvements** → **Nouveau mouvement**
2. Sélectionnez le **produit** concerné
3. Choisissez le **type** (Entrée/Sortie)
4. Saisissez la **quantité**
5. Sélectionnez la **raison** :
   - Pour les entrées : "Nouvelle livraison", "Retour client", "Correction stock"
   - Pour les sorties : "Vente", "Perte", "Casse", "Transfert"
6. Ajoutez une **note** (optionnel)
7. Confirmez la **date** du mouvement
8. Cliquez sur **Enregistrer**

### Vérification automatique

Le système vérifie automatiquement :
- **Stock suffisant** pour les sorties
- **Alertes** si le stock passe sous le minimum
- **Mise à jour** du stock actuel du produit

### Historique des mouvements

1. Allez dans **Mouvements** → **Historique**
2. Utilisez les filtres pour affiner la recherche :
   - **Produit** : Mouvements d'un produit spécifique
   - **Type** : Entrées ou sorties uniquement
   - **Période** : Entre deux dates
   - **Raison** : Par type de mouvement
3. **Export** : Téléchargez l'historique en CSV/Excel

### Exemples pratiques

#### Réception d'une livraison
```
Type: Entrée
Produit: Laptop Pro 15"
Quantité: 10
Raison: Nouvelle livraison
Note: Livraison fournisseur TechSupplier - Facture #12345
```

#### Vente au client
```
Type: Sortie
Produit: Laptop Pro 15"
Quantité: 2
Raison: Vente
Note: Vente client ABC - Commande #67890
```

#### Correction d'inventaire
```
Type: Entrée
Produit: Laptop Pro 15"
Quantité: 3
Raison: Correction stock
Note: Erreur de comptage lors du dernier inventaire
```

---

## Gestion des inventaires

### Créer un inventaire

1. Allez dans **Inventaires** → **Nouvel inventaire**
2. Remplissez les informations :
   - **Date** : Date de réalisation de l'inventaire
   - **Note** : Description (ex: "Inventaire mensuel décembre")
3. Cliquez sur **Créer**

### Ajouter des lignes d'inventaire

1. Sélectionnez l'inventaire créé
2. Cliquez sur **Ajouter une ligne**
3. Pour chaque produit :
   - **Produit** : Sélectionnez dans la liste
   - **Quantité théorique** : Stock attendu selon le système
   - **Quantité réelle** : Stock compté physiquement
   - **Note** : Observations (produit endommagé, périmé, etc.)
4. Le système calcule automatiquement la **différence**

### Exemple de saisie

| Produit | Théorique | Réel | Différence | Note |
|---------|-----------|------|------------|------|
| Laptop Pro 15" | 50 | 45 | -5 | 2 unités endommagées |
| Moniteur 27" | 30 | 32 | +2 | Erreur de comptage précédent |
| Clavier USB | 100 | 95 | -5 | 5 claviers manquants |

### Valider et fermer l'inventaire

1. Vérifiez toutes les lignes saisies
2. Cliquez sur **Fermer l'inventaire**
3. Le système :
   - Met à jour les stocks réels
   - Crée les mouvements de régularisation
   - Génère un rapport PDF
   - Envoie des alertes si nécessaire

### Bonnes pratiques

- **Préparation** : Imprimez la liste des produits avant l'inventaire
- **Organisation** : Procédez par zone/catégorie
- **Double vérification** : Faites valider par une deuxième personne
- **Documentation** : Notez toutes les anomalies
- **Régularité** : Planifiez des inventaires périodiques (mensuel/trimestriel)

---

## Système d'alertes

### Types d'alertes

#### Alertes de stock
- **Stock faible** : Stock < stock minimum
- **Rupture imminente** : Stock critique
- **Surstock** : Stock > stock optimal

#### Alertes de péremption
- **Expiration proche** : Produit expirant dans 30 jours
- **Produit périmé** : Date d'expiration dépassée

#### Alertes système
- **Inventaire requis** : Délai d'inventaire dépassé
- **Mouvement anormal** : Variations inhabituelles

### Consulter les alertes

1. Allez dans **Alertes** → **Liste des alertes**
2. Les alertes sont classées par :
   - **Niveau** : Critical (rouge) > Warning (orange) > Info (bleu)
   - **Date** : Plus récentes en premier
   - **Lecture** : Non lues en premier

### Filtrer les alertes

| Filtre | Utilisation |
|--------|-------------|
| Non lues | Alertes à traiter en priorité |
| Critical | Urgences absolues |
| Warning | Vigilance requise |
| Type | Par catégorie d'alerte |
| Période | Alertes récentes |

### Traiter une alerte

1. **Analysez l'alerte** : Comprenez la cause
2. **Prenez action** :
   - **Stock faible** : Lancez une commande
   - **Surstock** : Planifiez une promotion
   - **Expiration** : Vente rapide ou destruction
3. **Marquez comme lue** : Cliquez sur l'alerte → "Marquer comme lue"
4. **Supprimez** si résolue : Cliquez sur l'icône 🗑️

### Exemples d'actions

#### Alerte "Stock faible"
```
Message: Stock faible pour Laptop Pro 15": 3 unités (minimum: 10)
Action: Commander immédiatement 20 unités
Statut: En cours de traitement
```

#### Alerte "Expiration proche"
```
Message: Produit expirant bientôt: Yaourts expire dans 5 jours
Action: Lancer promotion "Vente flash"
Statut: Résolu
```

### Configuration des alertes

Les seuils d'alertes sont configurables par produit :
- **Stock minimum** : Déclenche l'alerte "stock faible"
- **Stock critique** : Déclenche l'alerte "rupture imminente"
- **Stock optimal** : Déclenche l'alerte "surstock"

---

## Prédictions et analyses

### Accès aux prédictions

1. Allez dans **Prédictions** → **Tableau de bord**
2. Choisissez les paramètres :
   - **Période** : 7, 30 ou 90 jours
   - **Algorithme** : Automatique, Linéaire, Moyenne mobile, ML
   - **Jours moyenne mobile** : Pour l'algorithme de moyenne mobile

### Types d'algorithmes

#### Régression linéaire
- **Utilisation** : Tendance stable et prévisible
- **Précision** : Moyenne
- **Rapidité** : Très rapide

#### Moyenne mobile
- **Utilisation** : Données avec variations saisonnières
- **Précision** : Bonne pour court terme
- **Rapidité** : Rapide

#### Machine Learning (ML)
- **Utilisation** : Données complexes et volumineuses
- **Précision** : Élevée
- **Rapidité** : Plus lent

### Interprétation des prédictions

#### Graphique de prédictions
- **Ligne bleue** : Prédictions de stock
- **Ligne grise** : Tendance réelle
- **Zone ombrée** : Intervalle de confiance
- **Points rouges** : Risques de rupture

#### Indicateurs clés
- **Confiance** : Fiabilité de la prédiction (0-100%)
- **Tendance** : Hausse (↑), Baisse (↓), Stable (→)
- **Volatilité** : Stabilité des prédictions

### Recommandations automatiques

Le système génère des recommandations basées sur :

#### Analyse des risques
- **Rupture imminente** : "Commandez immédiatement X unités"
- **Tendance baissière** : "Surveillez les ventes"
- **Surstock** : "Planifiez une promotion"

#### Suggestions d'optimisation
- **Quantité optimale** : Basée sur les prédictions
- **Timing idéal** : Moment de commander
- **Prix cible** : Pour les promotions

### Exemple d'analyse

```
Prédictions pour Laptop Pro 15" (prochains 7 jours)
- Algorithme: Machine Learning
- Confiance moyenne: 85%
- Tendance: Baisse modérée (-12%)

Recommandations:
⚠️ Risque de rupture dans 4 jours
📦 Commandez 15 unités cette semaine
📊 Tendance à la baisse - surveillez les ventes
```

---

## Exports et rapports

### Types d'exports disponibles

#### Exports CSV
- **Stock complet** : Tous les produits avec stocks actuels
- **Mouvements** : Historique détaillé des mouvements
- **Inventaires** : Résultats d'inventaires
- **Alertes** : Liste des alertes actives

#### Exports Excel (.xlsx)
- **Rapport de stock** : Avec graphiques intégrés
- **Analyse des mouvements** : Tableaux croisés dynamiques
- **Bilan mensuel** : Synthèse complète

#### Exports PDF
- **Fiche produit** : Informations complètes d'un produit
- **Rapport d'inventaire** : Document officiel
- **Synthèse mensuelle** : Rapport de direction

### Générer un export

1. Allez dans **Exports** → **Choisir le type**
2. Configurez les options :
   - **Période** : Dates de début/fin
   - **Filtres** : Catégories, produits, types
   - **Format** : CSV, Excel, PDF
3. Cliquez sur **Générer**
4. Téléchargez le fichier généré

### Exports personnalisés

#### Export de stock filtré
```
Filtres:
- Catégorie: Informatique
- Stock faible: Oui
- Format: Excel

Résultat: Liste des produits informatiques en stock faible
```

#### Export de mouvements par période
```
Filtres:
- Période: 01/01/2024 - 31/01/2024
- Type: Sorties uniquement
- Format: CSV

Résultat: Mouvements de sortie du mois de janvier
```

### Automatisation des exports

#### Planification
- **Quotidien** : Export des mouvements du jour
- **Hebdomadaire** : Rapport de stock complet
- **Mensuel** : Bilan et analyses

#### Destinataires
- **Email automatique** : Envoi aux responsables
- **Stockage cloud** : Sauvegarde automatique
- **Intégration ERP** : Synchronisation avec autres systèmes

### Exemples de rapports

#### Rapport de stock mensuel
```
Période: Décembre 2024
Produits totaux: 1,247
Valeur stock: €125,430
Stock faible: 23 produits
Ruptures: 3 produits
Mouvements: 3,456 entrées, 2,890 sorties
```

#### Analyse des ventes
```
Top 5 produits vendus:
1. Laptop Pro 15" - 156 unités
2. Moniteur 27" - 98 unités
3. Clavier USB - 234 unités
4. Souris sans fil - 189 unités
5. Webcam HD - 67 unités
```

---

## API REST

### Présentation

L'API REST permet l'intégration du système de gestion de stock avec d'autres applications. Elle est entièrement documentée avec Swagger/OpenAPI 3.0.

### Accès à l'API

- **URL de base** : `https://votreserveur.com/api/v1`
- **Documentation** : `https://votreserveur.com/api/docs`
- **Authentification** : Token Bearer (JWT)

### Authentification

#### Obtenir un token
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "votremotdepasse"
}
```

#### Réponse
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### Utiliser le token
```bash
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Endpoints principaux

#### Produits
```bash
GET    /api/v1/products           # Lister les produits
POST   /api/v1/products           # Créer un produit
GET    /api/v1/products/{id}      # Détail d'un produit
PUT    /api/v1/products/{id}      # Modifier un produit
DELETE /api/v1/products/{id}      # Supprimer un produit
GET    /api/v1/select/products    # Liste pour autocomplete
```

#### Mouvements
```bash
GET  /api/v1/movements            # Historique des mouvements
POST /api/v1/movements            # Créer un mouvement
GET  /api/v1/movements/{id}       # Détail d'un mouvement
```

#### Inventaires
```bash
GET    /api/v1/inventories        # Lister les inventaires
POST   /api/v1/inventories        # Créer un inventaire
GET    /api/v1/inventories/{id}   # Détail inventaire
POST   /api/v1/inventories/{id}/close # Fermer inventaire
```

#### Alertes
```bash
GET    /api/v1/alerts             # Lister les alertes
POST   /api/v1/alerts/{id}/read   # Marquer comme lue
DELETE /api/v1/alerts/{id}        # Supprimer une alerte
```

### Exemples d'utilisation

#### Lister les produits avec filtres
```bash
GET /api/v1/products?low_stock=true&category_id=5&per_page=20
```

#### Créer un mouvement
```bash
POST /api/v1/movements
Content-Type: application/json
Authorization: Bearer {token}

{
  "product_id": 123,
  "type": "out",
  "reason": "Vente",
  "quantity": 2,
  "moved_at": "2024-01-15T10:30:00Z",
  "note": "Vente client ABC"
}
```

#### Obtenir le dashboard
```bash
GET /api/v1/dashboard/summary
Authorization: Bearer {token}
```

### Gestion des erreurs

#### Codes d'erreur
- **200** : Succès
- **201** : Créé avec succès
- **400** : Requête invalide
- **401** : Non authentifié
- **403** : Permission refusée
- **404** : Ressource non trouvée
- **422** : Erreur de validation
- **500** : Erreur serveur

#### Format des erreurs
```json
{
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required."],
    "price": ["The price must be greater than 0."]
  }
}
```

### Limites et quotas

- **Requêtes/minute** : 1000 par utilisateur
- **Taille des fichiers** : 10MB maximum
- **Pagination** : 100 résultats maximum par page
- **Timeout** : 30 secondes par requête

---

## Dépannage

### Problèmes courants

#### Connexion impossible
**Symptôme** : Message "Identifiants incorrects"
**Solutions** :
1. Vérifiez l'email et le mot de passe
2. Activez les cookies dans votre navigateur
3. Contactez l'administrateur pour réinitialiser le mot de passe

#### Page blanche ou erreur 500
**Symptôme** : Page qui ne se charge pas
**Solutions** :
1. Rafraîchissez la page (F5)
2. Videz le cache du navigateur
3. Essayez un autre navigateur
4. Vérifiez votre connexion internet

#### Mouvement refusé
**Symptôme** : "Stock insuffisant"
**Solutions** :
1. Vérifiez le stock actuel du produit
2. Corrigez la quantité si nécessaire
3. Faites une entrée de stock avant la sortie

#### Alertes non reçues
**Symptôme** : Pas d'alertes pour stocks faibles
**Solutions** :
1. Vérifiez les seuils de stock minimum
2. Assurez-vous que les stocks sont à jour
3. Vérifiez les filtres d'affichage

### Performance

#### Lenteur de l'application
**Causes possibles** :
- Trop de données affichées
- Connexion internet lente
- Navigateur ancien
- Pic de trafic serveur

**Solutions** :
- Utilisez les filtres pour réduire les données
- Améliorez votre connexion
- Mettez à jour votre navigateur
- Essayez pendant les heures creuses

#### Export très lent
**Solutions** :
- Réduisez la période d'export
- Utilisez des filtres spécifiques
- Essayez le format CSV plus rapide
- Planifiez les exports volumineux la nuit

### Données incorrectes

#### Stock incohérent
**Symptôme** : Stock affiché ≠ Stock réel
**Solutions** :
1. Faites un inventaire de correction
2. Vérifiez les derniers mouvements
3. Annulez les mouvements erronés
4. Mettez à jour manuellement si nécessaire

#### Mouvements manquants
**Symptôme** : Mouvement non enregistré
**Solutions** :
1. Vérifiez l'historique complet
2. Filtrez par période plus large
3. Vérifiez les filtres actifs
4. Créez un mouvement de correction

### Support technique

#### Quand contacter le support
- Erreurs répétées
- Problèmes de performance
- Questions sur l'utilisation
- Demandes de fonctionnalités

#### Informations à fournir
- **Capture d'écran** de l'erreur
- **Navigateur** et version utilisés
- **Description détaillée** du problème
- **Heure** de survenue
- **Actions** menées avant l'erreur

#### Contact
- **Email** : support@votreentreprise.com
- **Téléphone** : +33 1 23 45 67 89
- **Horaires** : Lundi-Vendredi 9h-18h

---

## Glossaire

| Terme | Définition |
|-------|------------|
| **Stock minimum** | Quantité minimale avant alerte |
| **Stock optimal** | Quantité cible de stock |
| **Mouvement** | Entrée ou sortie de stock |
| **Inventaire** : Comptage physique du stock |
| **Alerte** : Notification automatique |
| **Prédiction** : Estimation de besoins futurs |
| **API** : Interface de programmation |
| **Dashboard** : Tableau de bord |

---

## FAQ

**Q : Puis-je accéder au système sur mobile ?**
R : Oui, l'interface est responsive et fonctionne sur smartphones et tablettes.

**Q : Comment importer des produits en masse ?**
R : Utilisez le format CSV avec le modèle disponible dans la section Imports.

**Q : Les alertes sont-elles envoyées par email ?**
R : Oui, configurez vos préférences dans votre profil pour recevoir les alertes par email.

**Q : Puis-je exporter l'historique complet ?**
R : Oui, utilisez les filtres de période pour exporter les données souhaitées.

**Q : Comment sont calculées les prédictions ?**
R : Plusieurs algorithmes sont utilisés automatiquement selon le volume de données.

**Q : Le système fonctionne-t-il hors ligne ?**
R : Non, une connexion internet est requise pour accéder à l'application.

---

## Mises à jour et formation

### Nouvelles fonctionnalités
Consultez régulièrement la section "Nouveautés" pour découvrir les dernières améliorations.

### Formation continue
Des webinaires sont organisés mensuellement pour présenter les nouvelles fonctionnalités et les meilleures pratiques.

### Documentation technique
Pour les développeurs, la documentation API complète est disponible sur `/api/docs`.

---

*Version 1.0 - Dernière mise à jour : Décembre 2024*
