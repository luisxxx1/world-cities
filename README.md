# Ciudades del mundo

Prueba técnica para el cargo de Analista de Soporte Nivel 1 en Infodec.

Hice una página web con Laravel y PHP para que Sofía pueda escoger un país y ver qué ciudades tiene y cuánta gente vive en cada una. La información sale de la base de datos World, que trabajé en MariaDB (la que viene con XAMPP).

Todo pasa en una sola pantalla: escoges el país y te aparecen las 10 ciudades con más gente, las 10 con menos gente y la lista completa. Como a Sofía también le interesan los idiomas, le agregué los idiomas que se hablan en cada país y qué tanto se hablan.

Se ve bien en computador, tablet y celular.

## Con qué la hice

- **PHP 8.2:** el lenguaje de programación
- **Laravel 12:** la herramienta con la que armé el proyecto, ordenado por partes
- **MariaDB (XAMPP):** donde está guardada la información de países y ciudades
- **Tailwind CSS:** para darle estilo a la página y que se adapte al celular
- **JavaScript:** para que las ciudades aparezcan sin tener que recargar la página
- **DbVisualizer:** para cargar y revisar la base de datos
- **Git y GitHub:** para guardar cada avance del proyecto

## Qué necesitas para usarla

- PHP 8.2 o más reciente, con la extensión `intl` activada
- Composer
- MySQL 8 o MariaDB 10.4 en adelante
- El archivo `world.sql`. Si no lo tienes, lo encuentras en la página oficial de MySQL: https://dev.mysql.com/doc/index-other.html (busca "world database")

## Cómo ponerla a funcionar

1. Descarga el proyecto y entra a la carpeta:

```bash
   git clone https://github.com/TU-USUARIO/world-cities.git
   cd world-cities
```

2. Instala lo que el proyecto necesita para funcionar:

```bash
   composer install
```

3. Carga la base de datos. Es importante dejar el `--default-character-set=utf8mb4`, porque sin eso las palabras con tilde se ven mal (más abajo cuento cómo me pasó):

```bash
   mysql -u root -p --default-character-set=utf8mb4 < world.sql
```

4. Crea el archivo de configuración y la llave de la página:

```bash
   cp .env.example .env
   php artisan key:generate
```

5. Revisa en el archivo `.env` los datos para conectarse a la base. Yo usé MariaDB en el puerto 3307. Si tienes MySQL 8 normal, cambia estas dos líneas:

```env
   DB_CONNECTION=mysql
   DB_PORT=3306
```

6. Enciende la página y ábrela en http://127.0.0.1:8000

```bash
   php artisan serve
```

La página solo lee la información de World y no crea nada nuevo en la base, así que no hay que hacer ningún paso adicional.

## Cómo funciona

Cuando escoges un país, la página le pide al servidor las ciudades de ese país. El servidor las busca en la base de datos y se las devuelve, y con eso se llenan las tablas en la pantalla sin recargar la página.

### Dónde está cada cosa

- `routes/web.php`: las direcciones de la página
- `app/Http/Controllers/CountryController.php`: recibe la petición, busca la información y la devuelve
- `app/Models/Country.php`: conecta con la tabla de países
- `app/Models/City.php`: conecta con la tabla de ciudades
- `app/Models/CountryLanguage.php`: conecta con la tabla de idiomas
- `resources/views/countries/index.blade.php`: la pantalla que ve Sofía

### Direcciones de la página

- `/`: la pantalla principal
- `/countries/{code}/cities`: devuelve la información de un país con sus ciudades e idiomas. Si el país no existe, responde que no lo encontró (error 404)

Por ejemplo, para Colombia: http://127.0.0.1:8000/countries/COL/cities

Algo que tuve que ajustar: la base World no está organizada como Laravel espera. Los nombres de las tablas están en singular, los países se identifican con un código de letras (`COL`, `ESP`) y no con un número, y las tablas no guardan fechas de creación. Por eso en cada modelo dejé indicado cómo es realmente cada tabla.

## Diagrama de la base de datos

![Diagrama de la base de datos World](<docs/evidencias/diagrama de bd.png>)

Lo hice en draw.io.

- Un país tiene muchas ciudades, y cada ciudad pertenece a un solo país.
- Un país también puede tener varios idiomas.
- Cada idioma se identifica por el país y el nombre del idioma juntos.
- La columna `Capital` del país guarda el número de la ciudad capital.

## Problemas que me encontré y cómo los resolví

**1. El puerto 3306 estaba ocupado.**
Al prender MySQL en XAMPP me salió que otro programa ya estaba usando el puerto 3306, porque tenía otro MySQL instalado. Como XAMPP ya estaba en el 3307, dejé todo conectado a ese puerto.

**2. Las tildes se veían mal.**
Al cargar `world.sql` con DbVisualizer, los nombres salían como "AsunciÃ³n" o "Ciudad de PanamÃ¡". El programa leyó el archivo con un formato distinto al que tenía. Lo volví a cargar desde la consola con `--default-character-set=utf8mb4` y revisé que ya salieran bien.

