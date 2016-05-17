# A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Baltimore Intergroup Council of Alcoholics Anonymous"
drush en -y baltimoreaa
```

# Import CSV data

* update the csv file. Then run:
```
drush enable -y anonmigrate
drush script 'anonmigrate_import("baltimoreaa")'
```
* uninstall the migrate module now that it is no longer needed.
```
drush pmu -y anonmigrate
```

## See also

* https://github.com/anondrupal/anonintergroup/blob/develop/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/develop/README.txt
