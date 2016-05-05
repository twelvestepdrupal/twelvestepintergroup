# A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Baltimore Intergroup Council of Alcoholics Anonymous"
drush en -y baltimoreaa
```

# Import CSV data

The CSV export included here contains old data. Before enabling this
module update the CSV file with a recent export, and then:

```
drush en -y migrate_tools migrate_source_csv migrate_drupal
```

### @todo: Don't enable migrate_drupal, see https://www.drupal.org/node/2560795

## See also

* https://github.com/anondrupal/anonintergroup/blob/develop/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/develop/README.txt
