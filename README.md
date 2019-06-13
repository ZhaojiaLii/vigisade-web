# Vigisade

* [Guidelines](#guidelines)
* [Git Workflow](#git-workflow)
* [API documentation](#api-documentation)
* [Make commands](#make-commands)

## Guidelines

__PHP__
* Codebase MUST follow [PHP Standards Recommendations](https://www.php-fig.org/psr/);
* All files MUST use the english language;
* Variables names, classes names and other names MUST be [descriptive](https://hackernoon.com/the-art-of-naming-variables-52f44de00aad);
* Comments MUST be [helpful](https://blog.codinghorror.com/code-tells-you-how-comments-tell-you-why/) for other developers;

__Symfony__
* Annotations MUST NOT be used EXCEPT for doctrine entities.
* Translations MUST use the `translations/` directory.
* DataFixtures MUST be set when creating a new doctrine entity.

__Git__
* Commit messages MUST follow this pattern: `RM #{redmineId} Do something`;
* Feature branch MUST follow this pattern `{name}-{redmineId}` (ex: `gmar-81000`);
    * If you work on an already merged branch, you SHOULD add a suffix: `gmar-81000-b`;


## Git Workflow

* Each redmine issue MUST have a dedicated feature branch;
* Each feature branch MUST be merged into a sprint branch using a Gitlab Merge Request;
* At the end of a sprint, the sprint branch MUST be merged into master;


## Api documentation

For a quick look on all API routes, you can find a Postman collection in `doc/vigisade.postman_collection.json`.


## Make commands

* When working from scratch, you should initialize the database:
    * Without fixtures: `make db-prepare`
    * With fixtures: `make db-prepare-fixtures`
* You can reset the database with`db-reset`. It resets migrations files.

