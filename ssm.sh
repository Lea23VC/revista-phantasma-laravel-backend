#!/bin/bash

# AWS CLI profile and region
AWS_PROFILE="new-lea"
AWS_REGION="us-east-1"

# Source and target paths
SOURCE_PATH="/revista_phantasma/prod/"
TARGET_PATH="/revista_phantasma/dev/"

# Temporary file to store parameters
TEMP_FILE="prod-parameters.json"

# Step 1: Retrieve all parameters from the 'prod' environment
echo "Fetching parameters from ${SOURCE_PATH}..."
aws ssm get-parameters-by-path \
  --profile $AWS_PROFILE \
  --region $AWS_REGION \
  --path $SOURCE_PATH \
  --recursive \
  --with-decryption \
  --query "Parameters[*].{Name:Name,Value:Value,Type:Type}" \
  --output json > $TEMP_FILE

# Step 2: Create parameters in the 'dev' environment
echo "Copying parameters to ${TARGET_PATH}..."
jq -c '.[]' $TEMP_FILE | while read -r i; do
  NAME=$(echo "$i" | jq -r '.Name' | sed "s|$SOURCE_PATH|$TARGET_PATH|")
  VALUE=$(echo "$i" | jq -r '.Value')
  TYPE=$(echo "$i" | jq -r '.Type')
  
  # Handle cases where the value may be an unresolved reference (e.g., ${...})
  if [[ $VALUE == *"\$"* ]]; then
    VALUE=\"${VALUE}\"
  fi

  aws ssm put-parameter \
    --profile $AWS_PROFILE \
    --region $AWS_REGION \
    --name "$NAME" \
    --value "$VALUE" \
    --type "$TYPE" \
    --overwrite

  if [ $? -eq 0 ]; then
    echo "Copied: $NAME"
  else
    echo "Failed to copy: $NAME"
  fi
done

# Clean up temporary file
rm -f $TEMP_FILE

echo "All parameters have been copied from ${SOURCE_PATH} to ${TARGET_PATH}."
