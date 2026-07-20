<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <title>Mobile Money - Préfixes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        
        /* Sidebar */
        .sidebar { 
            position: fixed; 
            top: 0; 
            left: 0; 
            height: 100vh; 
            width: 256px; 
            background: white; 
            border-right: 1px solid #f1f5f9; 
            padding: 20px; 
            display: none; 
            flex-direction: column; 
            z-index: 40; 
        }
        @media (min-width: 1024px) { 
            .sidebar { display: flex; } 
        }
        
        /* Navigation items */
        .nav-item { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 10px 12px; 
            border-radius: 12px; 
            font-size: 14px; 
            font-weight: 500; 
            color: #64748b; 
            transition: all 0.2s; 
            width: 100%; 
            border: none; 
            background: transparent; 
            cursor: pointer; 
        }
        .nav-item:hover { background: #f1f5f9; }
        .nav-item.active { 
            background: #0f172a; 
            color: white; 
        }
        .nav-item.active:hover { background: #1e293b; }
        
        /* Stat cards */
        .stat-card { 
            padding: 24px; 
            border-radius: 24px; 
            background: white; 
            border: 1px solid #f1f5f9; 
            transition: all 0.3s; 
            cursor: pointer; 
        }
        .stat-card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 12px 24px rgba(0,0,0,0.06); 
        }
        
        /* Mobile navigation */
        .mobile-nav { 
            display: flex; 
            gap: 4px; 
            padding: 8px 16px; 
            background: white; 
            border-bottom: 1px solid #f1f5f9; 
            overflow-x: auto; 
        }
        @media (min-width: 1024px) { 
            .mobile-nav { display: none; } 
        }
        .mobile-nav-item { 
            padding: 8px 12px; 
            border-radius: 8px; 
            font-size: 12px; 
            font-weight: 600; 
            white-space: nowrap; 
            border: none; 
            background: #f1f5f9; 
            color: #64748b; 
            cursor: pointer; 
        }
        .mobile-nav-item.active { 
            background: #0f172a; 
            color: white; 
        }
        
        /* Buttons */
        .btn-primary { 
            background: #0f766e; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 12px; 
            font-weight: 600; 
            border: none; 
            cursor: pointer; 
            transition: all 0.2s; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
        }
        .btn-primary:hover { 
            background: #0d6b63; 
            transform: scale(0.98); 
        }
        
        /* Prefix items */
        .prefix-item { 
            padding: 16px 24px; 
            border-bottom: 1px solid #f8fafc; 
            display: flex; 
            align-items: center; 
            gap: 16px; 
            transition: background 0.2s; 
        }
        .prefix-item:hover { background: #f8fafc; }
        
        /* Modal overlay */
        .modal-overlay { 
            position: fixed; 
            inset: 0; 
            background: rgba(15,23,42,0.6); 
            backdrop-filter: blur(4px); 
            z-index: 50; 
            display: none; 
            align-items: center; 
            justify-content: center; 
            padding: 16px; 
        }
        .modal-overlay.active { display: flex; }
        
        /* Modal content */
        .modal-content { 
            background: white; 
            border-radius: 24px; 
            max-width: 420px; 
            width: 100%; 
            padding: 24px; 
            animation: scaleIn 0.2s ease-out; 
        }
        @keyframes scaleIn { 
            from { opacity: 0; transform: scale(0.95); } 
            to { opacity: 1; transform: scale(1); } 
        }
        
        /* Input fields */
        .input-field { 
            width: 100%; 
            padding: 12px 16px; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            background: white; 
            font-weight: 500; 
            font-size: 18px; 
            text-align: center; 
            letter-spacing: 4px; 
            transition: all 0.2s; 
        }
        .input-field:focus { 
            outline: none; 
            border-color: #0f766e; 
            box-shadow: 0 0 0 3px rgba(15,118,110,0.2); 
        }
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
            <button class="nav-item" onclick="window.location.href='admin.html'">
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
            <button class="nav-item active">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                Préfixes
            </button>
        </nav>
        <a href="login.html" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
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
        <a href="login.html" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </header>
    <nav class="lg:hidden mobile-nav">
        <a href="/admin/dashboard"><button class="mobile-nav-item" onclick="window.location.href='admin.html'">Dashboard</button></a>
        <button class="mobile-nav-item" onclick="window.location.href='admin-users.html'">Utilisateurs</button>
        <button class="mobile-nav-item" onclick="window.location.href='admin-config.html'">Configuration</button>
        <a href="/amin/prefixe"><button class="mobile-nav-item active">Préfixes</button></a>
    </nav>

    <!-- Main Content -->
    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Préfixes téléphoniques</h1>
                    <p class="text-slate-500 mt-1">Gérez les préfixes valides pour la création de comptes</p>
                </div>
                <button class="btn-primary" onclick="openModal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter
                </button>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Total préfixes</p>
                    <p class="text-2xl font-bold text-slate-900"><?= $total ?? 0 ?></p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Actifs</p>
                    <p class="text-2xl font-bold text-slate-900"><?= $actifs ?? 0 ?></p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Inactifs</p>
                    <p class="text-2xl font-bold text-slate-900"><?= $inactifs ?? 0 ?></p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Opérateurs</p>
                    <p class="text-2xl font-bold text-slate-900"><?= count($operateurs ?? []) ?></p>
                </div>
            </div>

            <!-- Liste des préfixes -->
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="font-semibold text-slate-900">Liste des préfixes</h2>
                </div>
                
                <?php if (!empty($prefixes)): ?>
                    <?php foreach ($prefixes as $prefix): ?>
                        <div class="prefix-item">
                            <div class="w-12 h-12 rounded-2xl <?= ($prefix['actif'] ?? 1) ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' ?> flex items-center justify-center font-bold text-lg">
                                <?= esc($prefix['prefixes']) ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold <?= ($prefix['actif'] ?? 1) ? 'text-slate-900' : 'text-slate-400' ?>">
                                    +<?= esc($prefix['prefixes']) ?>
                                </p>
                                <p class="text-xs text-slate-500"><?= esc($prefix['operateur'] ?? 'Inconnu') ?></p>
                            </div>
                            
                            <form action="<?= base_url('admin/prefixe/toggle/' . $prefix['id']) ?>" method="POST" class="inline">
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Activer/Désactiver">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                            <form action="<?= base_url('admin/prefixe/delete/' . $prefix['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce préfixe ?')">
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 text-slate-500">
                        <p>Aucun préfixe configuré</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Nouveau préfixe</h3>
            <form action="<?= base_url('admin/prefixe/add') ?>" method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Préfixe (chiffres uniquement)</label>
                        <input type="tel" name="prefixes" placeholder="033" maxlength="4" class="input-field" required />
                        <p class="text-xs text-slate-400 mt-2">Entre 2 et 4 chiffres.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Opérateur</label>
                        <select name="id_operateur" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all" required>
                            <option value="">Sélectionner un opérateur</option>
                            <?php foreach ($operateursList ?? [] as $operateur): ?>
                                <option value="<?= $operateur['id'] ?>"><?= esc($operateur['operateur']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-all">Annuler</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { 
            document.getElementById('modal').classList.add('active'); 
        }
        function closeModal() { 
            document.getElementById('modal').classList.remove('active'); 
        }
        document.addEventListener('keydown', (e) => { 
            if (e.key === 'Escape') closeModal(); 
        });
        document.getElementById('modal').addEventListener('click', (e) => { 
            if (e.target === e.currentTarget) closeModal(); 
        });
    </script>
</body>
</html>