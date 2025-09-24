@echo off
echo Updating namespaces from Common to Api...

REM Update all namespace declarations
powershell -Command "(Get-Content 'app\Api\*' -Recurse -Include '*.php') | ForEach-Object { $_ -replace 'namespace Common\\', 'namespace Api\' } | Set-Content 'app\Api\*' -Recurse -Include '*.php'"

echo Namespace update completed!