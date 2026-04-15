<?php
declare(strict_types=1);

/**
 * ╔══════════════════════════════════════════════════════════════════════╗
 * ║  NEXUS AI — CONFIG.PHP V5                                            ║
 * ║  Configuration centrale • APIs scientifiques • IA Mistral            ║
 * ║  Auto-recherche avec auto-renforcement et communication              ║
 * ╚══════════════════════════════════════════════════════════════════════╝
 */

define('APP_VERSION', '5.0.0');
define('DB_PATH',     __DIR__ . '/nexus.db');
define('LOG_PATH',    __DIR__ . '/logs/app.log');
define('PROMPTS_PATH', __DIR__ . '/prompts/');
define('ARTICLES_PATH', __DIR__ . '/articles/');
define('MINI_APPS_PATH', __DIR__ . '/mini_apps/');

// ============================================================================
// CLÉS API MISTRAL — Rotation automatique avec fallback
// ============================================================================
define('MISTRAL_KEYS', [
    'a5qaRTjWUjGJpAk5z35XcdEP5ZbH8Rakec',
    'bo3rG1zvdq1yDOvjb7Z4J3J3eHXRShytub',
    'cvEzQMKN74Ez8RIwJ6y8J30ENDjFruXkFa'
]);
define('MISTRAL_API',   'https://api.mistral.ai/v1/chat/completions');
define('MISTRAL_MODEL', 'pixtral-12b-2409');
define('MISTRAL_DEEP_MODEL', 'mistral-large-latest');

