#!/bin/bash

#-------------------------------------
# Configuration
#-------------------------------------
BACKUP_DIR="/var/www/db-dump"       # Directory for backups
DB_NAME="app_heycarson_prod_backup" # Database name
DB_USER="root"                      # Database user
DB_PASS="AVNS_ZGxoP24TTSZu8DpJrhQ"   # Database password

# Files for rotation tracking
LATEST_FILE="$BACKUP_DIR/backup-latest.sql"
CYCLE_INDEX_FILE="$BACKUP_DIR/cycle_index.txt"
DAILY_LOG_FILE="$BACKUP_DIR/last_daily_backup.txt"

#-------------------------------------
# Create Latest Backup (runs every 5 minutes)
#-------------------------------------
mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$LATEST_FILE"
echo "Latest backup taken at $(date)" >> "$BACKUP_DIR/log.txt"

#-------------------------------------
# Rotate Stable Backups Once Per Day
#-------------------------------------
# Get current date as YYYY-MM-DD
current_date=$(date +%Y-%m-%d)

# Check if a daily backup has been taken today.
if [ ! -f "$DAILY_LOG_FILE" ] || [ "$(cat "$DAILY_LOG_FILE")" != "$current_date" ]; then
    # Determine current cycle index and calculate the new index
    if [ -f "$CYCLE_INDEX_FILE" ]; then
        last_index=$(cat "$CYCLE_INDEX_FILE")
        new_index=$(( (last_index + 1) % 4 ))
    else
        new_index=0
    fi

    # Save the updated cycle index and today's date
    echo "$new_index" > "$CYCLE_INDEX_FILE"
    echo "$current_date" > "$DAILY_LOG_FILE"

    # Copy the latest backup to the daily rotation file
    cp "$LATEST_FILE" "$BACKUP_DIR/backup${new_index}.sql"
    echo "Daily backup updated into backup${new_index}.sql at $(date)" >> "$BACKUP_DIR/log.txt"
fi
