# Données Statiques à Remplacer dans HomeModern.jsx

## Lignes Identifiées avec Données Statiques

1. **Ligne 411-440** : Section "Événements à Venir" avec 4 événements statiques
   - Roland Garros 2025
   - Champions League Finale  
   - Formule 1 Monaco
   - Concert Burna Boy

2. **Ligne 526-545** : Section "Packages VIP" avec 3 packages statiques
   - Package Hélicoptère
   - Jet Privé Paris
   - Safari Luxe Kenya

3. **Ligne 1053** : Section Gallery avec "Roland Garros" statique

## Solution

Remplacer ces tableaux statiques par :
- `events.slice(0, 4)` pour les événements
- `packages.slice(0, 3)` pour les packages
- Utiliser les données chargées depuis l'API
