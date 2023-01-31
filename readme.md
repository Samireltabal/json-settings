## SESettings Lib
    Library for saving wep app settings in json file with caching. 
# description
    saves settings in json file.. so it can be retrieved quickly without delay time.
# usage
- publish config file
  - php artisan vendor:publish --tag=config
# Limitation
  - limited to two levels example
    - allowed : `app()->Settings->get('parent.sub')` .
    - not allowed : `app()->Settings->get('parent.sub.extra')` will throw exception. 
# api
- get all settings
  - app()->Settings->get()
- get settings by key
  - app()->Settings->get('parent.sub')
- get settings by key
  - app()->Settings->set('parent.sub', 'some-value')
# Development
  - run 
    - composer install 
    - composer test
    - composer analyse
    - composer format
