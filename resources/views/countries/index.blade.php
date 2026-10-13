<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudades del mundo</title>

    <!-- tailwind desde el cdn, así no hay que instalar ni compilar nada -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <main class="mx-auto max-w-4xl p-4">

        <h1 class="mb-4 text-2xl font-bold">Ciudades del mundo</h1>

        <!-- el select se llena con los países que manda el controlador,
             en el value va el código porque es lo que usa la consulta -->
        <label for="country" class="mb-1 block font-semibold">País *</label>
        <select id="country" required class="w-full rounded border bg-white p-2">
            <option value="">Selecciona un país...</option>
            @foreach ($countries as $country)
                <option value="{{ $country->Code }}">{{ $country->Name }}</option>
            @endforeach
        </select>

        <!-- aquí aparece el "cargando" o el error si algo falla -->
        <p id="status" class="my-3 text-sm"></p>

        <!-- queda oculto hasta que se elige un país -->
        <div id="results" class="hidden space-y-6">

            <p><strong>Idiomas:</strong> <span id="languages"></span></p>

            <!-- en pantalla grande van lado a lado, en el celular una debajo de otra -->
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <h2 class="mb-2 font-bold">10 ciudades más pobladas</h2>
                    <table class="w-full bg-white text-sm">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="p-2">Ciudad</th>
                                <th class="p-2 text-right">Población</th>
                            </tr>
                        </thead>
                        <tbody id="most"></tbody>
                    </table>
                </div>

                <div>
                    <h2 class="mb-2 font-bold">10 ciudades menos pobladas</h2>
                    <table class="w-full bg-white text-sm">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="p-2">Ciudad</th>
                                <th class="p-2 text-right">Población</th>
                            </tr>
                        </thead>
                        <tbody id="least"></tbody>
                    </table>
                </div>
            </div>

            <!-- la lista completa, porque la prueba pide ver todas las ciudades del país -->
            <div>
                <h2 class="mb-2 font-bold">Todas las ciudades (<span id="total"></span>)</h2>
                <table class="w-full bg-white text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-2">Ciudad</th>
                            <th class="p-2 text-right">Población</th>
                        </tr>
                    </thead>
                    <tbody id="all"></tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // la url la saco de laravel para no dejarla quemada en el código
        const baseUrl = @json(url('/countries'));

        const select = document.getElementById('country');
        const status = document.getElementById('status');
        const results = document.getElementById('results');

        // sirve para llenar cualquiera de las tres tablas
        function fillTable(id, cities) {
            const tbody = document.getElementById(id);
            tbody.replaceChildren(); // borro lo del país anterior

            // hay países sin ciudades, como la Antártida
            if (cities.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2" class="p-2 text-gray-500">Sin ciudades registradas</td></tr>';
                return;
            }

            cities.forEach((city) => {
                const row = tbody.insertRow();
                row.className = 'border-t';

                // con textContent el nombre se muestra como texto y no se ejecuta nada raro
                const name = row.insertCell();
                name.className = 'p-2';
                name.textContent = city.Name;

                // la población con puntos de miles para que se lea mejor
                const population = row.insertCell();
                population.className = 'p-2 text-right';
                population.textContent = Number(city.Population).toLocaleString('es-CO');
            });
        }

        // esto corre cada vez que cambian el país
        select.addEventListener('change', async () => {
            const code = select.value;

            // si dejan el select vacío se avisa que es obligatorio
            if (!code) {
                results.classList.add('hidden');
                status.textContent = 'Selecciona un país para continuar.';
                status.className = 'my-3 text-sm text-red-600';
                return;
            }

            status.textContent = 'Cargando...';
            status.className = 'my-3 text-sm text-gray-500';

            try {
                // traigo los datos con fetch, sin recargar la página
                const response = await fetch(`${baseUrl}/${encodeURIComponent(code)}/cities`, {
                    headers: { Accept: 'application/json' },
                });

                // si responde con error (un 404 por ejemplo) se va al catch
                if (!response.ok) {
                    throw new Error('No se pudo consultar el país.');
                }

                const data = await response.json();

                // los idiomas en una sola línea con su porcentaje
                document.getElementById('languages').textContent = data.languages.length
                    ? data.languages.map((l) => `${l.language} (${l.percentage}%)`).join(', ')
                    : 'Sin idiomas registrados';

                document.getElementById('total').textContent = data.total_cities;

                fillTable('most', data.most_populated);
                fillTable('least', data.least_populated);
                fillTable('all', data.all_cities);

                status.textContent = '';
                results.classList.remove('hidden');
            } catch (error) {
                // si algo falla escondo las tablas y muestro el error
                results.classList.add('hidden');
                status.textContent = error.message;
                status.className = 'my-3 text-sm text-red-600';
            }
        });
    </script>
</body>
</html>