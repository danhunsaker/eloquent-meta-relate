Eloquent Meta-Relate
====================
Extend Eloquent relationships to support implied polymorphism and merging

Installation
------------
Use composer:

```
composer require danhunsaker/eloquent-meta-relate
```

Nothing to it!

Usage
-----
Include the `Danhunsaker\Eloquent\Traits\MetaRelate` trait on any model you want
to extend:

```php
use Danhunsaker\Eloquent\Traits\MetaRelate;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use MetaRelate;
}
```

### Implied Polymorphic Relationships ###

Now you can create implied polymorphic relationships simply by providing an
array of class names, instead of a single class, wherever multiple models are
part of the same relationship:

```php
    public function files()
    {
        return $this->hasMany([
            'App\Models\Audio',
            'App\Models\Document',
            'App\Models\Image',
            'App\Models\Video',
        ]);
    }
```

### Relationship Merging ###

You can also merge existing relationships:

```php
    public function forkedTo()
    {
        return $this->hasMany('App\Models\Fork', 'original_id')->with('fork');
    }

    public function forkedFrom()
    {
        return $this->hasMany('App\Models\Fork', 'fork_id')->with('original');
    }

    public function allForks()
    {
        return $this->createMetaRelation()
            ->merge($this->forkedTo())
            ->merge($this->forkedFrom());
    }
```

Contributions
-------------
Contributions (issues, pull requests, etc) are always welcome on GitHub.

If you find a security issue, please [email me
directly](mailto:dan.hunsaker+metarel@gmail.com).
