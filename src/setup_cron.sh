#!/bin/bash

# Get the absolute path to the cron.php file
CRON_PHP_PATH="$(dirname "$0")"/cron.php

# Add the cron job to run every 5 minutes
(crontab -l 2>/dev/null; echo "*/5 * * * * php $CRON_PHP_PATH") | crontab -

echo "Cron job for cron.php has been set up to run every 5 minutes."