// ============================================================================
// 36 SOURCES SCIENTIFIQUES — URLs validées + descriptions pour l'IA
// ============================================================================
define('SOURCES_CONFIG', [
    // ─── LITTÉRATURE BIOMÉDICALE ────────────────────────────
    'PubMed' => [
        'url'   => 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json&retmax=8&sort=relevance&term={TERM}',
        'desc'  => 'Base de données biomédicale principale NCBI',
        'query' => 'Terme de recherche PubMed. Ex: "myocarditis mRNA vaccine"',
        'type'  => 'literature',
    ],
    'EuropePMC' => [
        'url'   => 'https://www.ebi.ac.uk/europepmc/webservices/rest/search?format=json&pageSize=8&sort=CITED&query={TERM}',
        'desc'  => 'Europe PubMed Central — texte intégral annoté',
        'query' => 'Recherche libre en anglais',
        'type'  => 'literature',
    ],
    'OpenAlex' => [
        'url'   => 'https://api.openalex.org/works?per_page=8&sort=cited_by_count:desc&search={TERM}',
        'desc'  => '250M+ publications avec graphe de citations',
        'query' => 'Terme de recherche en anglais',
        'type'  => 'literature',
    ],
    'CrossRef' => [
        'url'   => 'https://api.crossref.org/works?rows=6&sort=relevance&query={TERM}',
        'desc'  => 'Métadonnées DOI — CrossRef',
        'query' => 'Recherche libre',
        'type'  => 'literature',
    ],
    'arXiv' => [
        'url'   => 'https://export.arxiv.org/api/query?max_results=6&sortBy=relevance&search_query=all:{TERM}',
        'desc'  => 'Préprints scientifiques (biologie, physique, IA)',
        'query' => 'Termes en anglais sans guillemets',
        'type'  => 'preprint',
    ],
    'Zenodo' => [
        'url'   => 'https://zenodo.org/api/records?size=6&sort=mostrecent&q={TERM}',
        'desc'  => 'Dépôt de datasets, codes et publications',
        'query' => 'Recherche libre',
        'type'  => 'data',
    ],
    'INSPIRE-HEP' => [
        'url'   => 'https://inspirehep.net/api/literature?size=5&sort=mostrecent&q={TERM}',
        'desc'  => 'Physique des hautes énergies et biophysique',
        'query' => 'Terme en anglais',
        'type'  => 'literature',
    ],
    'DataCite' => [
        'url'   => 'https://api.datacite.org/works?page[size]=5&query={TERM}',
        'desc'  => 'Datasets avec DOI — DataCite',
        'query' => 'Recherche de datasets scientifiques',
        'type'  => 'data',
    ],

    // ─── GÉNÉTIQUE & PROTÉINES ──────────────────────────────
    'UniProt' => [
        'url'   => 'https://rest.uniprot.org/uniprotkb/search?format=json&size=5&query={TERM}+AND+reviewed:true',
        'desc'  => 'Base de données universelle des protéines',
        'query' => 'Nom de gène ou protéine. Ex: TP53, BRCA1',
        'type'  => 'protein',
    ],
    'Ensembl' => [
        'url'   => 'https://rest.ensembl.org/lookup/symbol/homo_sapiens/{TERM}?content-type=application/json',
        'desc'  => 'Génomique — coordonnées chromosomiques',
        'query' => 'NOM exact du gène humain',
        'type'  => 'genomics',
    ],
    'ClinVar' => [
        'url'   => 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=clinvar&retmode=json&retmax=6&term={TERM}',
        'desc'  => 'Variants génétiques cliniques NCBI',
        'query' => 'Terme de recherche clinvar',
        'type'  => 'genomics',
    ],
    'GEO' => [
        'url'   => 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=gds&retmode=json&retmax=5&term={TERM}',
        'desc'  => 'Gene Expression Omnibus — données d\'expression',
        'query' => 'Terme de recherche GEO',
        'type'  => 'genomics',
    ],
    'ArrayExpress' => [
        'url'   => 'https://www.ebi.ac.uk/arrayexpress/json/v1/samples?query={TERM}&size=5',
        'desc'  => 'Données d\'expression génique — EMBL-EBI',
        'query' => 'Terme de recherche',
        'type'  => 'genomics',
    ],
    'HGNC' => [
        'url'   => 'https://rest.genenames.org/search/symbol/{TERM}',
        'desc'  => 'Nomenclature officielle des gènes humains',
        'query' => 'Symbole du gène',
        'type'  => 'genomics',
    ],
    'OMIM' => [
        'url'   => 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=omim&retmode=json&retmax=5&term={TERM}',
        'desc'  => 'Catalogue des gènes et maladies génétiques',
        'query' => 'Maladie ou gène',
        'type'  => 'genomics',
    ],
    'GWAS Catalog' => [
        'url'   => 'https://www.ebi.ac.uk/gwas/rest/api/studies?query={TERM}&size=5',
        'desc'  => 'Catalogue des associations pangénomiques',
        'query' => 'Maladie ou trait',
        'type'  => 'genomics',
    ],

    // ─── PATHWAYS & INTERACTIONS ────────────────────────────
    'Reactome' => [
        'url'   => 'https://reactome.org/ContentService/searcher/queries?query={TERM}&page=0&size=5',
        'desc'  => 'Base de données de pathways biologiques',
        'query' => 'Protéine ou pathway',
        'type'  => 'pathway',
    ],
    'KEGG' => [
        'url'   => 'https://rest.kegg.jp/find/pathways/{TERM}',
        'desc'  => 'Encyclopedia of Genes and Genomes',
        'query' => 'Pathway ou gène',
        'type'  => 'pathway',
    ],
    'STRING' => [
        'url'   => 'https://string-db.org/api/json/network?identifiers={TERM}&species=9606',
        'desc'  => 'Réseau d\'interactions protéine-protéine',
        'query' => 'Protéine (nom ou UniProt ID)',
        'type'  => 'interaction',
    ],
    'BioGRID' => [
        'url'   => 'https://webservice.biogrid.org/search/gene?taxid=9606&gene={TERM}&includeSynonyms=false',
        'desc'  => 'Interactions génétiques et protéiques',
        'query' => 'Gène',
        'type'  => 'interaction',
    ],
    'IntAct' => [
        'url'   => 'https://www.ebi.ac.uk/intact/ws/interactioncount?query={TERM}',
        'desc'  => 'Base de données d\'interactions moléculaires',
        'query' => 'Protéine',
        'type'  => 'interaction',
    ],

    // ─── COMPOSÉS & PHARMACOLOGIE ───────────────────────────
    'ChEMBL' => [
        'url'   => 'https://www.ebi.ac.uk/chembl/api/data/molecule.json?molecule_chembl_id__icontains={TERM}&limit=5',
        'desc'  => 'Composés bioactifs — EMBL-EBI',
        'query' => 'Composé ou cible',
        'type'  => 'compound',
    ],
    'PubChem' => [
        'url'   => 'https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/name/{TERM}/property/IsomericSMILES/JSON',
        'desc'  => 'Base de données de composés chimiques',
        'query' => 'Nom du composé',
        'type'  => 'compound',
    ],
    'DrugBank' => [
        'url'   => 'https://go.drugbank.com/drugs.json?query={TERM}&limit=5',
        'desc'  => 'Base de données de médicaments',
        'query' => 'Médicament ou cible',
        'type'  => 'compound',
    ],
    'BindingDB' => [
        'url'   => 'https://www.bindingdb.org/rwd/bindingsdo/search.jsp?search={TERM}&type=quick',
        'desc'  => 'Affinités de liaison protéine-ligand',
        'query' => 'Protéine ou ligand',
        'type'  => 'compound',
    ],
    'PharmGKB' => [
        'url'   => 'https://api.pharmgkb.org/genes?query={TERM}&size=5',
        'desc'  => 'Pharmacogénomique — gènes et médicaments',
        'query' => 'Gène ou médicament',
        'type'  => 'compound',
    ],

    // ─── MALADIES & CLINIQUE ────────────────────────────────
    'DisGeNET' => [
        'url'   => 'https://www.disgenet.org/api/gene_disease_associations/?query={TERM}&limit=5',
        'desc'  => 'Associations gènes-maladies',
        'query' => 'Maladie ou gène',
        'type'  => 'disease',
    ],
    'ClinicalTrials' => [
        'url'   => 'https://clinicaltrials.gov/api/v2/studies?query.cond={TERM}&pageSize=5',
        'desc'  => 'Essais cliniques enregistrés',
        'query' => 'Condition ou intervention',
        'type'  => 'clinical',
    ],
    'MedlinePlus' => [
        'url'   => 'https://medlineplus.gov/download/genetics/gene/{TERM}.json',
        'desc'  => 'Informations santé grand public',
        'query' => 'Gène ou maladie',
        'type'  => 'clinical',
    ],
    'Orphanet' => [
        'url'   => 'https://www.orpha.net/consor/cgi-bin/OC_Exp.php?Lng=EN&Expert={TERM}',
        'desc'  => 'Maladies rares et médicaments orphelins',
        'query' => 'ID Orphanet ou nom maladie',
        'type'  => 'disease',
    ],
    'ICD-11' => [
        'url'   => 'https://icd.who.int/browse11/l-m/en#/http%3a%2f%2fid.who.int%2ficd%2fentity%2f{TERM}',
        'desc'  => 'Classification internationale des maladies',
        'query' => 'Code ou nom maladie',
        'type'  => 'clinical',
    ],

    // ─── PRÉPRINTS & CONFÉRENCES ────────────────────────────
    'bioRxiv' => [
        'url'   => 'https://api.biorxiv.org/details/biorxiv/{TERM}/2023-01-01/2025-12-31?cursor=0&limit=5',
        'desc'  => 'Préprints en biologie',
        'query' => 'Terme de recherche',
        'type'  => 'preprint',
    ],
    'medRxiv' => [
        'url'   => 'https://api.biorxiv.org/details/medrxiv/{TERM}/2023-01-01/2025-12-31?cursor=0&limit=5',
        'desc'  => 'Préprints en médecine',
        'query' => 'Terme de recherche',
        'type'  => 'preprint',
    ],
    'HAL' => [
        'url'   => 'https://api.archives-ouvertes.fr/search/?q={TERM}&rows=5',
        'desc'  => 'Archive ouverte française',
        'query' => 'Terme de recherche',
        'type'  => 'preprint',
    ],

    // ─── PHYSIQUE & CHIMIE ──────────────────────────────────
    'NASA ADS' => [
        'url'   => 'https://api.adsabs.harvard.edu/v1/search/query?q={TERM}&rows=5',
        'desc'  => 'Astrophysique et physique',
        'query' => 'Terme de recherche',
        'type'  => 'physics',
    ],
    'RCSB PDB' => [
        'url'   => 'https://search.rcsb.org/rcsbsearch/v2/query?json={"query":{"type":"terminal","service":"full_text","parameters":{"value":"{TERM}"}},"return_type":"entry"}',
        'desc'  => 'Protein Data Bank — structures 3D',
        'query' => 'Protéine ou molécule',
        'type'  => 'structure',
    ],
    'NIST' => [
        'url'   => 'https://webbook.nist.gov/cgi/cbook.cgi?Name={TERM}&Units=SI',
        'desc'  => 'Données chimiques et physiques',
        'query' => 'Composé chimique',
        'type'  => 'chemistry',
    ],

    // ─── SCIENCES DE L'ENVIRONNEMENT ────────────────────────
    'GBIF' => [
        'url'   => 'https://api.gbif.org/v1/occurrence/search?taxon_key={TERM}&limit=5',
        'desc'  => 'Données biodiversité mondiale',
        'query' => 'Taxon ID',
        'type'  => 'environment',
    ],
    'NOAA' => [
        'url'   => 'https://www.ncdc.noaa.gov/cdo-web/api/v2/data?datasetid=GHCND&startdate=2023-01-01&enddate=2025-12-31&limit=5',
        'desc'  => 'Données climatiques NOAA',
        'query' => 'Paramètres climatiques',
        'type'  => 'environment',
    ],
    'IPCC' => [
        'url'   => 'https://www.ipcc.ch/report/ar6/wg1/downloads/figures/',
        'desc'  => 'Rapports sur le climat',
        'query' => 'Rapport IPCC',
        'type'  => 'environment',
    ],

    // ─── SCIENCES SOCIALES & ÉCONOMIE ───────────────────────
    'RePEc' => [
        'url'   => 'https://ideas.repec.org/search.html?query={TERM}&page=1',
        'desc'  => 'Publications en économie',
        'query' => 'Terme de recherche',
        'type'  => 'social',
    ],
    'SSRN' => [
        'url'   => 'https://papers.ssrn.com/sol3/cf_dev/AbsByAuth.cfm?per_id={TERM}',
        'desc'  => 'Sciences sociales — préprints',
        'query' => 'Auteur ou terme',
        'type'  => 'social',
    ],
    'PsycINFO' => [
        'url'   => 'https://www.apa.org/pubs/databases/psycinfo/',
        'desc'  => 'Psychologie et neurosciences',
        'query' => 'Terme de recherche',
        'type'  => 'social',
    ],
]);

