#FriendsGraph API
Simple friend graph app represented as RESTful API.

##Requirements
* [Composer](https://getcomposer.org/download/)
* PHP 5.6
* [Neo4j Graph Database - CE](http://neo4j.com/download/)

##Setup
1. `git clone https://github.com/RagnarIndie/friendsgraph.git`;
2. Point webroot for a desired virtual host to friendsgraph/web, also you need to turn on mod_rewrite;
3. Install Neo4j CE for your platform;
4. Edit app config file friendsgraph/config/dev.json and configure Neo4j connection params in db section;
5. Run `composer install` inside friendsgraph root dir. Also github-token may be requested by composer;

##Working with API
*Exapmle domain for this doc* - `friendsgraph.dev`

All available URIs and HTTP-verbs for this RESTful API you can see on index app page - http://friendsgraph.dev/

First of all, you need sample nodes (users) in your graph db. Just open a following URL - http://friendsgraph.dev/service/generate
Now you have 200 generated nodes without any relations between them.

After a successfull node generation you will see 200 OK response:
```
{
    success: true
}
```
**Warning! You don`t need to run it twice. Everytime you run this script nodes will be overwritten.**

Now you can list all generated users using RESTful API:
`GET http://friendsgraph.dev/api/0.1/users`

##API interface structure
* Default API-basepath - /api/0.1
* The app uses 3 types of resource collections:
    1. **/users** - user collection. You can get a final resource (user node) by appending user_id to the user collection
    URI. For example, /users/c0fd5c
    2. **/users/{user_id}/requests** - ingoing/outgoing friendship requests collection. You can get a final resource (friendship request) by appending request_id to the user requests collection URI. For example, /users/c0fd5c/requests/d5c1dd3af63caf4
    3. **/users/{user_id}/friends** - User friends collection. For example, /users/c0fd5c/friends
* You can use following HTTP verbs (GET, PUT, POST, DELETE):
    * **GET /users** - full user collection without relations. Response - json array of User objects.
    Формат User:
    ```
    {
        id: "ac58fe65",
        name: "Fairy Tetzlaff",
        age: "55",
        countryCode: "BR",
        country: "Brazil"
    }
    ```
    * **POST /users** - create a new user. Following form fields are required: name, age, country, countryCode
    API will return HTTP code (200|404), status and id for a new user node:
    ```
    {
        success: true|false,
        id: {user_id|null}
    }
    ```
    * **GET /users/{user_id}** - get single User object by id
    * **PUT /users/{user_id}** - update single user node by id. Form fields are required: name, age, country, countryCode
    * **DELETE /users/{user_id}** - delete single user node by id

    * **GET /users/{user_id}/friends** - get array of friends for a single user
    Also you can get friends-of-friends list. Just append additional param to collection URI - ?level=n,
    For example, /users/{user_id}/friends?level=2 - friends-of-friends for user. 3 - friends-of-friends-of-friends, etc.
    * **DELETE /users/{user_id}/friends/{friend_id}** - delete single friend

    * **GET /users/{user_id}/requests** - returns array of the ingoing (in) and outgoing (out) friendship requests. Example request:
    ```
    {
        id: {id}, #request id
        from: {from_user_id}, #sender id
        to: {to_user_id}, #target user id
        created: {created} #unix timestamp
    }
    ```
    * **POST /users/{user_id}/requests** - create a new friendship request. user_id - request sender.
    Required form field: to_user_id - desired friend user id.
    API returns HTTP code (200|404) and id for a new request:
    ```
    {
        success: true|false,
        id: {request_id|null}
    }
    ```
    * **GET /users/{user_id}/requests/{request_id}** - get friendship request by request_id
    * **PUT /users/{user_id}/requests/{request_id}** - accept ingoing friendship request by request_id
    * **DELETE /users/{user_id}/requests/{request_id}** - reject ingoing friendship request by request_id
