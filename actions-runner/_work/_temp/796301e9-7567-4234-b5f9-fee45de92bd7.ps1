$ErrorActionPreference = 'stop'
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
git config --global --add safe.directory "C:/xampp/htdocs/subscription-monitoring"

if ((Test-Path -LiteralPath variable:\LASTEXITCODE)) { exit $LASTEXITCODE }