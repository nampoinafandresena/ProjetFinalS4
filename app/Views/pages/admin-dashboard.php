<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <title>Mobile Money - Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 256px; background: white; border-right: 1px solid #f1f5f9; padding: 20px; display: none; flex-direction: column; z-index: 40; }
        @media (min-width: 1024px) { .sidebar { display: flex; } }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 12px; font-size: 14px; font-weight: 500; color: #64748b; transition: all 0.2s; width: 100%; border: none; background: transparent; cursor: pointer; }
        .nav-item:hover { background: #f1f5f9; }
        .nav-item.active { background: #0f172a; color: white; }
        .nav-item.active:hover { background: #1e293b; }
        .stat-card { padding: 24px; border-radius: 24px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.06); }
        .mobile-nav { display: flex; gap: 4px; padding: 8px 16px; background: white; border-bottom: 1px solid #f1f5f9; overflow-x: auto; }
        @media (min-width: 1024px) { .mobile-nav { display: none; } }
        .mobile-nav-item { padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap; border: none; background: #f1f5f9; color: #64748b; cursor: pointer; }
        .mobile-nav-item.active { background: #0f172a; color: white; }
        .btn-ghost { background: transparent; color: #475569; padding: 8px 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; }
        .btn-ghost:hover { background: #f1f5f9; }
        .badge-admin { background: #8b5cf6; color: white; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="flex items-center gap-3 px-2 py-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-slate-900 leading-tight">Administrateur</p>
                <p class="text-xs text-slate-500">Gestion système</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <button class="nav-item active">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Tableau de bord
            </button>
            <button class="nav-item" onclick="window.location.href='admin-users.html'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Utilisateurs
            </button>
            <button class="nav-item" onclick="window.location.href='admin-config.html'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Configuration
            </button>
            <button class="nav-item" onclick="window.location.href='admin-config.html'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Prefixes
            </button>
        </nav>
        <a href="index.html" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quitter
        </a>
    </aside>

    <!-- Mobile Header & Nav -->
    <header class="lg:hidden bg-white border-b border-slate-100 px-4 py-3 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="font-bold text-slate-900">Administrateur</span>
        </div>
        <a href="index.html" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </header>
    <nav class="lg:hidden mobile-nav">
        <a href="/admin/dashboard"><button class="mobile-nav-item active">Dashboard</button></a>
        <button class="mobile-nav-item" onclick="window.location.href='admin-users.html'">Utilisateurs</button>
        <button class="mobile-nav-item" onclick="window.location.href='admin-config.html'">Configuration</button>
        <a href="/admin/prefixe"><button class="mobile-nav-item" onclick="window.location.href='admin-config.html'">Préfixes</button></a>
    </nav>

    <!-- Main Content -->
    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tableau de bord administrateur</h1>
                <p class="text-slate-500 mt-1">Vue d'ensemble complète du système</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Total utilisateurs</p>
                    <p class="text-2xl font-bold text-slate-900">52</p>
                    <p class="text-xs text-emerald-600 mt-1">+4 ce mois</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Volume total</p>
                    <p class="text-2xl font-bold text-slate-900">3 450 200 Ar</p>
                    <p class="text-xs text-emerald-600 mt-1">+12.4% vs mois dernier</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Frais collectés</p>
                    <p class="text-2xl font-bold text-slate-900">2 850 Ar</p>
                    <p class="text-xs text-emerald-600 mt-1">+8.2% vs mois dernier</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Transactions</p>
                    <p class="text-2xl font-bold text-slate-900">24</p>
                    <p class="text-xs text-emerald-600 mt-1">+6 ce mois</p>
                </div>
            </div>

            <!-- Admins & Opérateurs -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Administrateurs -->
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                            <span class="badge-admin">Admin</span>
                            Administrateurs
                        </h2>
                        <span class="text-sm text-slate-500">3 actifs</span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold">SA</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900">Super Admin</p>
                                <p class="text-xs text-slate-500">admin@mobilemoney.mg</p>
                            </div>
                            <span class="text-xs text-emerald-600 font-semibold">● Actif</span>
                        </div>
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold">JR</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900">Jean Rakoto</p>
                                <p class="text-xs text-slate-500">jean@mobilemoney.mg</p>
                            </div>
                            <span class="text-xs text-emerald-600 font-semibold">● Actif</span>
                        </div>
                    </div>
                </div>

                <!-- Opérateurs récents -->
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Opérateurs récents
                        </h2>
                        <a href="admin-users.html" class="text-sm font-semibold text-violet-600 hover:text-violet-700 transition-colors">Voir tout</a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold">MR</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900">Marie Razafy</p>
                                <p class="text-xs text-slate-500">0337654321</p>
                            </div>
                            <span class="text-xs text-slate-400">22/10/2023</span>
                        </div>
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold">HA</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900">Hery Andry</p>
                                <p class="text-xs text-slate-500">0371122334</p>
                            </div>
                            <span class="text-xs text-slate-400">20/10/2023</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-900">Activité récente</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900">Dépôt de 500 000 Ar</p>
                            <p class="text-xs text-slate-500">Jean Rakoto · 24/10/2023 14:30</p>
                        </div>
                        <span class="text-xs text-emerald-600 font-semibold">Complété</span>
                    </div>
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900">Nouveau barème ajouté</p>
                            <p class="text-xs text-slate-500">Admin · 24/10/2023 11:00</p>
                        </div>
                        <span class="text-xs text-emerald-600 font-semibold">Configuré</span>
                    </div>
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900">Nouvel utilisateur inscrit</p>
                            <p class="text-xs text-slate-500">Hery Andry · 22/10/2023 16:45</p>
                        </div>
                        <span class="text-xs text-emerald-600 font-semibold">Actif</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>