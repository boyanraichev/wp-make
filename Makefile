init: install content config
	
install:
	@echo 'downloading wordpress...'
	@cd web && mkdir wp && cd wp && pwd && bash ../../setup/wp-download.sh
	@echo 'you might need to manually delete the content folder?'	
	
config:
	@echo 'wp config sample...'	
	@rm web/wp/wp-config-sample.php
	@cp setup/wp-config-sample.php web/wp-config.php
	@cp setup/index.php web/index.php
	
content:
	@echo 'move content folder...'	
	@rm -R web/wp/wp-content/plugins
	@rm -R web/wp/wp-content/themes
	@mkdir web/content && cd web/content && cp -rvf ../wp/wp-content/* . && rm -R ../wp/wp-content && mkdir themes