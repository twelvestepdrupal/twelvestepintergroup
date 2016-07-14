# A Drupal install profile for Twelve Step Intergroups

This will be a Drupal 8.x install profile for Twelve Step (AA, Alanon, NA, CA, etc...) Intergroups (Intergroups are also known as Central Offices or Central Service Offices). Collaboration or use of this project does not imply membership in AA or in any other 12 step fellowship. Should you be in a 12 step fellowship and have concerns regarding anonymity, we suggest you open an anonymous drupal.org account before commenting or contributing here. We will always maintain an individual's anonymity.

This project is new and a also work-in-progress. Should you wish to join the group developing this project, please contact me. We are having weekly meetings on Friday afternoons to collaborate on the tasks and stay on target for weekly progress.

A sample site using the profile is available at http://twelvestepintergroup.org/

## Drupal 8

This project implements a Drupal 8.x install profile (also known as a distribution).

The intent is to have a complete package that can be downloaded from drupal.org or installed on hosting platforms
such as Acquia or Pantheon.

## Steps used to create this profile and a new site

* Download latest Drupal 8
  - Install this profile in the profiles directory
  - Install composer
```
$ curl -sS https://getcomposer.org/installer | php
```
    - Alternatively
```
$ drush dl composer, and then use drush composer instead of composer.phar
```
* Install a new site
  - From an empty install, select "Twelve Step Intergroup"
  - Or with
```
$ drush -y si twelvestepintergroup --site-name="Baltimore Central Service Office of Alcoholics Anonymous"
```
  - After installing the new site, move the settings changes to settings.local.php, and then reset the changes.
```
$ git diff
$ git reset --hard
```

* Download latest chosen jquery plugin to libraries/chosen.
```
$ drush chosenplugin
```

### @todo: install chosen with composer.

## Export and Import content

The content specific to this profile should be migrated.

Create the migration templates from the examples in the twelvestepmigrate/migration_templates directory,
enable the twelvestepmigrate module, then import your the content based on the migration_tag.

```
  drush en twelvestepmigrate
```

Or in your custom modules hook_install().

```
  \Drupal::service('module_installer')->install(['twelvestepmigrate']);
  twelvestepmigrate_import('example-csv');
```

## See also

* https://www.drupal.org/node/159730 Developing installation profiles and distributions
