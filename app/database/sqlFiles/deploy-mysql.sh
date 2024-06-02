#!/bin/bash

# Auteur        : Antoine Langevin 
# Date          : 2023-10-20
# Last update   : 2023-10-20

# status --- NON TESTÉ


MARIADB_SECURE_PASSWORD="z2YrD3V3mnOL#i3ntoqw@fg"

echo "Mise à jour système..."
sudo apt update && sudo apt upgrade -y  || { echo "Erreur lors de la mise à jour"; exit 1; }
echo "OK"

echo "Installation de mariadb-server..."
sudo apt install -y mariadb-server
echo "OK"

echo "Configuration de mariadb-server..."
sudo mysql_secure_installation <<EOF

Y
$MARIADB_SECURE_PASSWORD
$MARIADB_SECURE_PASSWORD
Y
Y
Y
Y
EOF
echo "OK"

# start mariadb
echo "Exercution de mariadb..."
sudo systemctl start mariadb
sudo systemctl enable mariadb
echo "OK"

echo "Création de la structure de la base de données..."
mysql -u root -p $MARIADB_SECURE_PASSWORD < setup.sql
echo "OK"

echo "Population de la base de données..."
mysql -u root -p $MARIADB_SECURE_PASSWORD < populate.sql
echo "OK"

echo "Le setup de MariaDB est complet, setup.sql && populate.sql executés avec succès!"