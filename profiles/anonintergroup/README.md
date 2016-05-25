# A Drupal install profile for 12 Step (Anonymous) Intergroups / Central Offices

This will be a Drupal 8.x install profile for Alcoholics Anonymous (and other 12 Step) Intergroups (also known as Central Offices or Central Service Offices). Collaboration or use of this project does not imply membership in AA or in any other 12 step fellowship. Should you be in a 12 step fellowship and have concerns regarding anonymity, we suggest you open an anonymous drupal.org account before commenting or contributing here. We will always maintain an individual's anonymity.

This project is new and a also work-in-progress. Should you wish to join the group developing this project, please contact me. We are having weekly meetings on Friday afternoons to collaborate on the tasks and stay on target for weekly progress.

A sample site using the profile is available at http://anonintergroup.org/

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
  - From an empty install, select "Anonymous Intergroup"
  - Or with
```
$ drush -y si anonintergroup --site-name="Baltimore Central Service Office of Alcoholics Anonymous"
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

## Create new entities, this was how anonmeetings and anongroups modules were created

  - Install the drupal console, https://drupalconsole.com/
  - To create a new module, from docroot $ drupal generate:module
  - To create new entities, from docroot $ drupal generate:entity:content

## Export and Import content

  The content specific to this profile should be migrated. See baltimoreaa_migrate as an example.

## See also

* https://www.drupal.org/node/159730 Developing installation profiles and distributions
