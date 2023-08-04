# Miscelaneous Application Notes
[UML Class Diagrams](https://www.lucidchart.com/pages/uml-class-diagram)

## UML Visibility in JDS UML Documents
<span>+</span> public

<span>-</span> private

<span>#</span> protected

<span>~</span> package

<span>^</span> static

### Framework Hiarchy
* index - Front Controller
	* Container Umbrella with Reflection
		* Kernel - Heart or Core of the Framework
			* Router - provide the routes
			* Request - receive a request
			* Response - provide a response
		* Exceptions - Developer Error Reporting
			* User Friendly errors

## Foundation for Sites
`css code()`
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
```
`js code()`
```html
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
```
### Save for now
`php code()`
```php
	// this will allow [a-zA-Z0-9] no special characters
	$routeCollector->addRoute('GET', '/user/{userid:\w+}', function ($routeParams) {
		$content = "<h1>This is Post {$routeParams['userid']}</h1>";

		return new Response($content);
	});
```
```
Check out phpoffice/phpspreadsheet on packagist
```

1. Database abstraction: Doctrine DBAL provides an abstraction layer that allows developers to interact with different databases using a common API. This means that developers can write database-independent code, making it easier to switch between different databases without having to rewrite application code.
2. Query building: Doctrine DBAL provides a query builder that allows developers to write SQL queries in a more object-oriented way. This makes it easier to write complex queries and reduces the likelihood of SQL injection attacks.
3. Prepared statements: Prepared statements are a technique for preventing SQL injection attacks. Doctrine DBAL supports prepared statements, making it easy for developers to write secure database queries.
4. Performance: Doctrine DBAL is designed to be fast and efficient. It uses caching and other optimization techniques to minimize database access and improve performance.
5. Integration with other Doctrine components: Doctrine DBAL integrates well with other Doctrine components, such as the ORM and the Migrations tool. This makes it easy to build complex applications using a consistent set of tools and APIs.
