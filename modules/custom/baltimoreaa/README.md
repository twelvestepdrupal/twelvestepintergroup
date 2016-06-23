# A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si twelvestepintergroup
drush en -y baltimoreaa
drush queue-run geolocation
```

# Import CSV data

The tag here is 'baltimoreaa'. The tag in the examples is 'baltimoreaa-example'.

* uninstall the migrate module now that it is no longer needed.
```
drush pmu -y twelvestepmigrate
```

## See also

* https://github.com/anondrupal/twelvestepintergroup/blob/develop/profiles/twelvestepintergroup/README.md 
* https://github.com/anondrupal/twelvestepintergroup/blob/develop/README.txt
