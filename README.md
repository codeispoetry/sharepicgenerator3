# Sharepicgenerator

## Installation
```
composer install
npm install
npm run scss
npm run js
make shell -> php install.php

mkdir node_modules/quill/dist/
mkdir logs && chmod 777 logs
mkdir users && chmod 777 users
mkdir tmp && chmod 777 tmp
chmod 777 data
chmod 777 data/users.db
```

Create a config-file from config.iniSAMPLE.

Create templates-css-files with
```
npm run de:scss
```

## Translation
```bash
make translation-prepare
make translate
make stop up
```

## CLI
```bash
./cli.php create <user> <password>
./cli.php set_role <user> <role: admin>
./cli.php delete <user>

```