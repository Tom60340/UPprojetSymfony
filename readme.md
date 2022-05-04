### UP projet blog Studi.

### CONFIGURATION :

Création de l'app:

```
symfony new symfony --webapp
```

Création de l'entité Article:

```
php bin/console make:entity Article
```

Création de l'entité User:

```
php bin/console make:user
```

Création de la DB puis de la migration et e nfin d:m:m :

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migration:migrate
```

!!! Oubli de la property author dans l'entité Article en ManyToOne:

```
php bin/console make:entity Article
```

En fin il faut modifier la DB:

```
php bin/console doctrine:schema:update --force
```

### CREATION :

Création des controllers:

```
php bin/console make:controller HomeController
php bin/console make:controller ArticleController
```

Création du formulaire:

```
php bin/console make:form
```

Création du formulaire d'enregistrement:

```
php bin/console make:registration-form
```
