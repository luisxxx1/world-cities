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