// ============================================================================
// INITIALISATION SESSION
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// FONCTIONS UTILITAIRES
// ============================================================================

function getMistralKey(): string {
    $keys = MISTRAL_KEYS;
    $index = $_SESSION['mistral_key_index'] ?? 0;
    return $keys[$index % count($keys)];
}

function rotateMistralKey(): void {
    $index = $_SESSION['mistral_key_index'] ?? 0;
    $_SESSION['mistral_key_index'] = ($index + 1) % count(MISTRAL_KEYS);
}

function logSession(string $sessionId, string $message): void {
    $logFile = LOG_PATH . '/sessions/' . $sessionId . '.txt';
    $timestamp = date('[H:i:s]');
    file_put_contents($logFile, "$timestamp $message\n", FILE_APPEND | LOCK_EX);
}

function initDatabase(): PDO {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Création des tables si elles n'existent pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sessions (
            id TEXT PRIMARY KEY,
            topic TEXT,
            context TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status TEXT DEFAULT 'running'
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id TEXT,
            title TEXT,
            content TEXT,
            validation_score REAL DEFAULT 0.0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (session_id) REFERENCES sessions(id)
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS query_strategies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            source TEXT,
            query_template TEXT,
            success_rate REAL DEFAULT 0.5,
            last_used DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS findings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id TEXT,
            source TEXT,
            data TEXT,
            relevance_score REAL DEFAULT 0.5,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS mini_apps (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id TEXT,
            app_path TEXT,
            hypothesis TEXT,
            results TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    return $pdo;
}

// Initialisation de la base de données
$pdo = initDatabase();
