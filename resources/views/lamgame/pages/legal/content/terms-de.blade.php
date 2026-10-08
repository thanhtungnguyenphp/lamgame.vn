<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Nutzungsbedingungen
            </span>
            <h1>Nutzungsbedingungen</h1>
            <p>Zuletzt aktualisiert: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Inhaltsverzeichnis</h3>
                    <ul>
                        <li><a href="#chap-nhan">1. Annahme der Bedingungen</a></li>
                        <li><a href="#dich-vu">2. Leistungsbeschreibung</a></li>
                        <li><a href="#tai-khoan">3. Konto</a></li>
                        <li><a href="#mua-hang">4. Käufe & Zahlung</a></li>
                        <li><a href="#san-pham-so">5. Digitale Produkte</a></li>
                        <li><a href="#noi-dung">6. Nutzerinhalte</a></li>
                        <li><a href="#cam-ket">7. Nutzungsverpflichtungen</a></li>
                        <li><a href="#trach-nhiem">8. Haftungsbeschränkung</a></li>
                        <li><a href="#cham-dut">9. Kündigung</a></li>
                        <li><a href="#lien-he">10. Kontakt</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="chap-nhan">
                        <h2>1. Annahme der Bedingungen</h2>
                        <p>Durch den Zugriff auf und die Nutzung der Website LamGame.vn (der „Dienst") erklären Sie sich mit diesen Bedingungen einverstanden. Wenn Sie nicht einverstanden sind, nutzen Sie den Dienst bitte nicht.</p>
                        <p>Für bestimmte Dienste können zusätzliche Bedingungen gelten:</p>
                        <ul>
                            <li><a href="{{ url('/dieu-khoan-marketplace') }}">Marktplatz-Bedingungen</a> — Für Käufer/Verkäufer von Quellcode</li>
                            <li><a href="{{ url('/dieu-khoan-ai') }}">KI-Bedingungen</a> — Für die KI-Tools</li>
                            <li><a href="{{ url('/chinh-sach-hoan-tien') }}">Rückerstattungsrichtlinie</a></li>
                        </ul>
                    </section>

                    <section id="dich-vu">
                        <h2>2. Leistungsbeschreibung</h2>
                        <p>LamGame.vn bietet:</p>
                        <ul>
                            <li><strong>Marktplatz:</strong> Eine Plattform zum Kauf und Verkauf von Spiel-Quellcode, Assets und Vorlagen</li>
                            <li><strong>KI-Tools:</strong> KI-Tools für die Spieleentwicklung (GDD-Generator, Code-Assistent, Asset-Generator)</li>
                            <li><strong>Lernen:</strong> Blog, Tutorials und Kurse zur Spieleentwicklung</li>
                            <li><strong>Community:</strong> Ein Forum zum Wissensaustausch</li>
                        </ul>
                        <p>Wir behalten uns das Recht vor, Teile des Dienstes mit angemessener Ankündigung zu ändern, auszusetzen oder einzustellen.</p>
                    </section>

                    <section id="tai-khoan">
                        <h2>3. Benutzerkonten</h2>

                        <h3>3.1. Registrierung</h3>
                        <ul>
                            <li>Sie müssen 18 Jahre oder älter sein oder die Zustimmung der Eltern haben</li>
                            <li>Geben Sie korrekte und aktuelle Informationen an</li>
                            <li>Jede Person darf nur ein Konto verwenden</li>
                        </ul>

                        <h3>3.2. Kontosicherheit</h3>
                        <ul>
                            <li>Sie sind für die Sicherheit Ihres Passworts verantwortlich</li>
                            <li>Benachrichtigen Sie uns sofort bei unbefugtem Zugriff</li>
                            <li>Teilen Sie Ihr Konto nicht mit anderen</li>
                        </ul>

                        <h3>3.3. Kontosperrung</h3>
                        <p>Wir behalten uns das Recht vor, Ihr Konto zu sperren oder zu löschen, wenn Sie gegen die Bedingungen verstoßen, einschließlich:</p>
                        <ul>
                            <li>Angabe falscher Informationen</li>
                            <li>Betrug oder Täuschung</li>
                            <li>Urheberrechtsverletzung</li>
                            <li>Spamming oder Belästigung anderer Nutzer</li>
                        </ul>
                    </section>

                    <section id="mua-hang">
                        <h2>4. Käufe & Zahlung</h2>

                        <h3>4.1. Preise</h3>
                        <ul>
                            <li>Die Preise werden in USD angezeigt, inklusive MwSt. (falls zutreffend)</li>
                            <li>Preise können ohne Vorankündigung geändert werden</li>
                            <li>Es gilt der Preis zum Zeitpunkt der Bestellung</li>
                        </ul>

                        <h3>4.2. Zahlungsmethoden</h3>
                        <ul>
                            <li><strong>PayPal:</strong> Internationale Zahlung (Visa, Mastercard, PayPal-Guthaben)</li>
                            <li><strong>LemonSqueezy:</strong> Apple Pay, Google Pay, Karten</li>
                        </ul>
                        <p>Alle Zahlungen werden von Drittpartnern abgewickelt. Wir speichern keine Kartendaten.</p>

                        <h3>4.3. Bestellbestätigung</h3>
                        <p>Bestellungen werden nach erfolgreicher Zahlung per E-Mail bestätigt. Digitale Produkte werden sofort geliefert.</p>
                    </section>

                    <section id="san-pham-so">
                        <h2>5. Digitale Produkte & Lizenz</h2>

                        <h3>5.1. Lieferung</h3>
                        <ul>
                            <li>Digitale Produkte werden über einen Download-Link in Ihrem Konto geliefert</li>
                            <li>Lizenzschlüssel (falls vorhanden) werden per E-Mail gesendet</li>
                            <li>Download-Links haben eine begrenzte Anzahl von Downloads</li>
                        </ul>

                        <h3>5.2. Nutzungsrechte</h3>
                        <p>Beim Kauf eines Produkts erhalten Sie Nutzungsrechte entsprechend dem Lizenztyp:</p>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Lizenz</th>
                                    <th>Rechte</th>
                                    <th>Einschränkungen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Personal</td>
                                    <td>Nutzung in 1 persönlichen Projekt</td>
                                    <td>Nicht kommerziell, kein Weiterverkauf</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td>Nutzung in 1 kommerziellen Projekt</td>
                                    <td>Kein Weiterverkauf des Quellcodes</td>
                                </tr>
                                <tr>
                                    <td>Extended</td>
                                    <td>Nutzung in unbegrenzten Projekten</td>
                                    <td>Kein Weiterverkauf des Original-Quellcodes</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>5.3. Verboten</h3>
                        <ul>
                            <li>Weiterverkauf oder Weiterverbreitung des Quellcodes</li>
                            <li>Teilen von Lizenz-/Download-Links</li>
                            <li>Entfernen von Urheberrechtshinweisen im Code</li>
                            <li>Nutzung für illegale Zwecke</li>
                        </ul>
                    </section>

                    <section id="noi-dung">
                        <h2>6. Nutzerinhalte</h2>

                        <h3>6.1. Von Ihnen veröffentlichte Inhalte</h3>
                        <p>Wenn Sie Inhalte veröffentlichen (Artikel, Bewertungen, Kommentare), gilt:</p>
                        <ul>
                            <li>Sie behalten das Eigentum an Ihren Inhalten</li>
                            <li>Sie gewähren uns das Recht, die Inhalte anzuzeigen und zu speichern</li>
                            <li>Sie sind für die Rechtmäßigkeit der Inhalte verantwortlich</li>
                        </ul>

                        <h3>6.2. Verbotene Inhalte</h3>
                        <ul>
                            <li>Urheberrechts- oder Markenverletzung</li>
                            <li>Illegale, pornografische oder gewalttätige Inhalte</li>
                            <li>Spam oder unerlaubte Werbung</li>
                            <li>Falsche oder irreführende Informationen</li>
                            <li>Beleidigung oder Belästigung anderer</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Nutzungsverpflichtungen</h2>
                        <p>Sie verpflichten sich:</p>
                        <ul>
                            <li>Vietnamesisches Recht einzuhalten</li>
                            <li>Das System nicht zu schädigen (Hacking, DDoS, Malware)</li>
                            <li>Keine Informationen anderer Nutzer zu sammeln</li>
                            <li>Keine anderen Personen oder Organisationen zu imitieren</li>
                            <li>Den Betrieb des Dienstes nicht zu stören</li>
                        </ul>
                    </section>

                    <section id="trach-nhiem">
                        <h2>8. Haftungsbeschränkung</h2>

                        <h3>8.1. Dienst „wie besehen"</h3>
                        <p>Der Dienst wird „wie besehen" (as is) bereitgestellt. Wir garantieren nicht, dass:</p>
                        <ul>
                            <li>der Dienst ununterbrochen oder fehlerfrei ist</li>
                            <li>ein Produkt für Ihren bestimmten Zweck geeignet ist</li>
                            <li>Ergebnisse der KI-Tools zu 100% korrekt sind</li>
                        </ul>

                        <h3>8.2. Haftungsobergrenze</h3>
                        <p>In jedem Fall übersteigt unsere Haftung nicht den Betrag, den Sie in den letzten 12 Monaten gezahlt haben.</p>

                        <h3>8.3. Produkte von Verkäufern</h3>
                        <p>Wir sind eine Vermittlungsplattform. Verkäufer sind für die Qualität ihrer Produkte verantwortlich. Wir helfen bei der Streitbeilegung gemäß der <a href="{{ url('/chinh-sach-hoan-tien') }}">Rückerstattungsrichtlinie</a>.</p>
                    </section>

                    <section id="cham-dut">
                        <h2>9. Kündigung</h2>
                        <p>Sie können die Nutzung des Dienstes jederzeit beenden.</p>
                        <p>Wir können Ihr Konto kündigen oder sperren, wenn:</p>
                        <ul>
                            <li>Sie gegen diese Bedingungen verstoßen</li>
                            <li>betrügerisches oder illegales Verhalten vorliegt</li>
                            <li>eine Anordnung zuständiger Behörden vorliegt</li>
                        </ul>
                        <p>Nach der Kündigung:</p>
                        <ul>
                            <li>wird der Zugriff auf den Dienst entzogen</li>
                            <li>bleiben gekaufte Lizenzen gültig (sofern kein Verstoß vorliegt)</li>
                            <li>wird nicht ausgezahltes Guthaben gemäß Richtlinie behandelt</li>
                        </ul>
                    </section>

                    <section id="lien-he">
                        <h2>10. Kontakt & Anwendbares Recht</h2>

                        <h3>10.1. Kontakt</h3>
                        <div class="lg-legal__contact">
                            <p><strong>LamGame.vn</strong></p>
                            <p>E-Mail: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Website: <a href="{{ url('/lien-he') }}">lamgame.vn/lien-he</a></p>
                        </div>

                        <h3>10.2. Anwendbares Recht</h3>
                        <p>Diese Bedingungen unterliegen vietnamesischem Recht. Alle Streitigkeiten werden vor den zuständigen Gerichten in Vietnam beigelegt.</p>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Durch die weitere Nutzung von LamGame.vn bestätigen Sie, dass Sie diese Bedingungen gelesen haben und ihnen zustimmen.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
