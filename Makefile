.DEFAULT_GOAL := help
.SILENT:

stage ?= staging
# 1. Standard: Wir gehen davon aus, wir sind LOKAL (also Docker nutzen)
#    Wir mounten .ssh read-only, damit Deployer Keys hat.
PHP_RUNNER = docker compose run --rm php-app

# 2. Ausnahme: Wenn wir wirklich in der CI sind (GitHub gibt 'true' zurück)
#    Dann machen wir die Variable leer -> Befehl läuft nativ
ifeq ($(CI), true)
    PHP_RUNNER = 
endif

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
	$(PHP_RUNNER) composer dump-autoload
	$(PHP_RUNNER) composer install

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
	$(PHP_RUNNER) /bin/bash

bin-console: ## Command with c=help
	$(MAKE) -s .print m="####### Run bin/console $(c)"
	$(PHP_RUNNER) bin/console $(c)
	echo "\n"

composer-install: ## Run composer install
	$(PHP_RUNNER) composer install

## Tests

all-tests: ## Run all Tests
	$(MAKE) -s phpunit
	$(MAKE) -s infection
	$(MAKE) -s spec
	$(MAKE) -s behat

phpunit: ## Run phpunit Tests
	$(MAKE) .print m="####### Run PHPUnit"
	$(PHP_RUNNER) composer test:phpunit

phpunit-unit: ## Run Unit Tests only
	$(MAKE) .print m="####### Run PHPUnit Unit Tests"
	$(PHP_RUNNER) composer test:phpunit-unit

phpunit-feature: ## Run Feature Tests (requires running stack)
	$(MAKE) .print m="####### Run PHPUnit Feature Tests"
	$(PHP_RUNNER) composer test:phpunit-feature

coverage: ## Run phpunit Coverage
	$(MAKE) -s .print m="####### Check Coverage"
	$(PHP_RUNNER) composer coverage

coverage-html: ## Run phpunit Coverage wit HTML output
	$(MAKE) -s .print m="####### Run Check Coverage HTML"
	$(PHP_RUNNER) composer coverage-html -- $(filter-out $@,$(MAKECMDGOALS))

infection: ## Run mutationen
	$(MAKE) .print m="####### Run Infection"
	$(PHP_RUNNER) composer test:infection -- $(arg)

.PHONY: spec
spec: ## Run Spec Tests (Behaviour) | n='namespace'
	$(MAKE) -s .print m="####### Run Spec"
	$(PHP_RUNNER) composer test:phpspec -- $${n}

behat: ## Run all behat Tests
	$(MAKE) -s .print m="####### Run Behat"
	$(PHP_RUNNER) composer test:behat

spec-init: ## Run Spec init Tests (Behaviour)
	$(MAKE) -s .print m="####### Run Spec Init"
	$(PHP_RUNNER) composer test:phpspec-init

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
	$(PHP_RUNNER) composer style:cs

phpcs-check: ## Code style check
	$(MAKE) .print m="####### Run PHP-CS"
	$(PHP_RUNNER) composer style:phpcs

rector-check: ## Run Rector
	$(MAKE) -s .print m="####### Run Rector"
	$(PHP_RUNNER) composer rector-check

psalm: ## Run psalm
	$(MAKE) .print m="####### Run Psalm"
	$(PHP_RUNNER) composer style:psalm

phpstan: ## Run PHPstan
	$(MAKE) -s .print m="####### Run PHPStan"
	$(PHP_RUNNER) composer phpstan


### Fix
all-style-fixes asf: ## Run all Style fixes
	$(MAKE) -s cs-fix
	$(MAKE) -s phpcs-fix
	$(MAKE) -s rector

cs-fix: ## Run CS Fixer
	$(MAKE) .print m="####### Run CS-Fixer"
	$(PHP_RUNNER) composer style:cs-fix

phpcs-fix: ## Code style fix
	$(MAKE) .print m="####### Run PHP-CS Fixer"
	$(PHP_RUNNER) composer style:phpcbf

rector: ## Run Rector
	$(MAKE) -s .print m="####### Run Rector"
	$(PHP_RUNNER) composer rector


## Architekture
deptrac: ## Check Hexagonal architecture
	$(MAKE) .print m="####### Run Deptrac"
	$(PHP_RUNNER) composer deptrac


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

# --- Commands ---

deploy: ## Deploy application (usage: make deploy stage=production)
	$(MAKE) -s .print m="####### Deploying to $(stage)"
	$(PHP_RUNNER) vendor/bin/dep deploy $(stage) -vv

deploy-rollback: ## Rollback to previous release
	$(MAKE) -s .print m="####### Rolling back $(stage)"
	$(PHP_RUNNER) vendor/bin/dep rollback $(stage)

deploy-list: ## List available releases
	$(MAKE) -s .print m="####### Listing releases for $(stage)"
	$(PHP_RUNNER) vendor/bin/dep releases $(stage)

deploy-unlock: ## Unlock deployment (if failed previously)
	$(MAKE) -s .print m="####### Unlocking $(stage)"
	$(PHP_RUNNER) vendor/bin/dep deploy:unlock $(stage)

.print:
	@echo "\n\033[34m$m\033[0m"

# Hilfs-Target um zu prüfen, wie ausgeführt wird
debug-env:
	@echo "Runner Command: $(PHP_RUNNER)"

.print:
	printf "\033[33m\n${m}\033[0m\n"
