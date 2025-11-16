<x-main>
    <div class="d-flex justify-content-center align-items-center m-2">
        <div class="card">
            <div class="card-header">
                <h1>Analiza dla <b>{{ $username }}</b> </h1>
            </div>
            <div class="card-body">
                <span>Wszyscy obserwujący: <b>{{ $result->allTimeFollowers }}</b></span><br>
                <span>Aktualni obserwujący: <b>{{ $result->latestFollowers }}</b></span><br>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <div :if="count($result->lostFollowers) > 0">
            <span class="mb-3 d-block text-center">
                Utraceni obserwujący:
            </span>
            <div class="row">
                <div :foreach="$result->lostFollowers as $lostFollower" class="col">
                    <div class="card h-100 text-center">
                        <a href="{{ $lostFollower->profileUrl() }}" target="_blank" class="text-decoration-none">
                            <img src="{{ $lostFollower->avatarUrl() }}" class="card-img-top rounded-circle mx-auto mt-3" style="width: 100px; height: 100px;" alt="Avatar">
                            <div class="card-body">
                                <h5 class="card-title text-nowrap">{{ $lostFollower->username }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div :else>
            <span>Brak utraconych obserwujących.</span>
        </div>
    </div>
</x-main>
