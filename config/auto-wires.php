<?php

// PHP-DI will take all the definitions it can find and compile them.
// That means that definitions like autowired classes that are not listed in the configuration
// cannot be compiled since PHP-DI doesn't know about them.
// see: https://php-di.org/doc/performances.html#optimizing-for-compilation

// Here you can list the auto wired files the container does not know about,
// They will then be used to compile the container, which in turn will give
// the app a performance boost.
//
// I tried to create a mechanism that fetches these classes automatically..
// But I failed :'(

return [
];
