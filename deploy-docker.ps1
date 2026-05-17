# Traffic app — Docker deploy helper (Windows PowerShell)
$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

function Get-DockerExe {
    $candidates = @(
        "$env:ProgramFiles\Docker\Docker\resources\bin\docker.exe",
        "$env:ProgramFiles\Docker\Docker\docker.exe"
    )
    if (Get-Command docker -ErrorAction SilentlyContinue) {
        $candidates = @("docker") + $candidates
    }
    foreach ($path in $candidates) {
        try {
            $null = & $path version 2>$null
            if ($LASTEXITCODE -eq 0) { return $path }
        } catch { continue }
    }
    return $null
}

Write-Host "==> Preparing .env.docker" -ForegroundColor Cyan
if (-not (Test-Path ".env.docker")) {
    Copy-Item ".env.docker.example" ".env.docker"
    Write-Host "    Created .env.docker from example"
}

$dockerEnv = Get-Content ".env.docker" -Raw
if ($dockerEnv -match "APP_KEY=\s*$" -and (Test-Path ".env")) {
    $localKey = (Select-String -Path ".env" -Pattern "^APP_KEY=(.+)$").Matches.Groups[1].Value.Trim()
    if ($localKey) {
        (Get-Content ".env.docker") -replace "^APP_KEY=.*", "APP_KEY=$localKey" | Set-Content ".env.docker"
        Write-Host "    Copied APP_KEY from .env"
    }
}

$docker = Get-DockerExe
if (-not $docker) {
    Write-Host ""
    Write-Host "Docker is not installed or not in PATH." -ForegroundColor Red
    Write-Host "Install Docker Desktop: https://www.docker.com/products/docker-desktop/" -ForegroundColor Yellow
    Write-Host "Then re-run: .\deploy-docker.ps1" -ForegroundColor Yellow
    exit 1
}

Write-Host "==> Building and starting containers" -ForegroundColor Cyan
& $docker compose --env-file .env.docker up -d --build
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

Write-Host ""
Write-Host "Deployment started successfully." -ForegroundColor Green
Write-Host "App:     http://localhost:8000"
Write-Host "MongoDB: mongodb://localhost:27017"
Write-Host "Logs:    docker compose --env-file .env.docker logs -f app"
