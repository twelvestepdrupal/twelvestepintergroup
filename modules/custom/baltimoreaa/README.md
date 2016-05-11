# A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Baltimore Intergroup Council of Alcoholics Anonymous"
drush en -y baltimoreaa
```

# Import CSV data

* update the csv file in modules/baltimoreaa_migrate/baltimoreaa.csv
* enable the migrate module
```
drush en -y baltimoreaa_migrate
```
* uninstall the migrate module now that it is no longer needed.
```
drush pmu -y baltimoreaa_migrate
```

## See also

* https://github.com/anondrupal/anonintergroup/blob/develop/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/develop/README.txt
