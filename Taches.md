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
- [ok] Migration et Seed 
    - [ok] migration
    - [ok] seed
- [OK] Template de l application

- [ok] Cote operateur
    - [ok] configuration des préfixes valable de l’opérateur (ex: 033 et 037)
        - [ok] ajout de nouveaux prefixes
    - [ok] creation des type d operations & modifications des tranches
        - [ok] modfication des prix dans les tranches selon le type de transaction 
    - [ok] gain (via frais de retrait et transfert)
        - [ok] globale (tout operateur confondu)
        - [ok] selon operateur
    - [ok] situation des comptes clients 
        - [ok] situation globale
            - [ok] solde
            - [ok] transactions (historiques)
V2
    - [ok] Configuration % en plus de commissions pour les transferts vers les autres opérateurs 
    - [ok] Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs
    - [wip] Situation des montants à envoyer à chaque opérateur


- [ok] Cote client
    - [ok] login automatique
        - [ok] numero
        - [ok] se connecter
    - [ok] operations
        - [ok] voir le solde
        - [ok] faire un depot
        - [ok] faire un retrait
        - [ok] faire un transfert
        - [ok] voir les historiques
        
V2
- [ok] Option inclure frais de retrait lors de l’envoi il n’y a pas de frais de retrait pour les autres opérateurs
- [wip] Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
même opérateur uniquement


- avadika 0.0 le solde voalohany ao amin ny seeder amin ny farany