<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Marktplatz-Bedingungen
            </span>
            <h1>Marktplatz-Bedingungen</h1>
            <p>Zuletzt aktualisiert: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Inhaltsverzeichnis</h3>
                    <ul>
                        <li><a href="#gioi-thieu">1. Einleitung</a></li>
                        <li><a href="#nguoi-mua">2. Käuferbedingungen</a></li>
                        <li><a href="#nguoi-ban">3. Verkäuferbedingungen</a></li>
                        <li><a href="#san-pham">4. Produktanforderungen</a></li>
                        <li><a href="#gia-phi">5. Preise & Gebühren</a></li>
                        <li><a href="#thanh-toan">6. Verkäuferauszahlungen</a></li>
                        <li><a href="#ban-quyen">7. Urheberrecht & Lizenz</a></li>
                        <li><a href="#tranh-chap">8. Streitbeilegung</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Einleitung</h2>
                        <p>LamGame Marketplace ist eine Plattform, die Käufer und Verkäufer von Spiel-Quellcode, Assets und Vorlagen verbindet. Diese Bedingungen ergänzen die allgemeinen <a href="{{ url('/dieu-khoan-su-dung') }}">Nutzungsbedingungen</a>.</p>

                        <h3>Beteiligte Parteien:</h3>
                        <ul>
                            <li><strong>LamGame:</strong> Vermittlungsplattform, stellt die Infrastruktur bereit</li>
                            <li><strong>Verkäufer:</strong> Erstellt und verkauft Produkte</li>
                            <li><strong>Käufer:</strong> Kauft und nutzt Produkte</li>
                        </ul>
                    </section>

                    <section id="nguoi-mua">
                        <h2>2. Käuferbedingungen</h2>

                        <h3>2.1. Rechte</h3>
                        <ul>
                            <li>Das Produkt wie beschrieben erhalten</li>
                            <li>Verkäufer-Support in den ersten 30 Tagen</li>
                            <li>Rückerstattungen gemäß der <a href="{{ url('/chinh-sach-hoan-tien') }}">Rückerstattungsrichtlinie</a></li>
                            <li>Kostenlose Updates (je nach Verkäuferrichtlinie)</li>
                            <li>Produkte bewerten und rezensieren</li>
                        </ul>

                        <h3>2.2. Pflichten</h3>
                        <ul>
                            <li>Beschreibung und Demo vor dem Kauf sorgfältig lesen</li>
                            <li>Kompatibilität mit Ihrer Umgebung prüfen</li>
                            <li>Die Produktlizenz einhalten</li>
                            <li>Den Quellcode nicht teilen/weiterverkaufen</li>
                            <li>Ehrlich bewerten, kein Spam</li>
                        </ul>

                        <h3>2.3. Produktsupport</h3>
                        <ul>
                            <li>Der Verkäufer unterstützt: Installation, Fehler im Quellcode, Nutzungshinweise</li>
                            <li>Der Verkäufer unterstützt NICHT: Anpassungen auf Anfrage, Programmieren von Grund auf beibringen</li>
                            <li>Antwortzeit: Je nach Verkäufer, meist 24-72h</li>
                        </ul>
                    </section>

                    <section id="nguoi-ban">
                        <h2>3. Verkäuferbedingungen</h2>

                        <h3>3.1. Verkäuferregistrierung</h3>
                        <ul>
                            <li>Registrieren Sie ein Verkäuferkonto unter <a href="{{ url('/seller/register') }}">lamgame.vn/seller/register</a></li>
                            <li>Geben Sie korrekte Informationen an: Name, E-Mail, Bankdaten</li>
                            <li>Freigabe: 1-3 Werktage</li>
                        </ul>

                        <h3>3.2. Verkäuferrechte</h3>
                        <ul>
                            <li>Produkte auf dem Marktplatz anbieten</li>
                            <li>Eigene Preise festlegen</li>
                            <li>70% der Einnahmen erhalten (LamGame behält 30%)</li>
                            <li>Zugriff auf Verkaufsanalysen und Statistiken</li>
                            <li>Marketing-Unterstützung von LamGame</li>
                        </ul>

                        <h3>3.3. Verkäuferpflichten</h3>
                        <ul>
                            <li>Sicherstellen, dass das Produkt wie beschrieben funktioniert</li>
                            <li>Käufer 30 Tage lang unterstützen</li>
                            <li>Das Produkt bei schwerwiegenden Fehlern aktualisieren</li>
                            <li>Auf Support-Anfragen innerhalb von 72h reagieren</li>
                            <li>Das Urheberrecht einhalten</li>
                        </ul>

                        <h3>3.4. Verbotenes Verhalten</h3>
                        <ul>
                            <li>Verkauf urheberrechtsverletzender Produkte</li>
                            <li>Verkauf von Malware, Backdoors, Schadcode</li>
                            <li>Irreführende Produktbeschreibungen</li>
                            <li>Gefälschte Bewertungen, Manipulation von Ratings</li>
                            <li>Käufer kontaktieren, um außerhalb der Plattform zu handeln</li>
                        </ul>
                    </section>

                    <section id="san-pham">
                        <h2>4. Produktanforderungen</h2>

                        <h3>4.1. Mindestqualität</h3>
                        <ul>
                            <li>Code läuft mit der angegebenen Engine-Version</li>
                            <li>Alle zum Build/Kompilieren nötigen Dateien enthalten</li>
                            <li>Grundlegende Dokumentation oder README</li>
                            <li>Echte Screenshots/Demo-Video</li>
                        </ul>

                        <h3>4.2. Verbotene Produkte</h3>
                        <ul>
                            <li>Urheberrechtsverletzung (gestohlene Assets, Code)</li>
                            <li>Inhalte für Erwachsene, übermäßige Gewalt</li>
                            <li>Enthält Malware oder Backdoors</li>
                            <li>Glücksspiel ohne Lizenz</li>
                            <li>Klone urheberrechtlich geschützter Spiele</li>
                        </ul>

                        <h3>4.3. Prüfprozess</h3>
                        <ol>
                            <li>Verkäufer reicht das Produkt ein</li>
                            <li>Admin-Prüfung (1-5 Tage)</li>
                            <li>Genehmigt → Live auf dem Marktplatz</li>
                            <li>Abgelehnt → Feedback zur Überarbeitung erhalten</li>
                        </ol>
                    </section>

                    <section id="gia-phi">
                        <h2>5. Preise & Gebühren</h2>

                        <h3>5.1. Produktpreise</h3>
                        <ul>
                            <li>Verkäufer legen ihren Preis selbst fest, ab 2 $</li>
                            <li>Können mehrere Lizenzstufen erstellen (Personal, Commercial, Extended)</li>
                            <li>Können Aktionen und Rabatte durchführen</li>
                        </ul>

                        <h3>5.2. Marktplatzgebühren</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Gebühr</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Provisionsgebühr</td><td>30% pro Transaktion</td></tr>
                                <tr><td>Zahlungsgebühr</td><td>In den 30% enthalten</td></tr>
                                <tr><td>Auszahlungsgebühr</td><td>Kostenlos (VN-Banküberweisung)</td></tr>
                            </tbody>
                        </table>
                        <p><strong>Verkäufer erhält:</strong> 70% des Verkaufspreises × verkaufte Menge</p>
                    </section>

                    <section id="thanh-toan">
                        <h2>6. Verkäuferauszahlungen</h2>

                        <h3>6.1. Auszahlungsbedingungen</h3>
                        <ul>
                            <li>Mindestguthaben: 5 $</li>
                            <li>Konto mit verifizierten Bankdaten</li>
                            <li>Keine offenen Streitfälle/Rückerstattungen</li>
                        </ul>

                        <h3>6.2. Auszahlungsplan</h3>
                        <ul>
                            <li>Auszahlungsantrag: Jederzeit</li>
                            <li>Bearbeitung: 3-5 Werktage</li>
                            <li>Haltefrist: 14 Tage nach der Transaktion (zur Bearbeitung von Rückerstattungen)</li>
                        </ul>

                        <h3>6.3. Auszahlungsmethoden</h3>
                        <ul>
                            <li>Vietnamesische Banküberweisung</li>
                            <li>Voraussetzung: Kontoinhabername stimmt mit dem registrierten Namen überein</li>
                        </ul>
                    </section>

                    <section id="ban-quyen">
                        <h2>7. Urheberrecht & Lizenz</h2>

                        <h3>7.1. Eigentum</h3>
                        <ul>
                            <li>Verkäufer behalten das geistige Eigentum an ihren Produkten</li>
                            <li>Käufer erhalten Nutzungsrechte gemäß der gekauften Lizenz</li>
                            <li>LamGame besitzt die Produkte der Verkäufer nicht</li>
                        </ul>

                        <h3>7.2. Lizenztypen</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Lizenz</th>
                                    <th>Nutzung</th>
                                    <th>Weiterverkauf</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Personal</td><td>1 Projekt, nicht kommerziell</td><td>❌</td></tr>
                                <tr><td>Commercial</td><td>1 kommerzielles Projekt</td><td>❌</td></tr>
                                <tr><td>Extended</td><td>Unbegrenzte Projekte</td><td>❌ (kein Quellcode-Weiterverkauf)</td></tr>
                            </tbody>
                        </table>

                        <h3>7.3. Lizenzverstöße</h3>
                        <p>Bei einem festgestellten Verstoß:</p>
                        <ul>
                            <li>Erste Verwarnung</li>
                            <li>Kontosperrung bei Wiederholung</li>
                            <li>Verkäufer können Schadenersatz fordern</li>
                        </ul>
                    </section>

                    <section id="tranh-chap">
                        <h2>8. Streitbeilegung</h2>

                        <h3>8.1. Prozess</h3>
                        <ol>
                            <li><strong>Direkte Klärung:</strong> Käufer und Verkäufer verhandeln</li>
                            <li><strong>LamGame-Support:</strong> Bei Nichtlösung den Support kontaktieren</li>
                            <li><strong>Endgültige Entscheidung:</strong> LamGame entscheidet auf Basis von Nachweisen</li>
                        </ol>

                        <h3>8.2. Häufige Fälle</h3>
                        <ul>
                            <li><strong>Produkt funktioniert nicht:</strong> Verkäufer behebt es oder erstattet</li>
                            <li><strong>Fehlender Support:</strong> Verkäufer verwarnen, Support für den Käufer verlängern</li>
                            <li><strong>Urheberrechtsverletzung:</strong> Produkt entfernen, Käufer erstatten</li>
                        </ul>

                        <div class="lg-legal__contact">
                            <p><strong>Kontakt zur Streitbeilegung</strong></p>
                            <p>E-Mail: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Betreff: [DISPUTE] Bestellnummer - Kurzbeschreibung</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Diese Bedingungen können aktualisiert werden. Wesentliche Änderungen werden Verkäufern per E-Mail mitgeteilt.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
