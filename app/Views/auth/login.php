<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="<?= base_url('assets/css/style2.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title>Mobile Money</title>
    <script src="<?= base_url('assets/js/tailwind.js') ?>"></script>


</head>

<body>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/30 to-teal-50/20 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <div class="text-center mb-12">
                <div
                    class="inline-flex w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 items-center justify-center shadow-2xl shadow-emerald-500/30 mb-5 animate-float">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 18v-5m0 0V8m0 5h5m-5 0H7m6-9H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" />
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 tracking-tight">Mobile Money</h1>
                <p class="text-slate-500 mt-3 text-lg">Plateforme de simulation d'opérateur mobile money</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Espace Client -->
                <a href="<?= base_url('client/login') ?>"
                    class="group relative overflow-hidden p-8 rounded-3xl bg-white border border-slate-100 card-hover text-left block">
                    <div
                        class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-emerald-100/50 blur-3xl group-hover:bg-emerald-200/60 transition-colors">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center mb-5 transition-colors">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18v-5m0 0V8m0 5h5m-5 0H7m6-9H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900">Espace Client</h2>
                        <p class="text-slate-500 mt-2 text-sm">Connectez-vous avec votre numéro pour déposer, retirer et
                            transférer de l'argent.</p>
                        <div
                            class="mt-6 inline-flex items-center gap-2 text-emerald-600 font-semibold text-sm group-hover:gap-3 transition-all">
                            Accéder
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Espace Opérateur / Admin -->
                <a href="<?= base_url('admin/dashboard') ?>"
                    class="group relative overflow-hidden p-8 rounded-3xl bg-white border border-slate-100 card-hover text-left block">
                    <div
                        class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-slate-200/50 blur-3xl group-hover:bg-slate-300/60 transition-colors">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 rounded-2xl bg-slate-100 group-hover:bg-slate-200 flex items-center justify-center mb-5 transition-colors">
                            <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900">Espace Opérateur</h2>
                        <p class="text-slate-500 mt-2 text-sm">Gérez les préfixes, types d'opérations, barèmes de frais
                            et consultez les gains.</p>
                        <div
                            class="mt-6 inline-flex items-center gap-2 text-slate-900 font-semibold text-sm group-hover:gap-3 transition-all">
                            Accéder
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <p class="text-center text-xs text-slate-400 mt-10">Système de simulation - Projet S4 Info & Design</p>
        </div>
    </div>
</body>

</html>