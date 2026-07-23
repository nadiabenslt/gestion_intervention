# Gestion des Interventions Informatiques – Agence Urbaine de Laâyoune (AUL)

Application web de gestion et de suivi des interventions techniques du service informatique de l'Agence Urbaine de Laâyoune (AUL), réalisée dans le cadre d'un stage pour l'obtention du diplôme de Technicien Spécialisé en Développement Digital Full Stack.

## 📋 Description

Avant la mise en place de cette solution, le traitement des demandes d'intervention informatique au sein de l'AUL souffrait de plusieurs limites :
- Absence d'un système centralisé pour enregistrer les demandes
- Difficulté de suivi des interventions en cours ou réalisées
- Manque de traçabilité des actions effectuées
- Absence de statistiques facilitant l'analyse de l'activité du service informatique

Cette application digitalise l'ensemble du processus : de la déclaration d'une demande d'intervention par un employé, jusqu'à sa prise en charge, son traitement et sa clôture par le service informatique, tout en assurant traçabilité, reporting et génération de statistiques.

## 🎯 Objectifs

- Permettre au personnel de déposer des demandes d'intervention
- Permettre au service informatique de recevoir et traiter ces demandes
- Assurer une meilleure organisation et un suivi structuré des interventions
- Faciliter la génération d'états, de statistiques et de graphiques
- Intégrer l'application comme module du système intranet de l'Agence

## ✨ Fonctionnalités principales

### Gestion des utilisateurs
- Deux interfaces distinctes : **Client** et **Administrateur/Technicien**
- Authentification obligatoire des utilisateurs
- Association de chaque client à un département

### Gestion des demandes d'intervention
- Enregistrement des demandes (date/heure, client, département, matériel concerné, description du problème)
- Suivi des demandes en attente et des interventions en cours
- Mise en évidence des interventions dépassant un délai de 120 heures
- Fiches de demande imprimables

### Gestion administrative
- Gestion des intervenants (techniciens du service informatique)
- Gestion des équipements informatiques et affectation aux salles
- Gestion des clients et des départements
- Saisie des diagnostics, actions réalisées et date de fin d'intervention

### États, rapports et statistiques
- Affichage des états globaux et par client / département / période / équipement
- Génération de statistiques et de graphiques d'analyse (par département, client, équipement/type)

## 👥 Acteurs du système

| Acteur | Rôle |
| --- | --- |
| **Employé (Client)** | Déclare des demandes d'intervention et suit leur avancement |
| **Responsable / Technicien** | Gère les clients, départements, équipements et salles ; traite les interventions ; édite les états et génère les statistiques |

## 🛠️ Technologies utilisées

**Back-end**
- PHP – logique serveur et traitement des données
- MySQL – base de données relationnelle

**Front-end**
- HTML5
- CSS3
- Bootstrap – framework CSS pour des interfaces responsives
- JavaScript

**Environnement de développement**
- Visual Studio Code
- XAMPP (Apache, MySQL, PHP)

**Conception**
- UML (diagramme de cas d'utilisation, diagramme de classes)

## 🖼️ Aperçu des interfaces

- Interface d'authentification
- Interfaces Client : page d'accueil, formulaire de demande d'intervention, fiche imprimable, choix du rôle
- Interfaces Administrateur/Technicien : tableau de bord, gestion des matériels/employés/départements, historique des interventions, recherche de clients, suivi des interventions (en cours / terminées), fiches d'intervention technique, liste des demandes

## 🚀 Perspectives d'évolution

- **Notifications en temps réel** : envoi automatique de mails/SMS aux techniciens lors de l'affectation d'une nouvelle urgence
- **Tableau de bord décisionnel** : graphiques avancés pour analyser les pannes les plus fréquentes et anticiper la maintenance préventive
- **Version mobile** : interface responsive avancée ou application hybride pour valider les interventions directement sur le terrain

## 📍 Contexte du projet

- **Établissement d'accueil :** Agence Urbaine de Laâyoune (AUL)
- **Période de stage :** Janvier 2026
- **Diplôme visé :** Technicien Spécialisé en Développement Digital Full Stack
- **Réalisé par :** Bensaltana Nadia
- **Encadrant pédagogique :** M. Elmahfoudi Salah
- **Encadrants professionnels :** M. Karimi Abderrahmane, M. Abdellah El Fadeli
- **Année universitaire :** 2025/2026

## 📄 Licence

Projet académique réalisé dans le cadre d'un stage de fin de formation.
