<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('POS System', 'POS System') }} — Premium Authentication</title>
        <link rel="icon" href="{{ asset('favPOS.png') }}">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Minimal tailwind v4 fallback if build assets are not ready -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'class',
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                                heading: ['"Space Grotesk"', 'sans-serif'],
                            }
                        }
                    }
                }
            </script>
        @endif

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .font-heading {
                font-family: 'Space Grotesk', sans-serif;
            }
            .glass {
                background: rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .glass-light {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(15, 23, 42, 0.08);
            }
            .glow-indigo {
                box-shadow: 0 0 40px -10px rgba(99, 102, 241, 0.3);
            }
            .glow-emerald {
                box-shadow: 0 0 40px -10px rgba(16, 185, 129, 0.3);
            }
            @keyframes pulse-soft {
                0%, 100% { opacity: 0.25; transform: scale(1); }
                50% { opacity: 0.35; transform: scale(1.1); }
            }
            .animate-pulse-soft {
                animation: pulse-soft 8s infinite ease-in-out;
            }
            /* Smooth page inputs */
            .custom-input:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            }
        </style>
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-indigo-500/30 selection:text-indigo-200">
        
        <!-- Glowing Decorative Blobs -->
        <!-- <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse-soft pointer-events-none z-0"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[55%] h-[55%] bg-violet-600/20 rounded-full blur-[120px] animate-pulse-soft pointer-events-none z-0" style="animation-delay: 2s;"></div>
        <div class="absolute top-[40%] left-[45%] w-[30%] h-[30%] bg-emerald-600/10 rounded-full blur-[100px] animate-pulse-soft pointer-events-none z-0" style="animation-delay: 4s;"></div> -->

        <!-- Main Container -->
        <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-16 py-12 relative z-10">
            
            <!-- Left Side: POS Visual Showcase -->
            <!-- <div class="w-full lg:w-1/2 flex flex-col justify-center text-left space-y-8 select-none order-2 lg:order-1">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i class="bi bi-shop text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold font-heading tracking-tight bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                            {{ config('app.name', 'POS System') }}
                        </h1>
                        <p class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Bakery Edition</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-heading leading-tight tracking-tight text-white">
                        Smart Sales & <br class="hidden sm:inline" />
                        <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-emerald-400 bg-clip-text text-transparent">Real-Time Inventory</span> Control.
                    </h2>
                    <p class="text-slate-400 text-base sm:text-lg max-w-lg leading-relaxed">
                        Formulate recipes, schedule batches, track customer orders, and manage raw stocks. A robust Point of Sale engineered specifically for modern craft bakeries.
                    </p>
                </div> -->

                <!-- Mock Interactive Dashboard Card -->
                <!-- <div class="glass rounded-3xl p-6 glow-indigo relative overflow-hidden transition-all duration-300 hover:border-slate-700/80 group" x-data="dashboardSimulator()"> -->
                    
                    <!-- Header -->
                    <!-- <div class="flex justify-between items-center border-b border-slate-800/80 pb-4 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 absolute"></span>
                            <span class="text-xs font-semibold text-slate-300 tracking-wide uppercase ml-1">Live Terminal View</span>
                        </div>
                        <span class="text-xs text-slate-500">Device ID: BT-889</span>
                    </div> -->

                    <!-- Metrics Grid -->
                    <!-- <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-900/50 rounded-2xl p-4 border border-slate-800/50 hover:border-slate-800 transition-all">
                            <span class="text-xs text-slate-400 font-medium">Today's Sales</span>
                            <div class="flex items-baseline gap-1 mt-1 text-white">
                                <span class="text-lg font-bold">৳</span>
                                <span class="text-2xl font-black tracking-tight" x-text="salesFormatted()">18,450.00</span>
                            </div>
                            <span class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1 mt-1">
                                <i class="bi bi-graph-up-arrow"></i> +12.4% vs yesterday
                            </span>
                        </div>

                        <div class="bg-slate-900/50 rounded-2xl p-4 border border-slate-800/50 hover:border-slate-800 transition-all">
                            <span class="text-xs text-slate-400 font-medium">Oven #1 Progress</span>
                            <div class="flex items-center justify-between mt-1 text-white">
                                <span class="text-sm font-bold" x-text="queueStatus">Croissants</span>
                                <span class="text-xs text-indigo-400 font-semibold" x-text="timeRemaining">3m remaining</span>
                            </div> -->
                            <!-- Progress Bar -->
                            <!-- <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-1.5 rounded-full transition-all duration-1000" :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Lower Inventory Alert list -->
                    <!-- <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-xs bg-slate-900/30 p-2.5 rounded-xl border border-slate-900">
                            <div class="flex items-center gap-2 text-slate-300">
                                <i class="bi bi-box-seam text-indigo-400"></i>
                                <span>Unbleached Flour Stock</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-300 font-medium">94%</span>
                        </div>
                        <div class="flex items-center justify-between text-xs bg-slate-900/30 p-2.5 rounded-xl border border-slate-900">
                            <div class="flex items-center gap-2 text-slate-300">
                                <i class="bi bi-exclamation-triangle text-amber-400"></i>
                                <span>Premium Butter Stock</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-300 font-medium animate-pulse">18% (Reorder)</span>
                        </div>
                    </div> -->

                    <!-- Call to action simulate order -->
                    <!-- <div class="flex items-center justify-between">
                        <button type="button" @click="simulateOrder()" class="text-xs font-semibold text-indigo-300 bg-indigo-500/10 hover:bg-indigo-500/20 active:scale-95 px-4 py-2.5 rounded-xl border border-indigo-500/20 transition-all flex items-center gap-2">
                            <i class="bi bi-lightning-charge-fill"></i> Simulate Customer Order
                        </button>
                        <span class="text-[11px] text-slate-500 italic" x-show="showToast" x-transition>Order processed!</span>
                    </div>
                </div> -->

                <!-- Trust Badges -->
                <!-- <div class="flex items-center gap-6 text-slate-500 text-xs">
                    <span class="flex items-center gap-1.5"><i class="bi bi-shield-check text-slate-400"></i> ISO 27001 Secure</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-lightning text-slate-400"></i> 100% Realtime Sync</span>
                </div> -->
            <!-- </div> -->

            <!-- Right Side: Auth Toggle Card -->
            @php
                $activeTab = request()->query('tab', 'login');
                if (old('_form') === 'register' || $errors->has('name') || $errors->has('password_confirmation')) {
                    $activeTab = 'register';
                } elseif (old('_form') === 'forgot') {
                    $activeTab = 'forgot';
                }
            @endphp
            <div class="w-full lg:w-1/2 max-w-md mx-auto order-1 lg:order-2" x-data="{ tab: '{{ $activeTab }}' }">
                
                <!-- Main Glass Card -->
                <div class="glass rounded-3xl overflow-hidden shadow-2xl relative border border-white/10 glow-indigo">
                    
                    <!-- Form Tabs Header -->
                    <div class="flex bg-slate-900/70 border-b border-slate-800/80 p-2 gap-1">
                        <button type="button" @click="tab = 'login'" 
                                :class="tab === 'login' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50'"
                                class="flex-1 py-3 px-4 rounded-2xl text-sm font-semibold tracking-wide transition-all duration-200 focus:outline-none flex items-center justify-center gap-1.5">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                        <button type="button" @click="tab = 'register'"
                                :class="tab === 'register' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50'"
                                class="flex-1 py-3 px-4 rounded-2xl text-sm font-semibold tracking-wide transition-all duration-200 focus:outline-none flex items-center justify-center gap-1.5">
                            <i class="bi bi-person-plus"></i> Register
                        </button>
                        <button type="button" @click="tab = 'forgot'"
                                :class="tab === 'forgot' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50'"
                                class="flex-1 py-3 px-4 rounded-2xl text-sm font-semibold tracking-wide transition-all duration-200 focus:outline-none flex items-center justify-center gap-1.5">
                            <i class="bi bi-key"></i> Reset
                        </button>
                    </div>

                    <!-- Card Body containing forms -->
                    <div class="p-6 sm:p-8">
                        
                        <!-- TAB 1: LOGIN -->
                        <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold font-heading text-white">Welcome Back!</h3>
                                <p class="text-slate-400 text-xs mt-1">Please sign in to access your dashboard terminal.</p>
                            </div>

                            <!-- Error alert -->
                            @if (old('_form') === 'login' && $errors->any())
                                <div class="bg-red-500/10 border border-red-500/20 text-red-300 rounded-2xl p-4 text-xs flex gap-2.5 items-start">
                                    <i class="bi bi-exclamation-octagon-fill text-red-400 text-sm"></i>
                                    <div>
                                        <p class="font-bold">Login Failed</p>
                                        <p class="mt-0.5">Please check your email and password credentials.</p>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_form" value="login">

                                <div>
                                    <label for="login_email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="login_email" name="email" value="{{ old('_form') === 'login' ? old('email') : '' }}" required autofocus autocomplete="username"
                                               class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-4 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                               placeholder="name@bakery.com">
                                    </div>
                                    @if (old('_form') === 'login' && $errors->has('email'))
                                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div x-data="{ showPass: false }">
                                    <div class="flex justify-between items-center mb-2">
                                        <label for="login_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                                        <button type="button" @click="tab = 'forgot'" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Forgot Password?</button>
                                    </div>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-lock"></i></span>
                                        <input :type="showPass ? 'text' : 'password'" id="login_password" name="password" required autocomplete="current-password"
                                               class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-12 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                               placeholder="••••••••">
                                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200">
                                            <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                                        </button>
                                    </div>
                                    @if (old('_form') === 'login' && $errors->has('password'))
                                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <!-- Remember checkbox -->
                                <div class="flex items-center justify-between pt-1">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="remember" class="w-4 h-4 bg-slate-900 border-slate-800 rounded text-indigo-600 focus:ring-0 focus:ring-offset-0 focus:outline-none">
                                        <span class="text-xs text-slate-400">Remember session</span>
                                    </label>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-indigo-600/20 hover:from-indigo-600 hover:to-violet-700 hover:shadow-indigo-600/30 transition-all active:scale-[0.99] flex justify-center items-center gap-2">
                                    Sign In <i class="bi bi-chevron-right text-xs"></i>
                                </button>
                            </form>
                        </div>

                        <!-- TAB 2: REGISTER -->
                        <div x-show="tab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold font-heading text-white">Create Operator Account</h3>
                                <p class="text-slate-400 text-xs mt-1">Register a new store or bakery supervisor account.</p>
                            </div>

                            <!-- Error alert -->
                            @if (old('_form') === 'register' && $errors->any())
                                <div class="bg-red-500/10 border border-red-500/20 text-red-300 rounded-2xl p-4 text-xs flex gap-2.5 items-start">
                                    <i class="bi bi-exclamation-octagon-fill text-red-400 text-sm"></i>
                                    <div>
                                        <p class="font-bold">Registration Errors</p>
                                        <p class="mt-0.5">Please check and correct the highlighted fields below.</p>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_form" value="register">

                                <div>
                                    <label for="reg_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Operator Name</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-person"></i></span>
                                        <input type="text" id="reg_name" name="name" value="{{ old('_form') === 'register' ? old('name') : '' }}" required autofocus autocomplete="name"
                                               class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-4 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                               placeholder="John Doe">
                                    </div>
                                    @if (old('_form') === 'register' && $errors->has('name'))
                                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div>
                                    <label for="reg_email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="reg_email" name="email" value="{{ old('_form') === 'register' ? old('email') : '' }}" required autocomplete="username"
                                               class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-4 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                               placeholder="operator@bakery.com">
                                    </div>
                                    @if (old('_form') === 'register' && $errors->has('email'))
                                        <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div x-data="{ showPass: false }">
                                        <label for="reg_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-lock"></i></span>
                                            <input :type="showPass ? 'text' : 'password'" id="reg_password" name="password" required autocomplete="new-password"
                                                   class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-10 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                                   placeholder="••••••••">
                                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-200">
                                                <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-data="{ showPass: false }">
                                        <label for="reg_password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Confirm</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-lock-check"></i></span>
                                            <input :type="showPass ? 'text' : 'password'" id="reg_password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                                   class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-10 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                                   placeholder="••••••••">
                                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-200">
                                                <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if (old('_form') === 'register' && $errors->has('password'))
                                    <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('password') }}</span>
                                @endif
                                @if (old('_form') === 'register' && $errors->has('password_confirmation'))
                                    <span class="text-xs text-red-400 font-medium mt-1.5 block">{{ $errors->first('password_confirmation') }}</span>
                                @endif

                                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-indigo-600/20 hover:from-indigo-600 hover:to-violet-700 hover:shadow-indigo-600/30 transition-all active:scale-[0.99] flex justify-center items-center gap-2 mt-2">
                                    Register Account <i class="bi bi-person-check text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <!-- TAB 3: FORGOT PASSWORD -->
                        <div x-show="tab === 'forgot'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold font-heading text-white">Recover Password</h3>
                                <p class="text-slate-400 text-xs mt-1">We will send a secure password reset link to your email.</p>
                            </div>

                            <!-- Success status message -->
                            @if (session('status'))
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl p-4 text-xs flex gap-2.5 items-start">
                                    <i class="bi bi-check-circle-fill text-emerald-400 text-sm"></i>
                                    <div>
                                        <p class="font-bold">Reset Link Sent</p>
                                        <p class="mt-0.5">{{ session('status') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Error alerts -->
                            @if (old('_form') === 'forgot' && $errors->any())
                                <div class="bg-red-500/10 border border-red-500/20 text-red-300 rounded-2xl p-4 text-xs flex gap-2.5 items-start">
                                    <i class="bi bi-exclamation-octagon-fill text-red-400 text-sm"></i>
                                    <div>
                                        <p class="font-bold">Email Failed</p>
                                        <p class="mt-0.5">{{ $errors->first('email') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_form" value="forgot">

                                <div>
                                    <label for="forgot_email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Registered Email</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="forgot_email" name="email" value="{{ old('_form') === 'forgot' ? old('email') : '' }}" required autofocus
                                               class="w-full bg-slate-900/60 border border-slate-800 rounded-2xl py-3 pl-10 pr-4 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-slate-900 transition-all custom-input"
                                               placeholder="operator@bakery.com">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-indigo-600/20 hover:from-indigo-600 hover:to-violet-700 hover:shadow-indigo-600/30 transition-all active:scale-[0.99] flex justify-center items-center gap-2">
                                    Send Recovery Email <i class="bi bi-send text-xs"></i>
                                </button>
                                
                                <div class="text-center pt-2">
                                    <button type="button" @click="tab = 'login'" class="text-xs text-slate-400 hover:text-slate-200 font-medium inline-flex items-center gap-1.5">
                                        <i class="bi bi-arrow-left"></i> Return to Login
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </main>

        <!-- Page Footer -->
        <footer class="w-full text-center py-6 border-t border-slate-900/80 relative z-10">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ config('POS Inventory System', 'Solution Clime') }}. All rights reserved. 
            </p>
        </footer>

        <!-- Scripts for Dashboard Simulator -->
        <!-- <script>
            function dashboardSimulator() {
                return {
                    sales: 18450,
                    progress: 45,
                    queueStatus: 'Croissants Baking',
                    timeRemaining: '3m remaining',
                    showToast: false,
                    salesFormatted() {
                        return this.sales.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
                    init() {
                        // Slowly cycle progress and data to show activity
                        setInterval(() => {
                            if (this.progress < 100) {
                                this.progress += 2;
                            } else {
                                this.progress = 0;
                                // Cycle status
                                if (this.queueStatus.includes('Croissants')) {
                                    this.queueStatus = 'Sourdough Preheating';
                                    this.timeRemaining = '8m remaining';
                                } else {
                                    this.queueStatus = 'Croissants Baking';
                                    this.timeRemaining = '5m remaining';
                                }
                            }
                        }, 2000);
                    },
                    simulateOrder() {
                        this.sales += Math.floor(Math.random() * 450) + 150;
                        this.showToast = true;
                        setTimeout(() => this.showToast = false, 2500);
                    }
                }
            }
        </script> -->
    </body>
</html>
