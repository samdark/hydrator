Hydrator
========

Hydrator can be used for two purposes:

- To extract data from a class to be futher stored in a persistent storage.
- To instantiate a class having its data.

In both cases it is saving and filling protected and private properties without calling
any methods which leads to ability to persist state of an object with properly incapsulated
data.

[![Latest Stable Version](https://poser.pugx.org/samdark/hydrator/v/stable.png)](https://packagist.org/packages/samdark/hydrator)
[![Total Downloads](https://poser.pugx.org/samdark/hydrator/downloads.png)](https://packagist.org/packages/samdark/hydrator)
[![Build Status](https://travis-ci.org/samdark/hydrator.svg?branch=master)](https://travis-ci.org/samdark/hydrator)


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require --prefer-dist samdark/hydrator
```

## Usage

Consider we have a `Post` entity which represents a blog post. It has a title and a text. A unique id is generated to
identify it.

```php
class Post
{
    private $id;
    protected $title;
    protected $text;

    public function __construct($title, $text)
    {
        $this->id = uniqid('post_', true);
        $this->title = $title;
        $this->text = $text;
    }
   
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function setText()
    {
        return $this->text;
    }
}
```

Saving a post to database:

```php
$post = new Post('First post', 'Hell, it is a first post.');

$postHydrator = new \samdark\hydrator\Hydrator([
    'id' => 'id',
    'title' => 'title',
    'text' => 'text',
]);

$data = $postHydrator->extract($post);
save_to_database($data);
```

Loading post from database:

```php
<?php
$data = load_from_database();

$postHydrator = new \samdark\hydrator\Hydrator([
    'id' => 'id',
    'title' => 'title',
    'text' => 'text',
]);

$post = $postHydrator->hydrate($data, Post::class);
echo $post->getId();
```
