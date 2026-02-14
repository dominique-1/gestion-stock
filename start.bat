@echo off
title Gestion Stock - Serveur Simple
color 0A

echo ==========================================
echo    SERVEUR GESTION STOCK - VERSION SIMPLE
echo ==========================================
echo.
echo 🎯 Solution sans conflit de ports
echo 🌐 Port: 9000 (libre)
echo 📁 Répertoire: public/
echo.
echo Démarrage dans 3 secondes...
timeout /t 3 /nobreak > nul

cls
echo ==========================================
echo DÉMARRAGE DU SERVEUR...
echo ==========================================
echo.

php serveur_simple.php

echo.
echo Serveur arrêté
pause
