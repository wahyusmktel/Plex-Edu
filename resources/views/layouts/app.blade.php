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
        :root {
            --primary-gradient: linear-gradient(135deg, #ba80e8 0%, #d90d8b 100%);
            --primary-color: #d90d8b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
            color: #4a4a4a;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        
        main {
            flex: 1 0 auto;
        }

        /* Sidebar Styling */
        .sidenav {
            width: 280px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.03);
            border: none;
        }
        
        .sidenav .logo-container {
            padding: 30px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        
        .sidenav .logo-container i {
            font-size: 32px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidenav .logo-container .logo-text {
            font-weight: 800;
            font-size: 24px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .sidenav li {
            margin: 4px 0;
        }

        .sidenav li.active {
            background-color: transparent !important;
        }

        .sidenav li a {
            font-size: 14px;
            font-weight: 500;
            color: #616161;
            padding: 0 24px 0 32px;
            height: 44px;
            line-height: 44px;
            display: flex;
            align-items: center;
            border-radius: 0 22px 22px 0;
            margin-right: 20px;
            transition: all 0.3s ease;
        }
        
        .sidenav li a i {
            margin-right: 16px;
            font-size: 22px;
            color: #bdbdbd;
        }
        
        .sidenav li.active a {
            color: #fff !important;
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 12px rgba(217, 13, 139, 0.2);
        }
        
        .sidenav li.active a i {
            color: #fff !important;
        }
        
        /* Navbar, Main, Footer Padding */
        header, main, footer {
            padding-left: 280px;
            transition: padding-left 0.3s ease;
        }
        
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }

        /* Desktop Sidenav Toggle Logic */
        @media only screen and (min-width : 993px) {
            body.sidenav-collapsed .sidenav {
                transform: translateX(-105%) !important;
            }
            body.sidenav-collapsed header, 
            body.sidenav-collapsed main, 
            body.sidenav-collapsed footer {
                padding-left: 0;
            }
        }
        
        nav {
            background-color: transparent !important;
            box-shadow: none !important;
            color: #4a4a4a;
            height: 70px;
            line-height: 70px;
        }
        
        nav i {
            color: #757575 !important;
        }
        
        .nav-wrapper {
            padding: 0 30px;
        }

        .nav-right-icons {
            display: flex !important;
            align-items: center;
            gap: 10px;
        }

        .nav-right-icons li a {
            padding: 0 10px;
            display: flex;
            align-items: center;
        }
        
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #eee;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            margin-left: 10px;
        }
        
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .status-dot {
            width: 12px;
            height: 12px;
            background-color: #4caf50;
            border: 2px solid #fff;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
        }
        
        /* Dropdown Styling */
        .dropdown-content {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-width: 200px !important;
        }

        .dropdown-content li > a, .dropdown-content li > span {
            font-size: 14px;
            color: #4a4a4a;
            padding: 14px 16px;
        }

        .dropdown-content li > a > i {
            margin-right: 15px;
            color: #9e9e9e;
        }

        /* Footer */
        .page-footer {
            background-color: #fff;
            color: #757575;
            padding: 20px 0;
            border-top: 1px solid #eee;
            /* Place footer above sidebar */
            position: relative;
            z-index: 999;
        }
        
        .footer-copyright {
            background-color: transparent !important;
            color: #9e9e9e !important;
        }

        /* Content Hub */
        .container-fluid {
            width: 96%;
            margin: 0 auto;
            padding-top: 10px;
        }
        
        @yield('styles')
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li class="logo-container">
            <i class="material-icons">import_contacts</i>
            <span class="logo-text">Literasia</span>
        </li>
        <li class="active"><a href="{{ route('dashboard') }}"><i class="material-icons">dashboard</i>Dashboard Sekolah</a></li>
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
        <li><div class="divider"></div></li>
        <li><a class="waves-effect logout-btn" href="#!" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons">exit_to_app</i>Logout
        </a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
    
    <header>
        <nav>
            <div class="nav-wrapper">
                <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large"><i class="material-icons">menu</i></a>

                <ul class="right nav-right-icons">
                    <li><a href="#!"><i class="material-icons">dark_mode</i></a></li>
                    <li><a href="#!"><i class="material-icons">notifications_none</i></a></li>
                    <li>
                        <div class="profile-avatar dropdown-trigger" data-target="profile-dropdown">
                            <img src="https://via.placeholder.com/150" alt="Profile">
                            <div class="status-dot"></div>
                        </div>
                    </li>
                </ul>

                <!-- Profile Dropdown Structure -->
                <ul id="profile-dropdown" class="dropdown-content">
                    <li><a href="#!"><i class="material-icons">person</i>{{ Auth::user()->name }}</a></li>
                    <li><a href="#!"><span class="badge new pink" data-badge-caption="">{{ ucfirst(Auth::user()->role) }}</span></a></li>
                    <li class="divider"></li>
                    <li><a href="#!" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">exit_to_app</i>Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <footer class="page-footer">
        <div class="container-fluid">
            <div class="footer-copyright">
                © 2026 Literasia Edutekno Digital. All rights reserved.
                <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
        </div>
    </footer>

    <!-- Compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            // Initialize sideNav
            $('.sidenav').sidenav();
            
            // Handle sidenav toggle for both mobile and desktop
            $('.sidenav-trigger').on('click', function(e) {
                e.preventDefault();
                
                if ($(window).width() > 992) {
                    // Desktop mode: Toggle class on body
                    $('body').toggleClass('sidenav-collapsed');
                } else {
                    // Mobile mode: Use Materialize instance
                    var instance = M.Sidenav.getInstance($('#slide-out'));
                    if (instance) {
                        if (instance.isOpen) {
                            instance.close();
                        } else {
                            instance.open();
                        }
                    }
                }
            });

            // Initialize dropdowns
            $('.dropdown-trigger').dropdown({
                coverTrigger: false,
                constrainWidth: false
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
