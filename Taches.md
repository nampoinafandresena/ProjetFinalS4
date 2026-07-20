# Projet Final S4
ETU004017 - ETU004255
- tag par version 
    -rehfa manao commit : omena marque hoe V1

- [ok] creation de Taches.mg
- [ok] Base: base.sql
    - operateur 
    - type_operation
    - bareme_frais -> switch case
    - historiques

        - Model
        - View
            - donnees
            - CSS
        - Controller
        - BASE

- [OK] Model de base
    - [ok] model de base par tables
- [WIP] Migration et Seed 
    - migration
    - seed
- [OK] Template de l application

- Cote operateur
    - configuration des préfixes valable de l’opérateur (ex: 033 et 037)
        - ajout de nouveaux prefixes
    - creation des type d operations & modifications des tranches
        - modfication des prix dans les tranches selon le type de transaction 
    - gain (via frais de retrait et transfert)
        - globale (tout operateur confondu)
        - selon operateur
    - situation des comptes clients 
        - situation globale
            - solde
            - transactions (historiques)
V2
    - Configuration % en plus de commissions pour les transferts vers les autres opérateurs 
    - Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs
    - Situation des montants à envoyer à chaque opérateur


- Cote client
    - login automatique
        - numero
        - se connecter
    - operations
        - voir le solde
        - faire un depot
        - faire un retrait
        - faire un transfert
        - voir les historiques
        
V2
- Option inclure frais de retrait lors de l’envoi il n’y a pas de frais de retrait pour les autres opérateurs
- Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
même opérateur uniquement


- avadika 0.0 le solde voalohany ao amin ny seeder amin ny farany