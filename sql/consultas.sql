## Pregunta 2: consultas SQL

Las consultas están en `sql/consultas.sql`.
Las corrí en DbVisualizer.

### Ciudades con 100.000 habitantes o menos

- Resultado: **521 ciudades**.
- Usé "menor o igual", como pide el enunciado.
- Cuatro ciudades tienen justo 100.000.
- Con "menor que" se quedarían por fuera.

![](<docs/evidencias/sqlmenor100000.png>)

### Ciudades que empiezan con R

- Resultado: **110 ciudades**.

![](<docs/evidencias/sqlciudadeconR.png>)

### Las 10 ciudades más grandes donde se habla español

- La base guarda el español también donde no es oficial.
- Por ejemplo, Estados Unidos, donde lo habla el 7.5%.
- Sin filtrar, salen New York, Los Angeles y Chicago.
- Por eso hice dos versiones.

**Versión A: todos los países donde se habla español**

![](<docs/evidencias/sqlversionespañol.png>)

**Versión B: solo donde el español es oficial**

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