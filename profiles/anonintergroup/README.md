# A Drupal install profile for 12 Step (Anonymous) Intergroups / Central Offices

This will be a Drupal 8.x install profile for Alcoholics Anonymous (and other 12 Step) Intergroups (also known as Central Offices or Central Service Offices). Collaboration or use of this project does not imply membership in AA or in any other 12 step fellowship. Should you be in a 12 step fellowship and have concerns regarding anonymity, we suggest you open an anonymous drupal.org account before commenting or contributing here. We will always maintain an individual's anonymity.

This project is new and a also work-in-progress. Should you wish to join the group developing this project, please contact me. We are having weekly meetings on Friday afternoons to collaborate on the tasks and stay on target for weekly progress.

## Drupal 8

This project implements a Drupal 8.x install profile (also known as a distribution).

The intent is to have a complete package that can be downloaded from drupal.org or installed on hosting platforms
such as Acquia or Pantheon.

## Steps used to create this profile and a new site

* Download latest Drupal 8
  - Install this profile in the profiles directory
  - Install composer $ curl -sS https://getcomposer.org/installer | php
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

## Create new entities, this was how anonmeetings and anongroups modules were created

  - Install the drupal console, https://drupalconsole.com/
  - To create a new module, from docroot $ drupal generate:module
  - To create new entities, from docroot $ drupal generate:entity:content

## Export and Import content

  The content specific to this profile can be exported and imported, useful for creating a new Intergroup offices.

  - Enable the anoncontent module
```
$ drush en -y anoncontent
```
  - Create a new custom site module using $ drupal generate:module and put it in modules/custom. For example, call this module baltimoreaa.
  - Export content using
```
$ drush anon-content-export baltimoreaa
```
    - This will create a subdirectory under modules/custom/baltimoreaa/anoncontent. This content can then be checked into the site repository.
  - Import content using
```
$ drush anon-content-import baltimoreaa
```
    - You may need to create a fresh site install before importing. The import assumes that the content does not yet exist.

## See also

* https://www.drupal.org/node/159730 Developing installation profiles and distributions
