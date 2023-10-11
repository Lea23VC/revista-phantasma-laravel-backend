import os
import subprocess

# Load environment variables from .env file
env_vars = {}
if os.path.exists(".env"):
    with open(".env", "r") as env_file:
        for line in env_file:
            line = line.strip()
            if "=" in line:
                key, value = line.split("=", 1)
                env_vars[key] = value.strip().strip("'")


# Command to create individual secrets from .env file
def create_secrets():
    print(
        f"Creating secrets for {os.environ.get('OWNER')}/{os.environ.get('REPO_NAME')}..."
    )

    for key, value in env_vars.items():
        print(f"Creating secret {key} with value: {value}...")
        subprocess.run(
            [
                "aws",
                "ssm",
                "put-parameter",
                "--profile",
                "default",
                "--name",
                f"/revista_phantasma/prod/{key}",
                "--value",
                value,
                "--type",
                "SecureString",
                "--overwrite",
            ]
        )

    print("Secrets created successfully.")


if __name__ == "__main__":
    create_secrets()
