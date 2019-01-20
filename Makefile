install-wp:
	@bash scripts/wp-downloads.sh
	@cd web/
	@rm -R wp/wp-content/plugins
	@rm -R wp/wp-content/themes
	@mkdir content
	@cd content
	@cp -rvf ../wp/wp-content/* .
	@rm -R wp/wp-content
	