#!/bin/bash

# Run init-script with long timeout - and make it run in the background
/opt/mssql-tools/bin/sqlcmd -S localhost -l 30 -U SA -P "saPassword12" -i InitDbUser.sql &
/opt/mssql-tools/bin/sqlcmd -S localhost -l 60 -U SA -P "saPassword12" -i CreateDbStructure.sql &

# Start SQL server
/opt/mssql/bin/sqlservr