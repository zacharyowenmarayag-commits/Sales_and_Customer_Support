<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmbatuGrow')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col" style="font-family: 'Inter', sans-serif;">
    <div class="sticky top-0 z-50 shadow-sm">
        <header class="bg-green-700 border-b border-green-800 px-8 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <button id="mobile-sidebar-toggle" class="md:hidden text-white/80 hover:text-white focus:outline-none p-1 mr-1">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <img src="{{ asset('images/logo.png') }}" alt="AMBATUGROW Logo" class="w-9 h-9 object-contain bg-white rounded-full p-0.5">
                <span class="text-xl font-bold tracking-wider text-white">AMBATUGROW</span>
            </div>
            <div class="relative w-80">
                <input type="text" id="globalHeaderSearch" value="{{ request('q') }}" placeholder="What are you looking for?" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-sm text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/40 pr-10">
                <i class="fas fa-search absolute right-3 top-3 text-white/60 text-sm"></i>
            </div>
        </header>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Restore scroll position and input focus if the path matches the last saved path
            const savedPath = localStorage.getItem('scrollPathname');
            const savedScroll = localStorage.getItem('scrollPosition');
            if (savedPath === window.location.pathname) {
                if (savedScroll) {
                    window.scrollTo(0, parseInt(savedScroll, 10));
                }
                const activeElementId = localStorage.getItem('activeElementId');
                const cursorPosition = localStorage.getItem('cursorPosition');
                if (activeElementId) {
                    const el = document.getElementById(activeElementId);
                    if (el) {
                        el.focus();
                        if (cursorPosition !== null && el.setSelectionRange) {
                            const pos = parseInt(cursorPosition, 10);
                            el.setSelectionRange(pos, pos);
                        }
                    }
                    localStorage.removeItem('activeElementId');
                    localStorage.removeItem('cursorPosition');
                }
            }

            // Save scroll position and focused element before unloading the page
            window.addEventListener('beforeunload', () => {
                localStorage.setItem('scrollPosition', window.scrollY);
                localStorage.setItem('scrollPathname', window.location.pathname);
                if (document.activeElement && document.activeElement.id) {
                    localStorage.setItem('activeElementId', document.activeElement.id);
                    localStorage.setItem('cursorPosition', document.activeElement.selectionStart || 0);
                } else {
                    localStorage.removeItem('activeElementId');
                    localStorage.removeItem('cursorPosition');
                }
            });

            // Also save scroll position on scroll (debounced) to ensure correctness
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    localStorage.setItem('scrollPosition', window.scrollY);
                    localStorage.setItem('scrollPathname', window.location.pathname);
                }, 100);
            });

            const globalSearch = document.getElementById('globalHeaderSearch');
            if (globalSearch) {
                const triggerSearch = () => {
                    const query = globalSearch.value.trim();
                    const path = window.location.pathname.toLowerCase();
                    const url = new URL(window.location.href);

                    // Sync the global header search input to page-specific inputs if they exist,
                    // or redirect to correct search endpoints depending on section path.
                    if (query) {
                        url.searchParams.set('q', query);
                    } else {
                        url.searchParams.delete('q');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                };

                globalSearch.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        triggerSearch();
                    }
                });
            }

            // Scroll to the first matching element on page load
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q');
            if (searchQuery) {
                setTimeout(() => {
                    // 1. First priority: highlighted data match
                    const dataMatch = document.querySelector('.text-blue-600');
                    if (dataMatch) {
                        dataMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // 2. Second priority: search headings, labels, and section titles
                    const q = searchQuery.toLowerCase();
                    const candidates = document.querySelectorAll('h1, h2, h3, th, .label, .sprf-kpi-card .label, .sprf-panel h3');
                    for (const el of candidates) {
                        if (el.textContent.toLowerCase().includes(q)) {
                            // Scroll to the closest panel/section container, or the element itself
                            const section = el.closest('.sprf-panel, .sprf-kpi-card, .sprf-charts-row, .sprf-three-row, section, [class*="card"], [class*="panel"]') || el;
                            section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                    }
                }, 300);
            }
        });
    </script>

    <div class="flex flex-1">
        @include('partials.sidebar')

        <main class="flex-1 min-w-0 flex flex-col justify-between">
            <div class="flex-1 pl-6 pr-8 pt-6 pb-12 min-h-[calc(100vh-65px)]">
                @yield('content')
            </div>

            <footer class="bg-green-700 text-white">
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
        </main>
    </div>

    @stack('scripts')
</body>
</html>
