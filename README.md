Symfony HAL+JSON Transformer Bundle
=========================================
For Symfony 2 and Symfony 3

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/symfony2-hal-json-transformer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/symfony2-hal-json-transformer/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3db88526-561a-4969-a734-cff5cedb5afb/mini.png)](https://insight.sensiolabs.com/projects/3db88526-561a-4969-a734-cff5cedb5afb) 
[![Latest Stable Version](https://poser.pugx.org/nilportugues/haljson-bundle/v/stable)](https://packagist.org/packages/nilportugues/haljson-bundle)
[![Total Downloads](https://poser.pugx.org/nilportugues/haljson-bundle/downloads)](https://packagist.org/packages/nilportugues/haljson-bundle)
[![License](https://poser.pugx.org/nilportugues/haljson-bundle/license)](https://packagist.org/packages/nilportugues/haljson-bundle)
[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://paypal.me/nilportugues)

- [Installation](#installation)
- [Usage](#usage)
    - [Creating the mappings](#creating-the-mappings)
- [Outputing API Responses](#outputing-api-responses)
    - [HAL+JSON Responses](#response-objects-haljsonresponsetrait)
- [Integration with NelmioApiDocBundleBundle](#integration-with-nelmioapidocbundlebundle)
- [Quality](#quality)
- [Contribute](#contribute)
- [Support](#support)
- [Authors](#authors)
- [License](#license)



## Installation

**Step 1: Download the Bundle**

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require nilportugues/haljson-bundle
```


**Step 2: Enable the Bundle**

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php
// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new NilPortugues\Symfony\HalJsonBundle\NilPortuguesSymfony2HalJsonBundle(),
        );
        // ...
    }
    // ...
}
```

## Usage

### Creating the mappings

**Mapping directory**

Mapping files should be located at the `app/config/serializer` directory. This directory must be created.

It can be also be customized and placed elsewhere by editing the `app/config/config.yml` configuration file:

```yml
# app/config/config.yml

nilportugues_hal_json:
    mappings: "%kernel.root_dir%/config/serializer/"

```

**Mapping files**

The HAL+JSON transformer works by transforming an existing PHP object into its JSON representation. For each object, a mapping file is required. 

Mapping files **must** be placed in the mappings directory. The expected mapping file format is `.yml` and  will allow you to rename, hide and create links relating all of your data.

For instance, here's a quite complex `Post` object to demonstrate how it works:

```php
$post = new Post(
    new PostId(9),
    'Hello World',
    'Your first post',
    new User(
        new UserId(1),
        'Post Author'
    ),
    [
        new Comment(
            new CommentId(1000),
            'Have no fear, sers, your king is safe.',
            new User(new UserId(2), 'Barristan Selmy'),
            [
                'created_at' => (new DateTime('2015/07/18 12:13:00'))->format('c'),
                'accepted_at' => (new DateTime('2015/07/19 00:00:00'))->format('c'),
            ]
        ),
    ]
);
```

And the series of mapping files required:

```yml
# app/config/serializer/acme_domain_dummy_post.yml

mapping:
  class: Acme\Domain\Dummy\Post
  alias: Message
  aliased_properties:
    author: author
    title: headline
    content: body
  hide_properties: []
  id_properties:
    - postId
  urls:
    self: get_post ## @Route name
    comments: get_post_comments ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}
```

```yml
# app/config/serializer/acme_domain_dummy_value_object_post_id.yml

mapping:
  class: Acme\Domain\Dummy\ValueObject\PostId
  aliased_properties: []
  hide_properties: []
  id_properties:
  - postId
  urls:
    self: get_post  ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}
```


```yml
# app/config/serializer/acme_domain_dummy_comment.yml

mapping:
  class: Acme\Domain\Dummy\Comment
  aliased_properties: []
  hide_properties: []
  id_properties:
    - commentId
  urls:
    self: get_comment ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}
```

```yml
# app/config/serializer/acme_domain_dummy_value_object_comment_id.yml

mapping:
  class: Acme\Domain\Dummy\ValueObject\CommentId
  aliased_properties: []
  hide_properties: []
  id_properties:
    - commentId
  urls:
    self: get_comment ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}
```


```yml
# app/config/serializer/acme_domain_dummy_user.yml

mapping:
  class: Acme\Domain\Dummy\User
  aliased_properties: []
  hide_properties: []
  id_properties:
  - userId
  urls:
    self: get_user
    friends: get_user_friends  ## @Route name
    comments: get_user_comments  ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}    
```


```yml
# app/config/serializer/acme_domain_dummy_value_object_user_id.yml

mapping:
  class: Acme\Domain\Dummy\ValueObject\UserId
  aliased_properties: []
  hide_properties: []
  id_properties:
  - userId
  urls:
    self: get_user  ## @Route name
    friends: get_user_friends  ## @Route name
    comments: get_user_comments  ## @Route name
  curies:
    name: example
    href: http://example.com/docs/rels/{rel}    
```


## Outputing API Responses

It is really easy, just get an instance of the `HalJsonSerializer` from the **Service Container** and pass the object to its `serialize()` method. Output will be valid JSON-API.

Here's an example of a `Post` object being fetched from a Doctrine repository.

Finally, a helper trait, `HalJsonResponseTrait` is provided to write fully compilant responses wrapping the PSR-7 Response objects provided by the original JSON API Transformer library.

```php
<?php
namespace AppBundle\Controller;

use NilPortugues\Symfony\HalJsonBundle\Serializer\HalJsonResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    use HalJsonResponseTrait;

    /**
     * @\Symfony\Component\Routing\Annotation\Route("/post/{postId}", name="get_post")
     *
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPostAction($postId)
    {
        $post = $this->get('doctrine.post_repository')->find($postId);
        
        $serializer = $this->get('nil_portugues.serializer.hal_json_serializer');

        /** @var \NilPortugues\Api\HalJson\HalJsonTransformer $transformer */
        $transformer = $serializer->getTransformer();
        $transformer->setSelfUrl($this->generateUrl('get_post', ['postId' => $postId], true));
        $transformer->setNextUrl($this->generateUrl('get_post', ['postId' => $postId+1], true));

        return $this->response($serializer->serialize($post));
    }
} 
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

## Integration with NelmioApiDocBundleBundle

The [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle/blob/master/Resources/doc/index.md) is a very well known bundle used to document APIs. Integration with the current bundle is terrible easy. 

Here's an example following the `PostContoller::getPostAction()` provided before:

```php
<?php
namespace AppBundle\Controller;

use NilPortugues\Symfony\HalJsonBundle\Serializer\HalJsonResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    use HalJsonResponseTrait;

    /**
     * Get a Post by its identifier. Will return Post, Comments and User data.
     *
     * @Nelmio\ApiDocBundle\Annotation\ApiDoc(
     *  resource=true,
     *  description="Get a Post by its unique id",
     * )
     *
     * @Symfony\Component\Routing\Annotation\Route("/post/{postId}", name="get_post")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method({"GET"})
     *
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPostAction($postId)
    {
        $post = $this->get('doctrine.post_repository')->find($postId);
        
        $serializer = $this->get('nil_portugues.serializer.hal_json_serializer');

        /** @var \NilPortugues\Api\HalJson\HalJsonTransformer $transformer */
        $transformer = $serializer->getTransformer();
        $transformer->setSelfUrl($this->generateUrl('get_post', ['postId' => $postId], true));
        $transformer->setNextUrl($this->generateUrl('get_post', ['postId' => $postId+1], true));

        return $this->response($serializer->serialize($post));
    }
} 
```


And the recommended configuration to be added in `app/config/config.yml`

```yml
#app/config/config.yml

nelmio_api_doc:
  sandbox:
        authentication:
          name: access_token
          delivery: http
          type:     basic
          custom_endpoint: false
        enabled:  true
        endpoint: ~
        accept_type: ~
        body_format:
            formats: []
            default_format: form
        request_format:
            formats:
                json: application/hal+json
            method: accept_header
            default_format: json
        entity_to_choice: false
```        


## Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/) and [PSR-7](http://www.php-fig.org/psr/psr-7/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/symfony-hal-json-transformer/pulls).


## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/symfony-hal-json-transformer/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/symfony-hal-json-transformer).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/symfony-hal-json-transformer/issues/new)
 - Using Gitter: [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/nilportugues/symfony-hal-json-transformer?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)


## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/symfony-hal-json-transformer/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
