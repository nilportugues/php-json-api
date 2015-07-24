# Contributing

Here are a few guidelines and rules to follow when you'd like to contribute to the project:

- Follow [PSR-1](http://www.php-fig.org/psr/1/) 
- Follow [PSR-2](http://www.php-fig.org/psr/2/) 
 
Please ensure that your code fulfills these standards before any Pull Request (PR) by running the following tools found in the bin/ directory after `composer install`.

``` bash
php bin/php-cs-fixer fix src
php bin/php-formatter formatter:use:sort src/

php bin/php-cs-fixer fix tests
php bin/php-formatter formatter:use:sort tests/
```

There is also a policy for contributing to this project. Pull requests must be explained step by step to make the review process easy in order to accept and merge them. New features must come paired with Unit and/or Functional
tests.
