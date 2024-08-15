import os
import subprocess
import json


# Fetch environment variables from AWS SSM Parameter Store
def fetch_secrets():
    print("Fetching secrets from /revista_phantasma/prod...")

    # Fetch all parameter names under the given path
    result = subprocess.run(
        [
            "aws",
            "ssm",
            "get-parameters-by-path",
            "--profile",
            "default",
            "--path",
            "/revista_phantasma/prod/",
            "--recursive",
            "--with-decryption",
        ],
        capture_output=True,
        text=True,
    )

    if result.returncode != 0:
        print(f"Error fetching secrets: {result.stderr}")
        return {}

    parameters = json.loads(result.stdout)["Parameters"]
    env_vars = {param["Name"].split("/")[-1]: param["Value"] for param in parameters}

    return env_vars


# Write environment variables to .env.production file
def write_env_file(env_vars):
    with open(".env.production", "w") as env_file:
        for key, value in env_vars.items():
            env_file.write(f"{key}='{value}'\n")

    print(".env.production file created successfully.")


if __name__ == "__main__":
    env_vars = fetch_secrets()
    if env_vars:
        write_env_file(env_vars)
