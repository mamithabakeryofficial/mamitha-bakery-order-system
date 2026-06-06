<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mamitha Bakery')</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Premium Dark Chocolate for Bakery */
            --primary-color: #4A2E1B; 
            /* Very soft modern off-white/gray background */
            --secondary-color: #F8FAFC; 
            --accent-color: #D97706; /* Warm subtle amber for highlights */
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #334155; /* Slate 700 */
        }

        /* Modern Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: #0F172A; /* Slate 900 */
            letter-spacing: -0.025em;
        }

        /* Premium Card Styling */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        /* Subtle hover effect on cards */
        a.card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04) !important;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        
        /* Button Upgrades */
        .btn {
            font-weight: 500;
            letter-spacing: 0.01em;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transition: all 0.2s;
        }
        
        .btn-primary-custom:hover {
            background-color: #352113;
            border-color: #352113;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Table Styling */
        .table {
            color: #475569;
        }
        .table-light th {
            background-color: #F1F5F9;
            color: #475569;
            font-weight: 600;
            border-bottom-width: 1px;
        }
        
        /* Navbar Upgrades */
        .navbar {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
