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
http://localhost:1090/?table=table_name
```

Create a table with fields:
```
http://localhost:1090/?table=bairros&fields[id]=INT:KEY&fields[code]=VARCHAR(255)&fields[name]=VARCHAR(255)
```
