# Ejercicio de entrevista técnica

## Contexto

Gran parte de nuestro trabajo es hacer la vida más fácil a nuestros compañeros
que hacen aplicaciones (web o nativas). Para ello creamos APIs obteniendo
datos de terceros o propios.

Para este ejercicio tenemos que ayudar a nuestros compañeros a hacer una
aplicación de búsqueda y listado. 

## Especificaciones

Necesitamos construir un API de libros. Para obtener los datos de los libros se utilizará la API de [gutendex](https://gutendex.com/).

Las aplicacion debe tener estos dos endpoints:

- Busqueda mediante una cadena de caracteres. ( Usar Lists of Books de Gutendex )
- Mostrar los datos de un libro según el id especificado. (Usar Individual Books de gutendex)

La solución del ejercicio debe ser enviada en un repositorio de GitHub, GitLab o Bitbucket con el historial completo de git.

# Requisitos

La aplicación debe cumplir estos requisitos:

- Usar Symfony como Framework
- Debe ser un API REST y tener JSON como formato de salida.
- Los campos a mostrar serán:
```  
  "id": <number of Project Gutenberg ID>,
  "title": <string>,
  "subjects": <array of strings>,
  "authors": <array of Persons>,
```
- Debe estar construida en Arquitectura Hexagonal y DDD
- La aplicación debe cumplir los estandares [PSR-2]
- Se deben construir test unitarios sin atacar al API ( Mockear API )

## Extras

Como mejora de la aplicación puedes implementar las siguientes funcionalidades.

- Cachear las peticiones a Gutendex temporalmente mediante FileSystem o Redis
- Construir documentacion del API mediante OpenAPI. Puedes usar [NelmioAPIBundle] u otra librería para ello.
- Crear test funcionales mediante Behat 


[PunkApi]: https://punkapi.com/documentation/v2](https://gutendex.com/
[NelmioAPIBundle]: https://symfony.com/bundles/NelmioApiDocBundle/current/index.html
[PSR-2]: http://www.php-fig.org/psr/psr-2/
