# Documentation — Glossaire wp-plugin-base

Termes et concepts clarifiés lors de la mise en pratique du workflow wp-plugin-base sur le plugin **Post Edit Link**.

---

## gh (GitHub CLI)

`gh` est l'outil en ligne de commande officiel de GitHub. Il permet d'interagir avec GitHub directement depuis le terminal, sans passer par l'interface web :

- Créer et gérer des repos (`gh repo create`)
- Ouvrir, lister et merger des PR (`gh pr list`, `gh pr merge`)
- Déclencher des workflows (`gh workflow run`)
- Consulter les releases (`gh release view`)
- S'authentifier (`gh auth login`)

Installation : `scoop install gh` (Windows) ou `brew install gh` (macOS).

---

## .wp-plugin-base.env

Fichier de configuration situé à la racine du projet. Il déclare l'identité du plugin et les paramètres utilisés par la fondation wp-plugin-base pour générer les workflows CI, les fichiers de release et le packaging.

Exemple :

```env
FOUNDATION_RELEASE_SOURCE_PROVIDER=github-release
FOUNDATION_RELEASE_SOURCE_REFERENCE=MatthiasReinholz/wp-plugin-base
FOUNDATION_VERSION=v1.10.0
PLUGIN_NAME="Post Edit Link"
PLUGIN_SLUG=post-edit-link-block
MAIN_PLUGIN_FILE=post-edit-link-block.php
README_FILE=readme.txt
ZIP_FILE=post-edit-link-block.zip
PHP_VERSION=7.4
NODE_VERSION=22
AUTOMATION_PROVIDER=github
```

---

## Clés obligatoires de .wp-plugin-base.env

Le fichier `.wp-plugin-base.env` requiert 10 clés obligatoires :

| Clé | Rôle |
|-----|------|
| `FOUNDATION_RELEASE_SOURCE_PROVIDER` | Source de la fondation (ex. `github-release`) |
| `FOUNDATION_RELEASE_SOURCE_REFERENCE` | Repo de la fondation (ex. `MatthiasReinholz/wp-plugin-base`) |
| `FOUNDATION_VERSION` | Version de la fondation utilisée (ex. `v1.10.0`) |
| `PLUGIN_NAME` | Nom lisible du plugin |
| `PLUGIN_SLUG` | Identifiant technique (nom du dossier) |
| `MAIN_PLUGIN_FILE` | Fichier PHP principal du plugin |
| `README_FILE` | Fichier readme WordPress (ex. `readme.txt`) |
| `ZIP_FILE` | Nom du ZIP de distribution |
| `PHP_VERSION` | Version PHP minimale requise |
| `NODE_VERSION` | Version Node.js pour le build |

La clé `AUTOMATION_PROVIDER=github` est ajoutée pour activer les workflows GitHub Actions.

---

## PR (Pull Request)

Une **Pull Request** est une demande de fusion de code sur GitHub. Au lieu de pousser directement sur la branche `main`, on crée une branche séparée puis on ouvre une PR qui propose d'intégrer les changements. Cela permet de :

- **Relire** les modifications avant de les accepter
- **Lancer la CI** (tests, validations) automatiquement sur les changements proposés
- **Discuter** via des commentaires si besoin
- **Garder un historique** de pourquoi et comment un changement a été intégré

Dans le workflow wp-plugin-base, le workflow `prepare-release` crée automatiquement une PR de release. Une fois mergée (fusionnée dans `main`), le workflow `finalize-release` se déclenche.

---

## Attestation de provenance (Sigstore)

L'attestation de provenance est un mécanisme de sécurité intégré à GitHub Actions. Quand le workflow construit le ZIP du plugin, GitHub génère un **certificat cryptographique** (via Sigstore) qui prouve :

- Que le ZIP a été construit par **ce workflow**, dans **ce repo**, à partir de **ce commit** précis
- Que le contenu n'a pas été altéré entre la construction et le téléchargement

Cela crée une **chaîne de confiance** : code source → CI → artefact signé.

Le fichier `.sigstore.json` attaché à chaque release contient cette preuve vérifiable.

**Limitation** : cette fonctionnalité n'est disponible que pour les repos publics sur les comptes personnels GitHub. Les organisations payantes y ont accès en privé.

---

## Flux de release wp-plugin-base — Résumé

```
1. prepare-release (workflow_dispatch)
   → Crée la branche release/x.y.z
   → Met à jour les métadonnées de version
   → Génère le changelog
   → Ouvre une PR vers main

2. Revue et merge de la PR
   → Vérification humaine des changements
   → Merge dans main

3. finalize-release (automatique au merge)
   → Valide les versions et le code (lint PHP/JS)
   → Construit le ZIP de distribution
   → Génère le SBOM (inventaire des composants)
   → Signe le package (Sigstore)
   → Atteste la provenance
   → Crée le tag annoté x.y.z
   → Publie la GitHub Release avec le ZIP téléchargeable
```

Le workflow `release.yml` (manuel) sert uniquement à **réparer** une release existante si un canal de distribution a échoué.
