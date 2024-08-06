To rise the api, just run the docker compose command:

```
docker compose up -d --build
```

To choose to create a database script:

```
http://localhost:1090/?database=your_database_name
```

To create just a table script:

```
http://localhost:1090/?tables[]=table_name
```

Several tables may be created at once:
```
http://localhost:1090/?tables[]=neighborhood&tables[]=cities
```

Create a table with fields:
```
http://localhost:1090/?tables[]=neighborhood&fields[id]=INT:KEY&fields[code]=VARCHAR(255)&fields[name]=VARCHAR(255)
```
