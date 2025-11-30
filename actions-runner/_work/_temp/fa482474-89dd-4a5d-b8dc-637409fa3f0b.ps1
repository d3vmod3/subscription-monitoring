$ErrorActionPreference = 'stop'
cd C:\xampp\htdocs\subscription-monitoring
git reset --hard
git pull origin main

if ((Test-Path -LiteralPath variable:\LASTEXITCODE)) { exit $LASTEXITCODE }