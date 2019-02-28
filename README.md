# wp5-make

A WordPress 5 development boilerplate for custom projects with plugin and theme development. Replace `wp5-blank` with your theme slug and start building a WordPress site with a custom theme!

## Why this boilerplate?

This boilerplate is aimed at simplifying the work of the developer, by including steps and presets for most of the common actions when building a WordPress site with a custom theme, like:

- Composer enabled development
- Makefile for easy WP download and installation
- Better folder structure
- Laravel style config folder for configurations that do not need to go in the database
- Theme development boilerplate
- Webpack or Laravel Mix asset compiling
- Removal of many WordPress default features, i.e. junk elements in the `<head>`
- Maintenance mode
- Login by email; ajax login; block wp-login.php
- Custom fields generation through php config file
- and a few other useful things

## Dependencies

This boilerplate is intended to be used with the WP5-BANG package, which contains part of the functionality listed above. Additionally, you can install WP5-BANG-META package to easily configure custom meta fields, as well as any other package needed.

## Workflow and environments

Running `make install` will download latest WordPress version and then change the folder structure, getting the content folder out of the WordPress installation. It will add a *wp-config.php*, that includes many improvements (both in structure and security) over WordPress standard install:

- renaming *wp-content* folder and moving it outside the WordPress installation
- moving *plugins* and *themes* folders outside the content folder
- solving some common issues in local installations
- decreasing number of post revisions saved to the posts table
- changing the default table prefix
- disable pingbacks

## How to use

1. `git clone` this project locally
2. Delete the *.git* folder
3. `git init` your new project
4. Run `make install` to download wordpress
5. Open *web/wp-config.php* and make all changes necessary
6. Replace `wp5-blank` with your theme slug/name in all occurances in folder /web/themes/wp5-blank, including the folder name
7. Run the WordPress installer by opening the project in your browser

## Compiling

If you want to use assets compiling, you have Laravel mix predefined. Run `npm install` to install the needed packages. You can then use the different commands available in package.json, like `npm run dev` or `npm watch`. Depending on wheather you will compile your assets or not, you have to set up the correct links in the theme Init.php file.

### Using GIT with WordPress

The package includes a .gitignore file that will set up a GIT + composer environment for WordPress without interfering with native WordPress functionality. WordPress files are gitignored, so update WordPress through the built-in updater. All plugins and themes, are gitignored as well, with specific exclusion of the ones you develop yourself.

In other words, only your custom theme and plugins should be included in the repository and all other WordPress code should be ignored and managed through the WordPress admin. 

A special feature is under development to make easier the syncing of WP and plugin versions between dev and production.

### Push to production

My recommendation is setup a push-to-production deployment strategy. 

1. Create a folder for your repository outside the public html folder on your server. 
2. Run `git init --bare` to create a bare repository.
3. You will find a hooks folder in your new empty repository: `cd hooks`
4. Create a file called *post-receive* and enter the following contents:
`
#!/bin/sh
git --work-tree=/path/to/your/root --git-dir=/path/to/your/repository.git checkout -f
`
This hook will run every time you push to this repo and will copy the current working tree to your actual production site (without the .git folder). 
5. Go back to your dev installation and add your newly created repository `git remote add production ssh://username@your-server.com:PORT/path/to/your/repository.git`
6. You need to do a force push to this repository master branch: `git push -f production master:master`
