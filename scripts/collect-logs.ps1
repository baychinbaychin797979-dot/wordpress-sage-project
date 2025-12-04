<# collect-logs.ps1
Thu thập docker compose ps và logs cho các service chính, lưu vào scripts/docker-logs.txt
Chạy: powershell -ExecutionPolicy Bypass -File .\scripts\collect-logs.ps1
#>

$out = Join-Path $PSScriptRoot 'docker-logs.txt'
"---- Logs collected at $(Get-Date) ----`n" | Out-File $out -Encoding utf8

docker compose ps 2>&1 | Tee-Object -FilePath $out -Append

foreach ($svc in @('db','wordpress','cli')) {
    "`n--- Logs for $svc ---`n" | Out-File $out -Append -Encoding utf8
    docker compose logs --tail=200 $svc 2>&1 | Tee-Object -FilePath $out -Append
}

Write-Host "Logs saved to $out"
