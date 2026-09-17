<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre un ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4">
            <h3 class="mb-1">Prendre un ticket</h3>
            <p class="text-muted-slate mb-4">Choisissez le service qui vous concerne</p>

            <form method="POST" action="{{ url('/deconnexion') }}" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-outline-slate btn-sm">Déconnexion</button>
            </form>

            @if (session('error'))
                <div class="alert alert-ticket-danger p-3 mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-ticket-danger p-3 mb-3">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('paiements.rediriger') }}">
                @csrf
<div class="mb-4">
    <label class="form-label">Choisir un établissement</label>

    <select name="etablissement_id"
            id="etablissement_id"
            class="form-select"
            required>

        <option value="">-- Sélectionner --</option>

        @foreach ($etablissements as $etablissement)
            <option value="{{ $etablissement->id }}">
                {{ $etablissement->nom }}
            </option>
        @endforeach

    </select>
</div>
                <div class="mb-4">
                    <label class="form-label">Choisir un service</label>
                    <select name="service_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
        data-etablissement="{{ $service->etablissement_id }}">
    {{ $service->nom }}
</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-indigo w-100">Obtenir mon ticket</button>
            </form>
        </div>
    </div>
    <script>
    const etablissementSelect = document.getElementById('etablissement_id');
    const serviceSelect = document.querySelector('select[name="service_id"]');

    function filtrerServices() {
        const etablissementId = etablissementSelect.value;

        serviceSelect.value = '';

        Array.from(serviceSelect.options).forEach((option, index) => {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            option.hidden =
                !etablissementId ||
                option.dataset.etablissement !== etablissementId;
        });
    }

    etablissementSelect.addEventListener('change', filtrerServices);

    filtrerServices();
</script>
</body>
</html>