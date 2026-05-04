#!/bin/bash

FILE=/var/www/html/MintHCM/legacy/config.php

if [ -f "$FILE" ]; then
  # Run services
  service cron start
  service apache2 start
else
  # Use existing code in the container instead of downloading
  printf "Using existing code for installation...\n"
  # The code is already in /var/www/html/MintHCM via Dockerfile COPY
  php /var/www/script/generate_config.php
  chown -R www-data:www-data /var/www/html/MintHCM
  chmod -R 755 /var/www/html/MintHCM
 
  # Check if the config_si.php file was generated
  if [[ ! -f /var/www/html/MintHCM/configMint4 ]]; then
    printf "Error: Failed to generate configMint4 - please check the configuration\n"
    exit 1
  fi

  # Start apache for installation if needed
  service apache2 start

  # Make the MintHCM installation request
  printf "Starting MintHCM installation...\n"
  su -s /bin/bash -c 'php /var/www/html/MintHCM/MintCLI install < /var/www/html/MintHCM/configMint4' www-data

# Check the exit code
  if [[ $? -ne 0 ]]; then
    printf "Error: MintHCM installation failed - please check logs\n"
  else
    printf "MintHCM installation completed!\n"
    #add cron and start service
    printf "*    *    *    *    *     cd /var/www/html/MintHCM/legacy; php -f cron.php > /dev/null 2>&1" > /var/spool/cron/crontabs/www-data
    service cron start
  fi
fi
