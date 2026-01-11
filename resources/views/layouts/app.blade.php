<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Literasia')</title>
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            color: #4a4a4a;
        }
        
        /* Sidebar Styling */
        .sidenav {
            width: 260px;
            background-color: #fff;
            box-shadow: none;
            border-right: 1px solid #eee;
        }
        
        .sidenav .logo-container {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidenav .logo-container .logo-text {
            font-weight: 700;
            font-size: 20px;
            color: #d81b60;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .sidenav li a {
            font-size: 14px;
            font-weight: 500;
            color: #757575;
            padding: 0 32px;
            display: flex;
            align-items: center;
        }
        
        .sidenav li a i {
            margin-right: 16px;
            font-size: 20px;
            color: #9e9e9e;
        }
        
        .sidenav li.active {
            background-color: transparent;
        }
        
        .sidenav li.active a {
            color: #fff;
            background: linear-gradient(90deg, #d81b60 0%, #e91e63 100%);
            border-radius: 0 24px 24px 0;
            margin-right: 16px;
        }
        
        .sidenav li.active a i {
            color: #fff;
        }
        
        /* Navbar Styling */
        header, main, footer {
            padding-left: 260px;
        }
        
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
        
        .navbar-fixed nav {
            background-color: #f5f7fa;
            box-shadow: none;
            color: #4a4a4a;
            padding: 0 24px;
        }
        
        .navbar-fixed nav i {
            color: #757575;
        }
        
        .nav-right-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #ddd;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-avatar .status-dot {
            width: 10px;
            height: 10px;
            background-color: #4caf50;
            border: 2px solid #fff;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
        }
        
        /* Content Styling */
        .page-header {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .school-info {
            display: flex;
            flex-direction: column;
        }
        
        .school-info .label {
            font-size: 12px;
            color: #9e9e9e;
        }
        
        .school-info .name {
            font-size: 18px;
            font-weight: 600;
        }
        
        .icon-box-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box-circle.pink { background-color: #fce4ec; color: #d81b60; }
        
        /* Layout classes */
        .container-fluid {
            width: 95%;
            margin: 0 auto;
        }
        
        @yield('styles')
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li class="logo-container">
            <i class="material-icons pink-text text-darken-1">import_contacts</i>
            <span class="logo-text">Literasia</span>
        </li>
        <li class="active"><a href="#!"><i class="material-icons">dashboard</i>Dashboard Sekolah</a></li>
        <li><a href="#!"><i class="material-icons">assignment_turned_in</i>E-Raport</a></li>
        <li><a href="#!"><i class="material-icons">computer</i>CBT</a></li>
        <li><a href="#!"><i class="material-icons">warning</i>Pelanggaran</a></li>
        <li><a href="#!"><i class="material-icons">article</i>Berita</a></li>
        <li><a href="#!"><i class="material-icons">school</i>Sekolah</a></li>
        <li><a href="#!"><i class="material-icons">people</i>Fungsionaris</a></li>
        <li><a href="#!"><i class="material-icons">notifications</i>Pengumuman</a></li>
        <li><a href="#!"><i class="material-icons">image</i>Slider Admin</a></li>
        <li><a href="#!"><i class="material-icons">calendar_today</i>Kalender</a></li>
        <li><a href="#!"><i class="material-icons">account_balance</i>Mata Pelajaran</a></li>
        <li><a href="#!"><i class="material-icons">record_voice_over</i>Sambutan</a></li>
    </ul>
    
    <header>
        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <ul class="left hide-on-med-and-down">
                        <li><a href="#!"><i class="material-icons">radio_button_checked</i></a></li>
                    </ul>
                    <ul class="right nav-right-icons">
                        <li><a href="#!"><i class="material-icons">dark_mode</i></a></li>
                        <li><a href="#!"><i class="material-icons">notifications_none</i></a></li>
                        <li>
                            <div class="profile-avatar">
                                <img src="https://via.placeholder.com/150" alt="Profile">
                                <div class="status-dot"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.sidenav');
            var instances = M.Sidenav.init(elems);
        });
    </script>
    @yield('scripts')
</body>
</html>
