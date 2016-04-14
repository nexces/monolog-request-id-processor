monolog-request-id-processor
=====

A Monolog processor to add X-Request-Id or UNIQUE_ID env to monolog stack if present, provide a default one otherwise. Optionally, add a forwarded request-id list. 

Some resources about X-Request-Id (almost a standard) :  

 - https://devcenter.heroku.com/articles/http-request-id
 - https://packagist.org/packages/php-middleware/request-id
 - https://blog.viaduct.io/x-request-id/
