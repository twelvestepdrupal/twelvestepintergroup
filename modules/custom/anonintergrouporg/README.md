This is the basic for the site http://anonintergroup.org, which is an example site of the Drupal profile.

The site is a CI site recreated using:

```
drush sql-drop -y
drush -y si anonintergroup --site-name="Anonymous Intergroup Example Site"
drush en -y anonintergrouporg
```
