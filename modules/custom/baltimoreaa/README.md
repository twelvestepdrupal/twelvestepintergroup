# A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup
drush en -y baltimoreaa
```

# Import CSV data

Because baltimoreaa is an example in anonmigrate, the migration_templates
here don't need to exist. However, the example is just that, only an
example. We maintain the actual migration templates here.

The tag here is 'baltimoreaa'. The tag in the examples is 'baltimoreaa-example'.

* uninstall the migrate module now that it is no longer needed.
```
drush pmu -y anonmigrate
```

## See also

* https://github.com/anondrupal/anonintergroup/blob/develop/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/develop/README.txt
