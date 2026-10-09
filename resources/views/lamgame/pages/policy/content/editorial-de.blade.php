<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Redaktionsrichtlinie</h1>
                <p class="policy-intro">
                    Bei LamGame.vn verpflichten wir uns, der Game-Developer-Community genaue, nützliche
                    und zuverlässige Inhalte bereitzustellen.
                </p>
            </header>

            <section class="policy-section">
                <h2>🎯 Inhaltsmission</h2>
                <p>
                    Unser Ziel ist es, hochwertige Ressourcen zur Spieleentwicklung zu schaffen, die Entwicklern
                    helfen, auf das neueste Wissen der globalen Spielebranche zuzugreifen.
                </p>
            </section>

            <section class="policy-section">
                <h2>✍️ Unsere Autoren</h2>
                <p>Inhalte auf LamGame.vn werden geschrieben von:</p>
                <ul>
                    <li><strong>Dem LamGame-Redaktionsteam</strong> — Spieleentwicklungsexperten mit langjähriger Erfahrung</li>
                    <li><strong>Gastautoren</strong> — Entwickler und Experten aus Spielestudios im In- und Ausland</li>
                    <li><strong>Mitwirkenden</strong> — Community-Mitglieder mit verifizierter Expertise</li>
                </ul>
                <p>
                    Jeder Autor hat eine eigene Profilseite mit Informationen zu Erfahrung und Fachgebiet.
                    <a href="{{ route('authors.index') }}">Autorenliste ansehen →</a>
                </p>
            </section>

            <section class="policy-section">
                <h2>📋 Redaktionsprozess</h2>
                <p>Jeder Artikel auf LamGame.vn durchläuft einen strengen Prozess:</p>
                <ol>
                    <li>
                        <strong>Recherche & Schreiben</strong>
                        <p>Der Autor recherchiert gründlich und schreibt Inhalte auf Basis realer Erfahrung und offizieller Dokumentation.</p>
                    </li>
                    <li>
                        <strong>Technische Prüfung</strong>
                        <p>Code-Beispiele werden auf den genannten Engine-/Framework-Versionen getestet. Anleitungen werden auf korrekte Funktion verifiziert.</p>
                    </li>
                    <li>
                        <strong>Redaktionelle Prüfung</strong>
                        <p>Redakteure prüfen Genauigkeit, Klarheit und Konsistenz der Inhalte.</p>
                    </li>
                    <li>
                        <strong>Veröffentlichung & Aktualisierung</strong>
                        <p>Artikel werden mit klarem Datum veröffentlicht. Inhalte werden bei Änderungen der Engine/des Frameworks aktualisiert.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📚 Quellen</h2>
                <p>Wir bevorzugen zuverlässige Quellen:</p>
                <ul>
                    <li>Offizielle Dokumentation von Unity, Unreal Engine, Godot usw.</li>
                    <li>Forschung und Artikel von GDC, Gamasutra, Game Developer Magazine</li>
                    <li>Reale Erfahrung aus veröffentlichten Spielprojekten</li>
                    <li>Interviews und Einblicke von renommierten Entwicklern</li>
                </ul>
                <p>Quellen werden bei Bedarf in jedem Artikel klar angegeben.</p>
            </section>

            <section class="policy-section">
                <h2>🔄 Inhaltsaktualisierungen</h2>
                <p>
                    Spieleentwicklung ist ein schnelllebiges Feld. Wir überprüfen und aktualisieren
                    Artikel regelmäßig, um die Genauigkeit sicherzustellen:
                </p>
                <ul>
                    <li>Tutorial-Artikel werden überprüft, wenn eine Engine/ein Framework eine neue Version veröffentlicht</li>
                    <li>API-Informationen werden gemäß der offiziellen Dokumentation aktualisiert</li>
                    <li>Das Datum „Zuletzt aktualisiert" wird in jedem Artikel angezeigt</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>⚠️ Haftungsausschluss</h2>
                <ul>
                    <li>Code-Beispiele dienen nur zur Veranschaulichung und müssen für die Produktion angepasst werden</li>
                    <li>Leistung und Ergebnisse können je nach Umgebung variieren</li>
                    <li>Wir übernehmen keine Verantwortung für Schäden durch die Anwendung der Inhalte</li>
                    <li>Preis- und Lizenzinformationen können sich je nach Anbieter ändern</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📧 Feedback & Beiträge</h2>
                <p>
                    Wir begrüßen jedes Feedback zur Verbesserung der Inhaltsqualität:
                </p>
                <ul>
                    <li>Einen technischen Fehler gefunden? <a href="{{ route('lamgame.lien-he') }}">Melden Sie ihn uns</a></li>
                    <li>Möchten Sie einen Artikel beitragen? <a href="{{ route('lamgame.lien-he') }}">Kontaktieren Sie das Redaktionsteam</a></li>
                    <li>Eine Inhaltskorrektur anfordern? Siehe die <a href="{{ route('lamgame.chinh-sach-chinh-sua') }}">Korrekturrichtlinie</a></li>
                </ul>
            </section>

            <footer class="policy-footer">
                <p><strong>Zuletzt aktualisiert:</strong> {{ now()->format('d/m/Y') }}</p>
            </footer>
        </article>
    </div>
</div>
