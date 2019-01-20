# wp5-env

A WordPress 5 development boilerplate. Replace `wp5-blank` with your theme slug and start building a WordPress site with a custom theme!

## Why this boilerplate?

This theme boilerplate is aimed at simplifying the work of the developer, by including steps and presets for most of the common actions when building a WordPress theme, like:

- Composer enabled development
- Theme registration with WordPress
- Folder structure
- Setting up scripts and styles assets
- Assets compilation with Laravel Mix predefined
- Config folder for configurations that do not need to go in the database
- Removal of many WordPress default features, like unneeded elements in the `<head>`
- Maintenance mode
- Login by email
- and a few others

## Dependencies

This boilerplate is intended to be used along the WP5-BANG package, which contains part of the functionality listed above. Additionally, you can install WP5-BANG-META package to easily configure custom meta fields, as well as any other package needed.

## Workflow and environments

The package contains a *wp-config-sample.php*, which adds several improvements (mostly security) over WordPress standard install:

- renaming *wp-content* folder
- solving some issues in local installations
- decreasing number of post revisions saved to the posts table
- changing table prefix
- disable pingbacks

### Using GIT with WordPress

The package sets up a GIT + composer environment for WordPress without interfering with native WordPress functionality. WordPress files are gitignored, so updating WordPress is through the built-in updater. All plugins and themes, except the ones you develop yourself, should also be gitignored.

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
