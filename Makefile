TITLE = [data-mapper]

unit-tests:
	@/bin/echo "${TITLE} unit test suite started..." \
	&& ./vendor/bin/phpunit -c tests/unit/phpunit.xml --coverage-html tests/unit/coverage

integration-tests:
	@/bin/echo "${TITLE} starting virtual machine ..." \
	&& vagrant up \
	&& /bin/echo "${TITLE} importing clear database ..." \
	&& ansible-playbook ansible.yml --tags database \
	&& /bin/echo "${TITLE} starting integration test suite ..." \
	&& ./vendor/bin/phpunit -c tests/integration/phpunit.xml --coverage-html tests/integration/coverage \
	&& /bin/echo "${TITLE} stopping virtual machine ..." \
	&& vagrant halt

.PHONY: unit-tests integration-tests