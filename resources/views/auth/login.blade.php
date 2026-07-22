<x-guest-dark-layout>
    <p class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase mb-3">Autentikasi</p>
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">Masuk ke Akun</h1>
    <p class="text-slate-400 mb-8">
        Gunakan akun untuk melapor dan memantau status laporan Anda.
    </p>

    <div class="bg-[#131722] border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="nama@email.com"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center gap-2 text-slate-400 select-none cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-slate-600 bg-[#0d101a] text-orange-500 focus:ring-orange-500 focus:ring-offset-0">
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a class="text-orange-500 hover:text-orange-400 font-medium" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-bold py-3 transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-slate-400 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-orange-500 hover:text-orange-400 font-semibold">Daftar di sini</a>
        </p>
    </div>
</x-guest-dark-layout>
