---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_4bda5095341488cf7cb6da324a4bfadc -->
## Function to return suburb name suggestions

> Example request:

```bash
curl -X POST "http://localhost/api/searchlocation" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/searchlocation",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/searchlocation`


<!-- END_4bda5095341488cf7cb6da324a4bfadc -->

<!-- START_c30f2857a9f308cc435f5e1f8054b235 -->
## Function to calculate the best tank size for the location

> Example request:

```bash
curl -X POST "http://localhost/api/gettanksize" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/gettanksize",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/gettanksize`


<!-- END_c30f2857a9f308cc435f5e1f8054b235 -->

<!-- START_14b0c6cf5c4e49b9fbd7d918e4a931ee -->
## Function to calculate the water levels in the tank for an entire year

> Example request:

```bash
curl -X POST "http://localhost/api/getwaterlevels" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/getwaterlevels",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getwaterlevels`


<!-- END_14b0c6cf5c4e49b9fbd7d918e4a931ee -->

