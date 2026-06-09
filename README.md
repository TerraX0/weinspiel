# Interactive Wine Game
An interactive, web-based game built to practice core application logic, state management, and routing.

**🌐 Live Demo:** You can play the game live on my website: [alinakoellner.de](https://alinakoellner.de)

### What it does:
This project showcases my ability to implement core programming logic, handle user inputs, and manage dynamic interactive states in the browser. It serves as a practical exercise to deepen my understanding of controller logic and frontend-backend interaction.

---

## 🇬🇧 English Version

### Project Setup & Common Commands
This project was initialized using the Symfony framework. Below is a guide on how new projects are structured and the most common terminal commands used during development.

**Initialization (for new projects):**
```bash
# Navigate to your project directory
cd path/to/your/project

# Create a new Symfony skeleton
composer create-project symfony/skeleton .

# Add full web application features (Twig, AssetMapper, Orcm, etc.)
composer require webapp
```
```bash
# Option A: Standard PHP built-in server
php -S 127.0.0.1:8000 -t public

# Option B: If Symfony CLI is installed (automatically selects a free port)
symfony server:start
```
```bash
# Clear the application cache
php bin/console cache:clear
# Alternative with Symfony CLI: symfony console cache:clear

# Create a new controller
php bin/console make:controller
# Alternative with Symfony CLI: symfony console make:controller

# Execute database migrations
php bin/console doctrine:migrations:migrate
# Alternative with Symfony CLI: symfony console doctrine:migrations:migrate
```
---

## 🇩🇪 German Version
<br>Im Terminal, beim Erstellen jedes neuen PROJEKTS (nicht Branch):

```bash
cd Ordner des Projekts
composer create-project symfony/skeleton . #
composer require webapp
php -S 127.0.0.1:8000 -t public (oder wenn CLI installiert ist, dann wählt es automatisch einen freien Port)
symfony server:start (wenn CLI bereits installiert ist)
```
Weitere häufigen Befehle:
```bash
php bin/console cache:clear oder mit CLI: #symfony console cache:clear
php bin/console make:controller oder mit CLI: symfony console make:controller
php bin/console doctrine:migrations:migrate oder mit CLI: symfony console doctrine:migrations:migrate
```
