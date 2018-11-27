init:
	@echo -e "deploy - deploy production"
	@echo -e "clear_vars - clear cahce and logs"

deploy: git composer update_database clear_var


git:
	@git reset --hard
	@git checkout origin/develop
	@git pull

composer:
	@sudo chmod go+rw -R ./var
	@composer install

update_database:
	@php bin/console doctrine:migrations:migrate --no-debug
	@php bin/console doctrine:schema:update --force --em=local_managed --no-debug

clear:
	@php bin/console ckeditor:install --no-debug
	@php bin/console assets:install --symlink --no-debug
	@php bin/console cache:clear --no-warmup --env=dev --no-debug
	@php bin/console cache:warmup --env=dev --no-debug

clear_var:
	@sudo php bin/console assets:install --symlink --no-debug
	@sudo php bin/console cache:clear --no-warmup --env=prod
	@sudo php bin/console cache:warmup --env=prod
	@sudo rm ./var/logs/prod.log
	@sudo touch ./var/logs/prod.log
	@sudo rm ./var/logs/dev.log
	@sudo touch ./var/logs/dev.log
	@sudo chmod go+rw -R ./var
	@sudo chmod go+rw -R ./public/uploads