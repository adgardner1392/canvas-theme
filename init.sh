# Straightforward bash script to run all build processes, this allows us to
# keep things like build files and dependencies completely seperate to our
# repositories.

npm install;
composer install;
npm run build;
