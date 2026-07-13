<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmbatuGrow')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">
    <div class="bg-green-800 text-white text-xs py-2 px-6 flex justify-between items-center font-medium">
        <div></div>
        <div class="tracking-wide">BILI NA!!! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" class="underline">ShopNow</a></div>
        <div class="cursor-pointer">English <i class="fas fa-chevron-down ml-1"></i></div>
    </div>

    <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="bg-green-700 text-white rounded-full p-2 w-9 h-9 flex items-center justify-center font-bold text-sm">AG</div>
            <span class="text-xl font-bold tracking-wider text-gray-900">AMBATUGROW</span>
        </div>
        <div class="relative w-80">
            <input type="text" placeholder="What are you looking for?" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 pr-10">
            <i class="fas fa-search absolute right-3 top-3 text-gray-400 text-sm"></i>
        </div>
    </header>

    <div class="flex flex-1">
        @include('partials.sidebar')

        <main class="flex-1 px-8 pt-0 pb-4">
            @yield('content')
        </main>
    </div>

    <footer class="bg-green-700 text-white mt-6">
        <div class="max-w-7xl mx-auto px-8 py-12 grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-green-100 mb-4">Support</h3>
                <p class="text-sm text-green-200">BATUMBUKAL</p>
                <p class="text-sm text-green-200 mt-1">AMBATUGROW@gmail.com</p>
                <p class="text-sm text-green-200 mt-1">+88015-88888-9999</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-green-100 mb-4">Account</h3>
                <ul class="space-y-2 text-sm text-green-200">
                    <li>My Account</li>
                    <li>Login / Register</li>
                    <li>Cart</li>
                    <li>Wishlist</li>
                    <li>Shop</li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-green-100 mb-4">Quick Link</h3>
                <ul class="space-y-2 text-sm text-green-200">
                    <li>Privacy Policy</li>
                    <li>Terms Of Use</li>
                    <li>FAQ</li>
                    <li>Contact</li>
                </ul>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
