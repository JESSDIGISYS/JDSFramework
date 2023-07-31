# Miscelaneous Notes
[UML Class Diagrams](https://www.lucidchart.com/pages/uml-class-diagram)

## UML Visibility in JDS UML Documents
<span>+</span> public

<span>-</span> private

<span>#</span> protected

<span>~</span> package

<span>^</span> static

### Framework Hiarchy
	index - Front Controller

		Kernel - Heart or Core of the application

			Request - receive a request

			Response - provide a response


			// // this will allow [a-zA-Z0-9] no special characters
			// $routeCollector->addRoute('GET', '/user/{userid:\w+}', function ($routeParams) {
			// 	$content = "<h1>This is Post {$routeParams['userid']}</h1>";

			// 	return new Response($content);
			// });

