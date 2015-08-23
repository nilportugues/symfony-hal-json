Symfony2 HAL+JSON Transformer Bundle
=========================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/haljson-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/haljson-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3db88526-561a-4969-a734-cff5cedb5afb/mini.png)](https://insight.sensiolabs.com/projects/3db88526-561a-4969-a734-cff5cedb5afb) 
[![Latest Stable Version](https://poser.pugx.org/nilportugues/haljson-bundle/v/stable)](https://packagist.org/packages/nilportugues/haljson-bundle)
[![Total Downloads](https://poser.pugx.org/nilportugues/haljson-bundle/downloads)](https://packagist.org/packages/nilportugues/haljson-bundle)
[![License](https://poser.pugx.org/nilportugues/haljson-bundle/license)](https://packagist.org/packages/nilportugues/haljson-bundle)


## Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/haljson-bundle
```


**Output:**

```
HTTP/1.1 200 OK
Cache-Control: private, max-age=0, must-revalidate
Content-type: application/hal+json
```

```json
{
    "post_id": 9,
    "headline": "Hello World",
    "body": "Your first post",
    "_embedded": {
        "author": {
            "user_id": 1,
            "name": "Post Author",
            "_links": {
                "self": {
                    "href": "http://example.com/users/1"
                },
                "example:friends": {
                    "href": "http://example.com/users/1/friends"
                },
                "example:comments": {
                    "href": "http://example.com/users/1/comments"
                }
            }
        },
        "comments": [
            {
                "comment_id": 1000,
                "dates": {
                    "created_at": "2015-08-13T22:47:45+02:00",
                    "accepted_at": "2015-08-13T23:22:45+02:00"
                },
                "comment": "Have no fear, sers, your king is safe.",
                "_embedded": {
                    "user": {
                        "user_id": 2,
                        "name": "Barristan Selmy",
                        "_links": {
                            "self": {
                                "href": "http://example.com/users/2"
                            },
                            "example:friends": {
                                "href": "http://example.com/users/2/friends"
                            },
                            "example:comments": {
                                "href": "http://example.com/users/2/comments"
                            }
                        }
                    }
                },
                "_links": {
                    "example:user": {
                        "href": "http://example.com/users/2"
                    },
                    "self": {
                        "href": "http://example.com/comments/1000"
                    }
                }
            }
        ]
    },
    "_links": {
        "curies": [
            {
                "name": "example",
                "href": "http://example.com/docs/rels/{rel}",
                "templated": true
            }
        ],
        "self": {
            "href": "http://example.com/posts/9"
        },
        "next": {
            "href": "http://example.com/posts/10"
        },
        "example:author": {
            "href": "http://example.com/users/1"
        },
        "example:comments": {
            "href": "http://example.com/posts/9/comments"
        }
    },
    "_meta": {
        "author": [
            {
                "name": "Nil Portugués Calderó",
                "email": "contact@nilportugues.com"
            }
        ]
    }
}
```

#### Response objects (HalJsonResponseTrait)

The following `HalJsonResponseTrait` methods are provided to return the right headers and HTTP status codes are available:

```php
    private function errorResponse($json);
    private function resourceCreatedResponse($json);
    private function resourceDeletedResponse($json);
    private function resourceNotFoundResponse($json);
    private function resourcePatchErrorResponse($json);
    private function resourcePostErrorResponse($json);
    private function resourceProcessingResponse($json);
    private function resourceUpdatedResponse($json);
    private function response($json);
    private function unsupportedActionResponse($json);
```    


## Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/) and [PSR-7](http://www.php-fig.org/psr/psr-7/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/symfony2-haljson-transformer/pulls).


## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/symfony2-haljson-transformer/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/symfony2-haljson-transformer).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/symfony2-haljson-transformer/issues/new)
 - Using Gitter: [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/nilportugues/symfony2-haljson-transformer?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)


## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/symfony2-haljson-transformer/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
