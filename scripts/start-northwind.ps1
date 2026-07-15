param(
    [string]$SqlPath,
    [int]$Port = 3307
)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$SqlPath = if ([string]::IsNullOrWhiteSpace($SqlPath)) {
    Join-Path $projectRoot 'database\sql\northwind_2024-11-26.sql'
} else {
    $SqlPath
}
$mysqlBin = 'C:\xampp\mysql\bin'
$dataDirectory = Join-Path $projectRoot 'storage\mariadb-data'
$configPath = Join-Path $dataDirectory 'my.ini'
$mysql = Join-Path $mysqlBin 'mysql.exe'
$mysqlAdmin = Join-Path $mysqlBin 'mysqladmin.exe'
$mysqld = Join-Path $mysqlBin 'mysqld.exe'
$installer = Join-Path $mysqlBin 'mysql_install_db.exe'

foreach ($requiredFile in @($mysql, $mysqlAdmin, $mysqld, $installer)) {
    if (-not (Test-Path -LiteralPath $requiredFile)) {
        throw "Required XAMPP database tool not found: $requiredFile"
    }
}

if (-not (Test-Path -LiteralPath (Join-Path $dataDirectory 'mysql'))) {
    & $installer "--datadir=$dataDirectory" "--port=$Port" '--password='
    if ($LASTEXITCODE -ne 0) {
        throw 'Could not initialize the isolated MariaDB data directory.'
    }
}

$databaseReady = $false
try {
    & $mysqlAdmin --protocol=tcp -h 127.0.0.1 -P $Port -u root ping 2>$null | Out-Null
    $databaseReady = $LASTEXITCODE -eq 0
} catch {
    $databaseReady = $false
}

if (-not $databaseReady) {
    Start-Process -FilePath $mysqld -ArgumentList "--defaults-file=$configPath", '--standalone' -WindowStyle Hidden

    for ($attempt = 0; $attempt -lt 30; $attempt++) {
        Start-Sleep -Milliseconds 250
        try {
            & $mysqlAdmin --protocol=tcp -h 127.0.0.1 -P $Port -u root ping 2>$null | Out-Null
            if ($LASTEXITCODE -eq 0) {
                $databaseReady = $true
                break
            }
        } catch {
            # Keep waiting until the local server is ready.
        }
    }
}

if (-not $databaseReady) {
    throw "MariaDB did not start on port $Port. Check $dataDirectory for its error log."
}

& $mysql --protocol=tcp -h 127.0.0.1 -P $Port -u root -e 'CREATE DATABASE IF NOT EXISTS northwind CHARACTER SET utf8 COLLATE utf8_general_ci;'

$hasNorthwind = $false
try {
    $customerCount = & $mysql --protocol=tcp -h 127.0.0.1 -P $Port -u root -N -B northwind -e 'SELECT COUNT(*) FROM CUSTOMERS;' 2>$null
    $hasNorthwind = $LASTEXITCODE -eq 0 -and [int]$customerCount -gt 0
} catch {
    $hasNorthwind = $false
}

if (-not $hasNorthwind) {
    if (-not (Test-Path -LiteralPath $SqlPath)) {
        throw "Northwind SQL dump not found: $SqlPath"
    }

    $normalizedSqlPath = $SqlPath.Replace('\', '/')
    & $mysql --protocol=tcp -h 127.0.0.1 -P $Port -u root northwind -e "source $normalizedSqlPath"
    if ($LASTEXITCODE -ne 0) {
        throw 'The Northwind SQL dump could not be imported.'
    }
}

Write-Host "Northwind MariaDB is ready on 127.0.0.1:$Port." -ForegroundColor Green
