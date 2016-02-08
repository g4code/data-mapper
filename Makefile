TITLE = [data-mapper]

unit-tests:
	@/bin/echo -e "${TITLE} unit test suite started..." \
	&& ./vendor/bin/phpunit -c tests/unit/phpunit.xml --coverage-html tests/unit/coverage

integration-tests:
	@/bin/echo -e "${TITLE} integration test suite started..." \
	&& ./vendor/bin/phpunit -c tests/integration/phpunit.xml --coverage-html tests/integration/coverage

.PHONY: unit-tests integration-tests