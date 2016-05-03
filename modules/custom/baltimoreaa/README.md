 # A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Baltimore Intergroup Council of Alcoholics Anonymous"
drush en -y baltimoreaa
drush aci baltimoreaa
```

## Import SQL data

Given an export of data from the existing site, place it in this directory as 
baltimmore.sql. This import will create a table named 'meeting_directory'. The
Anon Intergroup data structure uses three entities, so this single database table
needs to be split into it's three separate components: locations, groups, and
meetings. To do this, run

```
drush sqlc < baltimore-import.sql
```

Once we have the three separate tables, we can easily convert the table to JSON.
And then store that JSON in the anoncontent directory for export and import into
Drupal.

## Add content

If you edit or add anon content, then run

```
drush anon-content-export baltimoreaa
```

git diff to review the changes and create a github pull request.
Files in baltimoreaa/anoncontent/* should be changed.

## See also

* https://github.com/anondrupal/anonintergroup/blob/master/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/master/README.txt
