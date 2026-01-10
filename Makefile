.DEFAULT_GOAL := help
.SILENT:

$(shell [ -f .env ] || cp ./.env.skeleton .env)
$(shell [ -d ./var/logs/nginx ] || mkdir -p ./var/logs/nginx)

help:
	@grep -hE '(^[a-zA-Z0-9 \./_-]+:.*?##.*$$)|(^##)|(^###)|(^####)' $(MAKEFILE_LIST) | \
	awk 'BEGIN {FS = ":.*?## "}; \
	/^## / {printf "\n \033[1;37m%s\033[0m\n", substr($$0, 4); next}; \
	/^### / {printf " > \033[4;37m%s\033[0m\n", substr($$0, 5); next}; \
	/^#### / {printf "\n\033[0;33m## %s\033[0m\033[2;37m\n", substr($$0, 6); next}; \
	/^[a-zA-Z0-9\-\. ]+:.*?##/ { \
		cmd = $$1; desc = $$2; \
		n = split(cmd, parts, " "); \
		formatted = parts[1]; \
		vislen = length(parts[1]); \
		for (i = 2; i <= n; i++) { \
			alias = parts[i]; \
			formatted = formatted " \033[0;36m(" alias ")\033[0m"; \
			vislen += length(alias) + 3; \
		} \
		padding = 20 - vislen; if (padding < 1) padding = 1; \
		printf " ─ \033[0;32m%s\033[0m%*s \033[0;39m%s\n", formatted, padding, "", desc; \
	} END { print "" }'

## Docker-Container
setup: ## Install composer thinks
	$(MAKE) -s .print m="####### Composer dump-autoload and install"
	docker compose run --rm php-app composer dump-autoload
	docker compose run --rm php-app composer install

build rebuild: ## Build or rebuild the container
	$(MAKE) -s .print m="####### Build images from Dockerfile"
	docker compose build --no-cache --pull

start up 1: ## Start container
	$(MAKE) -s .print m="####### Start Docker"
	docker compose up -d

stop down 0: ## Stop container
	$(MAKE) -s .print m="####### Run Stop Docker"
	docker compose down --remove-orphans

console c: ## Open console
	$(MAKE) -s .print m="####### Start Console"
	docker compose run --rm php-app /bin/bash

bin-console: ## Command with c=help
	$(MAKE) -s .print m="####### Run bin/console $(c)"
	docker compose run --rm php-app bin/console $(c)
	echo "\n"

composer-install: ## Run composer install
	docker compose run --rm php-app composer install

## Tests

all-tests: ## Run all Tests
	$(MAKE) -s phpunit
	$(MAKE) -s infection
	$(MAKE) -s spec
	$(MAKE) -s behat

phpunit: ## Run phpunit Tests
	$(MAKE) .print m="####### Run PHPUnit"
	docker compose run --rm php-app composer test:phpunit

phpunit-unit: ## Run Unit Tests only
	$(MAKE) .print m="####### Run PHPUnit Unit Tests"
	docker compose run --rm php-app composer test:phpunit-unit

phpunit-feature: ## Run Feature Tests (requires running stack)
	$(MAKE) .print m="####### Run PHPUnit Feature Tests"
	docker compose exec php-app composer test:phpunit-feature

coverage: ## Run phpunit Coverage
	$(MAKE) -s .print m="####### Check Coverage"
	docker compose run --rm -e XDEBUG_MODE=coverage php-app composer coverage

coverage-html: ## Run phpunit Coverage wit HTML output
	$(MAKE) -s .print m="####### Run Check Coverage HTML"
	docker compose run --rm -e XDEBUG_MODE=coverage php-app composer coverage-html -- $(filter-out $@,$(MAKECMDGOALS))

infection: ## Run mutationen
	$(MAKE) .print m="####### Run Infection"
	docker compose run --rm php-app composer test:infection -- $(arg)

