# Import CSV or JSON data

* Create migration templates for each entity type by copying the
example-csv or example-json files here to a custom modules
migration_templates/ directory.

* Change the id, label, and migration_tags.

For example, change:

```
id: example_csv_locations
label: Import Example CSV Locations
migration_tags: [ example-csv ]
```

To:

```
id: mycity_locations
label: Import My Cities AA Locations
migration_tags: [ mycity ]
```

Remember to also change the dependency in the meeting example to to
the new id in your copied file.

* Change the source keys.

The source keys are what uniquely identifies the row for the entity.

For example, in the csv example data, a meeting is uniquely identified
by it's mID (group number) and meeting time. The 'anonintergroup' source
knows that meetings are repeating, so the unique part is the meeting time
and not the meeting time and day. If two different meetings were held in
the same location at the same time, on different days, whatever key
identifies these as unique meetings needs to be part of the source id key.

In the csv example data, the location is uniquely identified by
the four parts of an address.

* Change the day key

The day field can have a numeric value or a string. If it's a numeric value,
the assumption is that 0 is for sunday, 1 for monday, and so on. If 1 is for
sunday, just add a :1.


* Change the process.

This is the critical part of your migration and depends on your input file.

You have access to all of Drupal 8's core migration process's.

Additionally, if the process transformation is more complex, you can create
a custom migration module, and implement custom process transformations.

* Change the process format map.

@todo: write something here

* Run your import:

```
drush enable -y anonmigrate
drush script 'anonmigrate_import("mycity")'
```

* You can run the example import too. But beware that this will create
data and shouldn't be done on a production server.

```
drush script 'anonmigrate_import("example-csv")'
```

* Uninstall the migrate module now that it is no longer needed.

```
drush pmu -y anonmigrate
```
