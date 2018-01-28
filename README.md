# Laravel extended cast model

This library help you to make more advanced cast to eloquent model.

Compatible with laravel 5.4 and upper.

## Install

```
composer require grummfy/eloquent-extended-cast-model
```

## Usage

Using the trait to facilitate the usage of custom cast on model. There is two traits:
* CastableModel: allow you to use custom cast
* JsonCollectionCastable: allow you to use json collection on cast

See [example](example) directory for a possible usage.

### Custom casting in your model

* add the trait `CastableModel`
* in the $cast property of the model, add your field and the custom cast `'fooField' => 'bar'`
* add two methods: `toBar` and `fromBar` (Bar is the same name as the one present in the $cast property)

If you want to add some extra value to your cast method (like it's done in the JsonReadOnlyCollection), you can fill the property
`$castParameters` of your model: `'fooField' => ['argument1', 'argument2']`.

### Cast JsonReadOnlyCollection

This library came with a json collection. It's a collection of value that are saved in json. It's nice to use with a
collection of value object.

## TODO
* unit test
* QA tools
  * travis
  * styleci
  * scrutinizer
  * ...

## Note
The basic idea was inspired from https://github.com/reliese/laravel/blob/master/src/Database/Eloquent/Model.php