.PHONY: spec
spec: ## Run Spec Tests (Behaviour) | n='namespace'
	$(MAKE) -s .print m="####### Run Spec"
	docker compose run --rm php-app composer test:phpspec -- $${n}

behat: ## Run all behat Tests
	$(MAKE) -s .print m="####### Run Behat"
	docker compose run --rm php-app composer test:behat

spec-init: ## Run Spec init Tests (Behaviour)
	$(MAKE) -s .print m="####### Run Spec Init"
	docker compose run --rm php-app composer test:phpspec-init

## Code - Style
### Check
all-style-checks asc: ## Run all Style checks
	$(MAKE) -s cs-check
	$(MAKE) -s phpcs-check
	$(MAKE) -s rector-check
	$(MAKE) -s phpstan
	$(MAKE) -s phan
	$(MAKE) -s deptrac

cs-check: ## Run CS Fixer
	$(MAKE) .print m="####### Run CS-Check"
	docker compose run --rm php-app composer style:cs

phpcs-check: ## Code style check
	$(MAKE) .print m="####### Run PHP-CS"
	docker compose run --rm php-app composer style:phpcs

rector-check: ## Run Rector
	$(MAKE) -s .print m="####### Run Rector"
	docker compose run --rm php-app composer rector-check

psalm: ## Run psalm
	$(MAKE) .print m="####### Run Psalm"
	docker compose run --rm php-app composer style:psalm

phpstan: ## Run PHPstan
	$(MAKE) -s .print m="####### Run PHPStan"
	docker compose run --rm php-app composer phpstan


### Fix
all-style-fixes asf: ## Run all Style fixes
	$(MAKE) -s cs-fix
	$(MAKE) -s phpcs-fix
	$(MAKE) -s rector

cs-fix: ## Run CS Fixer
	$(MAKE) .print m="####### Run CS-Fixer"
	docker compose run --rm php-app composer style:cs-fix

phpcs-fix: ## Code style fix
	$(MAKE) .print m="####### Run PHP-CS Fixer"
	docker compose run --rm php-app composer style:phpcbf

rector: ## Run Rector
	$(MAKE) -s .print m="####### Run Rector"
	docker compose run --rm php-app composer rector


## Architekture
deptrac: ## Check Hexagonal architecture
	$(MAKE) .print m="####### Run Deptrac"
	docker compose run --rm php-app composer deptrac


all-check: ## Run all comands (also with fix) in order. If it runs at the end, you code is ready to commit
	$(MAKE) -s all-style-fixes

	$(MAKE) -s deptrac
	$(MAKE) -s phpstan
	$(MAKE) -s psalm

	$(MAKE) -s all-tests

## CI / Deployment
docker-push: ## Build and Push CI Image (requires IMAGE_NAME env)
	$(MAKE) -s .print m="####### Building & Pushing Image: ${IMAGE_NAME}"
	docker compose build
	docker compose push

docker-pull: ## Pull CI Image from Registry
	$(MAKE) -s .print m="####### Pulling Image: ${IMAGE_NAME}"
	docker compose pull

deploy: ## Deploy application (stage=production|staging)
	$(MAKE) -s .print m="####### Deploying to ${stage}"
	DEPLOYER_STAGE=${stage} docker compose run --rm php-app vendor/bin/dep deploy ${stage}

deploy-rollback: ## Rollback to previous release (stage=production|staging)
	$(MAKE) -s .print m="####### Rolling back ${stage} to previous release"
	DEPLOYER_STAGE=${stage} docker compose run --rm php-app vendor/bin/dep rollback ${stage}

deploy-list: ## List available releases (stage=production|staging)
	$(MAKE) -s .print m="####### Listing releases for ${stage}"
	DEPLOYER_STAGE=${stage} docker compose run --rm php-app vendor/bin/dep releases ${stage}



.print:
	printf "\033[33m\n${m}\033[0m\n"
