Im Terminal, beim Erstellen jedes neuen PROJEKTS (nicht Branch):
cd Ordner des Projekts
composer create-project symfony/skeleton . #
composer require webapp
php -S 127.0.0.1:8000 -t public (oder wenn CLI installiert ist, dann wählt es automatisch einen freien Port)
symfony server:start (wenn CLI bereits installiert ist)

Weitere häufigen Befehle:
php bin/console cache:clear oder mit CLI: #symfony console cache:clear
php bin/console make:controller oder mit CLI: symfony console make:controller
php bin/console doctrine:migrations:migrate oder mit CLI: symfony console doctrine:migrations:migrate
