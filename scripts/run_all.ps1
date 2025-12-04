<# Run all: start docker, install composer, migrate, seed, activate theme. Logs to scripts/setup-output.txt #>
$outFile = Join-Path $PSScriptRoot 'setup-output.txt'
"---- Run started at $(Get-Date) ----" | Out-File $outFile -Encoding utf8

if (-not (Test-Path (Join-Path $PSScriptRoot '..\.env'))) {
    Copy-Item (Join-Path $PSScriptRoot '..\.env.example') (Join-Path $PSScriptRoot '..\.env') -Force
    "Created .env from .env.example" | Out-File $outFile -Append -Encoding utf8
}

"Running: docker compose up -d" | Out-File $outFile -Append -Encoding utf8
docker compose up -d 2>&1 | Tee-Object -FilePath $outFile -Append

Start-Sleep -Seconds 5

"Checking containers status..." | Out-File $outFile -Append -Encoding utf8
docker compose ps 2>&1 | Tee-Object -FilePath $outFile -Append

$tries = 0
while ($tries -lt 20) {
    $ps = docker compose ps --format "{{.Name}}: {{.State}}" 2>$null
    if ($ps -match 'db.*running' -and $ps -match 'wordpress.*running') { break }
    Start-Sleep -Seconds 3
    $tries++
    "Waiting for containers ($tries) ..." | Out-File $outFile -Append -Encoding utf8
}

"Running composer install and acorn migrate/seed inside cli container" | Out-File $outFile -Append -Encoding utf8
docker compose exec cli bash -lc 'cd /var/www/html/wp-content/themes/sage && composer install --no-interaction' 2>&1 | Tee-Object -FilePath $outFile -Append
docker compose exec cli bash -lc 'cd /var/www/html/wp-content/themes/sage && vendor/bin/acorn migrate --force' 2>&1 | Tee-Object -FilePath $outFile -Append
docker compose exec cli bash -lc 'cd /var/www/html/wp-content/themes/sage && vendor/bin/acorn db:seed --force' 2>&1 | Tee-Object -FilePath $outFile -Append
docker compose exec cli bash -lc 'wp theme activate sage --allow-root' 2>&1 | Tee-Object -FilePath $outFile -Append

"Finished run at $(Get-Date). If there were errors, see $outFile" | Out-File $outFile -Append -Encoding utf8
Write-Host "Done. Open http://localhost:8080. Logs: $outFile"
