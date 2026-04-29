<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-map me-1"></i> {{ $title }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

                <!-- BERANDA -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fa-solid fa-house me-1" style="color: rgb(77, 30, 80);"></i>
                        Beranda
                    </a>
                </li>

                <!-- PETA -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('peta') }}">
                        <i class="fa-solid fa-map me-1" style="color: rgb(77, 30, 80);"></i>
                        Peta
                    </a>
                </li>

                <!-- TABEL -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">
                        <i class="fa-solid fa-table me-1" style="color: rgb(77, 30, 80);"></i>
                        Tabel
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
