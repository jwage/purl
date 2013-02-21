Purl
====

Purl is a simple Object Oriented URL manipulation library for PHP 5.3+

[![Build Status](https://secure.travis-ci.org/jwage/purl.png?branch=master)](http://travis-ci.org/jwage/purl)

Using Purl
----------

Creating Url instances is easy:

```php
<?php

$url = new \Purl\Url('http://jwage.com');
```

You can also create `Url` instances through the static `parse` method if you prefer that style:

```php
<?php

$url = \Purl\Url::parse('http://jwage.com');
```

One benefit of using this method is you can chain methods together after creating the `Url`:

```php
<?php

$url = \Purl\Url::parse('http://jwage.com')
	->set('scheme', 'https')
	->set('port', '443')
	->set('user', 'jwage')
	->set('pass', 'password')
	->set('path', 'about/me')
	->set('query', 'param1=value1&param2=value2')
	->set('fragment', 'about/me?param1=value1&param2=value2');

echo $url->getUrl(); // https://jwage:password@jwage.com:443/about/me?param1=value1&param2=value2#about/me?param1=value1&param2=value2

// $url->path becomes instanceof Purl\Path
// $url->query becomes instanceof Purl\Query
// $url->fragment becomes instanceof Purl\Fragment
```

### Path Manipulation

```php
<?php
$url = new \Purl\Url('http://jwage.com');

// add path segments one at a time
$url->path->add('about')->add('me');

// set the path data from a string
$url->path = 'about/me/another_segment'; // $url->path becomes instanceof Purl\Path

// get the path segments
print_r($url->path->getData()); // array('about', 'me', 'another_segment')
```

### Query Manipulation

```php
<?php
$url = new \Purl\Url('http://jwage.com');
$url->query->set('param1', 'value1');
$url->query->set('param2', 'value2');

echo $url->query; // param1=value1&param2=value2
echo $url; // http://jwage.com?param1=value1&param2=value2

// set the query data from an array
$url->query->setData(array(
	'param1' => 'value1',
	'param2' => 'value2'
));

// set the query data from a string
$url->query = 'param1=value1&param2=value2'; // $url->query becomes instanceof Purl\Query
print_r($url->query->getData()); //array('param1' => 'value1', 'param2' => 'value2')
```

### Fragment Manipulation

```php
<?php
$url = new \Purl\Url('http://jwage.com');
$url->fragment 'about/me?param1=value1&param2=value2'; // $url->fragment becomes instanceof Purl\Fragment
```

A Fragment is made of a path and a query and comes after the hashmark (#).

```php
<?php
echo $url->fragment->path; // about/me
echo $url->fragment->query; // param1=value1&param2=value2
echo $url; // http://jwage.com#about/me?param1=value1&param2=value2
```

### Domain Parts

Purl can parse a URL in to parts and its canonical form. It uses the list of domains from http://publicsuffix.org to break the domain into its public suffix, registerable domain, subdomain and canonical form.

```php
<?php
$url = new \Purl\Url('http://about.jwage.com');

echo $url->publicSuffix; // com
echo $url->registerableDomain; // jwage
echo $url->subdomain; // about
echo $url->canonical; // com.jwage.about/
```

### Extract URLs

You can easily extract urls from a string of text using the `extract` method:

```php
<?php
$string = 'some text http://google.com http://jwage.com';
$urls = \Purl\Url::extract($string);

echo $urls[0]; // http://google.com/
echo $urls[1]; // http://jwage.com/
```

### Join URLs

You can easily join two URLs together using Purl:

```php
<?php
$url = new \Purl\Url('http://jwage.com/about?param=value#fragment');
$url->join('http://about.me/jwage');
echo $url; // http://about.me/jwage?param=value#fragment
```

Or if you have another `Url` object already:

```php
<?php
$url1 = new \Purl\Url('http://jwage.com/about?param=value#fragment');
$url2 = new \Purl\Url('http://about.me/jwage');
$url1->join($url2);
echo $url1; // http://about.me/jwage?param=value#fragment
```
