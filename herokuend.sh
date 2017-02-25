#!/bin/bash
while IFS='' read -r line || [[ -n "$line" ]]; do
    heroku config:set  $line
done < "./.env"
