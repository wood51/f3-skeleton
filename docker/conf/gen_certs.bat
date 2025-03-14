@echo off
setlocal enabledelayedexpansion

:: Forcer l'utilisation de l'encodage UTF-8
chcp 65001 >nul

:: Variables de configuration
set DOMAIN=localhost
set CERT_DIR=certs

:: Vérifier si mkcert est installé
where mkcert >nul 2>&1
if %errorlevel% neq 0 (
    echo "mkcert n'est pas installé. Installer-le avec 'choco install mkcert'."
    exit /b
)

:: Créer le répertoire pour les certificats s'il n'existe pas
if not exist %CERT_DIR% (
    mkdir %CERT_DIR%
)

:: Installer le certificat racine CA mkcert si nécessaire
echo "Installation du CA racine avec mkcert..."
mkcert -install

:: Générer les certificats pour le domaine spécifié
echo "Génération des certificats SSL pour %DOMAIN%..."
mkcert -key-file %CERT_DIR%\cert.key -cert-file %CERT_DIR%\cert.crt %DOMAIN%

:: Afficher le chemin des certificats générés
echo Certificats générés avec succès :
echo  - Certificat : %CERT_DIR%\cert.crt
echo  - Clé privée : %CERT_DIR%\cert.key

pause
