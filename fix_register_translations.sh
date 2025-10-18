#!/bin/bash

echo "🔧 Correction des traductions dans Register.jsx..."

# Fichier à modifier
FILE="carre-premium-frontend/src/pages/Register.jsx"

# Créer une sauvegarde
cp "$FILE" "${FILE}.backup"

# Remplacer toutes les traductions
sed -i '' \
  -e "s/t('createAccount', 'Créer un Compte')/t('auth.createAccount')/g" \
  -e "s/t('registerSubtitle', 'Rejoignez Carré Premium et profitez d'avantages exclusifs')/t('auth.registerSubtitle')/g" \
  -e "s/t('personalInfo', 'Informations')/t('auth.personalInfo')/g" \
  -e "s/t('security', 'Sécurité')/t('auth.security')/g" \
  -e "s/t('firstName', 'Prénom')/t('auth.firstName')/g" \
  -e "s/t('lastName', 'Nom')/t('auth.lastName')/g" \
  -e "s/t('email', 'Email')/t('auth.email')/g" \
  -e "s/t('phone', 'Téléphone')/t('auth.phone')/g" \
  -e "s/t('dateOfBirth', 'Date de naissance')/t('auth.dateOfBirth')/g" \
  -e "s/t('gender', 'Genre')/t('auth.gender')/g" \
  -e "s/t('selectGender', 'Sélectionner')/t('auth.selectGender')/g" \
  -e "s/t('male', 'Homme')/t('auth.male')/g" \
  -e "s/t('female', 'Femme')/t('auth.female')/g" \
  -e "s/t('other', 'Autre')/t('auth.other')/g" \
  -e "s/t('country', 'Pays')/t('auth.country')/g" \
  -e "s/t('preferredLanguage', 'Langue préférée')/t('auth.preferredLanguage')/g" \
  -e "s/t('preferredCurrency', 'Devise préférée')/t('auth.preferredCurrency')/g" \
  -e "s/t('continue', 'Continuer')/t('auth.continue')/g" \
  -e "s/t('password', 'Mot de passe')/t('auth.password')/g" \
  -e "s/t('passwordStrength', 'Force du mot de passe')/t('auth.passwordStrength')/g" \
  -e "s/t('confirmPassword', 'Confirmer le mot de passe')/t('auth.confirmPassword')/g" \
  -e "s/t('acceptTerms', 'J'accepte les')/t('auth.acceptTerms')/g" \
  -e "s/t('termsAndConditions', 'Conditions d'utilisation')/t('auth.termsAndConditions')/g" \
  -e "s/t('and', 'et la')/t('auth.and')/g" \
  -e "s/t('privacyPolicy', 'Politique de confidentialité')/t('auth.privacyPolicy')/g" \
  -e "s/t('back', 'Retour')/t('auth.back')/g" \
  -e "s/t('creating', 'Création...')/t('auth.creatingAccount')/g" \
  -e "s/t('alreadyHaveAccount', 'Vous avez déjà un compte ?')/t('auth.alreadyHaveAccount')/g" \
  -e "s/t('login', 'Connectez-vous')/t('auth.loginLink')/g" \
  -e "s/t('whyJoinUs', 'Pourquoi Nous Rejoindre ?')/t('auth.whyJoinUs')/g" \
  -e "s/t('exclusiveOffers', 'Offres Exclusives')/t('auth.exclusiveOffers')/g" \
  -e "s/t('loyaltyPoints', 'Points de Fidélité')/t('auth.loyaltyPoints')/g" \
  -e "s/t('prioritySupport', 'Support Prioritaire')/t('auth.prioritySupport')/g" \
  "$FILE"

echo "✅ Traductions corrigées dans Register.jsx"
echo "📝 Sauvegarde créée: ${FILE}.backup"
