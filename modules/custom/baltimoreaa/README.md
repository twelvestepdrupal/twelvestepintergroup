 # A Drupal install profile for the Baltimore Intergroup Council of Alcoholics Anonymous

## Install a new site

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Baltimore Intergroup Council of Alcoholics Anonymous"
drush en -y baltimoreaa
drush aci baltimoreaa
```

## Add content

If you edit or add anon content, then run

```
drush ace baltimoreaa
```

git diff to review the changes and create a github pull request.
Files in baltimoreaa/anoncontent/* should be changed.

## See also

* https://github.com/anondrupal/anonintergroup/blob/master/profiles/anonintergroup/README.md 
* https://github.com/anondrupal/anonintergroup/blob/master/README.txt
