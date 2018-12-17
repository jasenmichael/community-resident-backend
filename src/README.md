# Community Resident API and Site

#### folder structure

```
api/
  public/
    index.php
    .htaccess
  src/
    config/
      db.php
    routes/
      resident/
        login.php
        list.php
        add.php
        update.php ***
        delete.php ***

      files/
        archive.php
        delete.php  *** {file: path/file}
        upload.php *** {path, name, file}
      forms/
          work/
            delete.php
            list.php
            post.php
            update.php
            
          receipts/ ***
            post.php ***

  vendor/
  composer.json
 ``` 

#### authentication

- resident logs in with username and password, gets a token
  - get hashed pass from db and verify
  - verify with password
- token is used for all api requests


### database

residents
  id
  uuid
  name
  last
  email
  phone
  emergency_contact
  residence
  role
  date_created
  date_updated
  visitor

work

residents