**3. Un comando de revisión fallaba.**
Al correr `php artisan db:show` salía el error `Table 'performance_schema.session_status' doesn't exist`. La conexión sí funcionaba; el problema era que Laravel estaba configurado para MySQL y XAMPP trae MariaDB. Lo cambié a `DB_CONNECTION=mariadb`.

**4. Faltaba activar algo en PHP.**
Laravel necesita la extensión `intl` para mostrar bien los números, y en XAMPP viene apagada. La activé en el archivo `php.ini` quitándole el punto y coma a `extension=intl`.

**5. Archivos con el nombre en minúscula.**
Algunos archivos me quedaron como `city.php` en vez de `City.php`. En Windows da igual, pero en otros sistemas la página no los encontraría. Los renombré para que quedaran bien.

## Pruebas que hice

- **Cargar la base:** salieron 239 países, 4079 ciudades y 984 idiomas
- **Escoger Colombia:** 38 ciudades, Bogotá de primera y el español como idioma principal
- **Escoger Antártida:** sale el mensaje de que no hay ciudades, sin errores
- **No escoger ningún país:** sale el aviso de que hay que escoger uno
- **Buscar un país que no existe:** responde que no lo encontró
- **Verla en celular:** las tablas se acomodan una debajo de otra

### Capturas

- Versiones de PHP y Composer

  ![](<docs/evidencias/VERSIONES DE HERRAMIENTAS.png>)

- Conexión a la base de datos

  ![](<docs/evidencias/CONFIGURACION DE BD.png>)

- Configuración de la base World

  ![](<docs/evidencias/Configuracion de la BD WORLD.png>)

- Base cargada (239 / 4079 / 984)

  ![](<docs/evidencias/IMPORTACION DE CONTEOS.png>)

- Tildes dañadas

  ![](<docs/evidencias/TILDES DAÑADAS.png>)

- Tildes corregidas

  ![](<docs/evidencias/ARREGLO DE TILDES.png>)

- Pantalla inicial

  ![](<docs/evidencias/VENTANA.png>)

- Colombia en computador

  ![](<docs/evidencias/COLOMBIA PC.png>)

- Colombia en celular

  ![](<docs/evidencias/COLOMBIA CELULAR.png>)

- Revisión con F12

  ![](<docs/evidencias/IMAGEN DE F12 O CODIGO.png>)

- Antártida sin ciudades

  ![](<docs/evidencias/ANTACTICA.png>)

- Campo obligatorio

  ![](<docs/evidencias/CAMPOOBLIGATORIO.png>)



## Pregunta 2: consultas SQL

Las consultas están en `sql/consultas.sql`.

Las hice en DbVisualizer.

### Ciudades con 100.000 habitantes o menos

Me salieron **521 ciudades**.

Ojo con el "menor o igual":
hay cuatro ciudades con justo 100.000,
y si solo buscaba las menores, se quedaban por fuera.

![](<docs/evidencias/sqlmenor100000.png>)

### Ciudades que empiezan con R

Me salieron **110 ciudades**.

![](<docs/evidencias/sqlciudadeconR.png>)

### Las 10 ciudades más grandes donde se habla español

Aquí me encontré con algo curioso.

La base también tiene el español en países
donde no es el idioma oficial.

Por ejemplo, en Estados Unidos lo habla el 7.5%,
así que en la lista se colaban New York, Los Angeles y Chicago.

Por eso hice dos versiones.

**Con todos los países donde se habla español**

![](<docs/evidencias/sqlversionespañol.png>)

**Solo donde el español es oficial**

Esta es la que mejor responde la pregunta:

1. Ciudad de México
2. Lima
3. Santafé de Bogotá
4. Santiago de Chile
5. Buenos Aires
6. Madrid
7. La Habana
8. Cali
9. Guayaquil
10. Caracas

![](<docs/evidencias/sqlespañolversionb.png>)

## Pregunta 3: base de datos

### ¿Qué campo es la clave primaria de la tabla País?

**b. Código**

Cada país tiene un código único de tres letras,
como COL para Colombia o ESP para España.

Lo comprobé en la base:
la clave primaria de `country` es `Code`,
no se puede repetir
y hay 239 códigos, uno por cada país.

El nombre, el continente o la capital no sirven,
porque se pueden repetir.

![](<docs/evidencias/p3llavepais.png>)

### ¿Cuál es la relación entre Ciudad y País?

**B. Un país puede tener varias ciudades**

Cada ciudad guarda el código de su país.
Muchas ciudades pueden tener el mismo país,
pero cada ciudad pertenece a uno solo.

Es una relación de uno a muchos.

Lo comprobé contando las ciudades por país.
Por ejemplo, China tiene 363 e India 341.

![](<docs/evidencias/p3relacion.png>)


## Autor



Luis Fernando Jiménez Quiñones 