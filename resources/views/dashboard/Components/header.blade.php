<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-primary" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if(Auth::user()->image)
                            <img src="/{{Auth::user()->image}}" alt="User Image" width="100px" id="image">
                        @else
                            <span class="profile-image">{{substr(Auth::user()->name,0,1)}}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('view_profile') }}">Profile</a>
                        <a class="dropdown-item" href="/logout">Log Out</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
