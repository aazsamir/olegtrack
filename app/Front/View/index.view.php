<x-main>
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card mx-auto">
                <div class="card-header">
                    <h1>Witamy w OlegTrack</h1>
                </div>
                <div class="card-body">
                    <form method="GET" action="/show">
                        <div class="mb-3">
                            <label for="username" class="form-label">Użytkownik</label>
                            <select class="form-select" id="username" name="username" required>
                                <option value="" disabled selected>Wybierz użytkownika</option>
                                <div :foreach="$users as $user">
                                    <option value="{{ $user }}">{{ $user }}</option>
                                </div>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Analizuj</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card mx-auto">
                <div class="card-header">
                    <h2>Dodaj nowego użytkownika do śledzenia</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="/track">
                        <div class="mb-3">
                            <x-csrf-token />
                            <label for="new-username" class="form-label">Nazwa użytkownika</label>
                            <input type="text" class="form-control" id="new-username" name="username" required>
                        </div>
                        <button type="submit" class="btn btn-success">Dodaj użytkownika</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card mx-auto">
                <div class="card-header">
                    <h2>Obecnie śledzeni użytkownicy</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <div :foreach="$trackedUsers as $trackedUser">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $trackedUser }}
                                <form method="POST" action="/untrack" class="mb-0">
                                    <x-csrf-token />
                                    <input type="hidden" name="username" value="{{ $trackedUser }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-main>
