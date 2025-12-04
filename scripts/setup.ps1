<#
PowerShell setup script to bootstrap the Docker environment, install composer deps
and run acorn migrations + seeders inside the CLI container.

Usage: Run in repository root from PowerShell as Administrator (if needed):
  ./scripts/setup.ps1
#>

Write-Host "Copying .env.example to .env if missing..."
if (-not (Test-Path .env)) { Copy-Item .env.example .env }

Write-Host "Starting Docker containers..."
docker-compose up -d

Write-Host "Waiting a few seconds for services to be ready..."
Start-Sleep -Seconds 6

Write-Host "Installing composer dependencies and running migrations inside CLI container..."
# Use paths relative to the container's working dir (`/var/www/html`) and ensure WP-CLI is available
$cmd = 'cd wp-content/themes/sage; composer install --no-interaction; vendor/bin/acorn migrate --force; vendor/bin/acorn db:seed --force; '
$cmd += 'if [ ! -x /usr/local/bin/wp ]; then curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp && chmod +x /usr/local/bin/wp; fi; '
$cmd += '/usr/local/bin/wp theme activate sage --path=/var/www/html --allow-root'
docker-compose exec cli bash -lc "$cmd"

Write-Host "Done. Visit http://localhost:8080 and activate the theme if not already active."
