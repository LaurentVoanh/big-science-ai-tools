        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Version <?= APP_VERSION ?></p>
            <ul class="footer-links">
                <li><a href="?action=home">Accueil</a></li>
                <li><a href="?action=contact">Contact</a></li>
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Confidentialité</a></li>
            </ul>
        </div>
    </footer>

    <script src="<?= $config['site_url'] ?>assets/js/main.js"></script>
</body>
</html>
