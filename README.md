# Symfony 2.8 blog
---------------------------------------------

php app/console doctrine:database:create

php app/console doctrine:schema:create

php app/console doctrine:schema:update

php app/console doctrine:fixture:load

=============================================

php app/console assets:install web --symlink

php app/console assetic:dump

php app/console cache:clear --env=prod