<x-guest-dark-layout>
    <p class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase mb-3">Autentikasi</p>
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">Buat Akun Baru</h1>
    <p class="text-slate-400 mb-8">
        Daftar untuk mulai melaporkan dan memantau kerusakan jalan di sekitar Anda.
    </p>

    <div class="bg-[#131722] border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl">

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    placeholder="Nama Anda"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    placeholder="nama@email.com"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full rounded-lg bg-[#0d101a] border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-bold py-3 transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-slate-400 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-400 font-semibold">Masuk di sini</a>
        </p>
    </div>
</x-guest-dark-layout>
