<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A1.5 1.5 0 1 0 9 14.5 1.5 1.5 0 0 0 7.5 13m9 0a1.5 1.5 0 1 0 1.5 1.5 1.5 1.5 0 0 0-1.5-1.5"/>
                </svg>
                KI-Bedingungen
            </span>
            <h1>KI-Tools-Bedingungen</h1>
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
                        <li><a href="#dich-vu">2. Die KI-Tools</a></li>
                        <li><a href="#su-dung">3. Nutzungsbedingungen</a></li>
                        <li><a href="#du-lieu">4. Daten & Datenschutz</a></li>
                        <li><a href="#so-huu">5. Inhaltseigentum</a></li>
                        <li><a href="#gioi-han">6. Limits & Kontingent</a></li>
                        <li><a href="#cam-ket">7. Nutzungsverpflichtungen</a></li>
                        <li><a href="#mien-tru">8. Haftungsausschluss</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Einleitung</h2>
                        <p>LamGame AI Tools ist eine Suite von KI-Tools für Spieleentwickler. Diese Bedingungen ergänzen die allgemeinen <a href="{{ url('/dieu-khoan-su-dung') }}">Nutzungsbedingungen</a>.</p>

                        <div class="lg-legal__notice">
                            <p><strong>⚠️ Wichtig:</strong> KI kann ungenaue Inhalte erzeugen. Prüfen und verifizieren Sie die Ergebnisse immer vor der Nutzung.</p>
                        </div>
                    </section>

                    <section id="dich-vu">
                        <h2>2. Die KI-Tools</h2>

                        <h3>2.1. Verfügbare Tools</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Tool</th>
                                    <th>Beschreibung</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>GDD-Generator</td><td>Ein Game Design Document aus einer Idee erstellen</td></tr>
                                <tr><td>Code-Generator</td><td>Code für Unity, Godot, Unreal generieren</td></tr>
                                <tr><td>Code-Debug</td><td>Code-Fehler analysieren und beheben</td></tr>
                                <tr><td>Code-Review</td><td>Code-Qualität bewerten</td></tr>
                                <tr><td>Test-Generator</td><td>Unit-Tests automatisch erstellen</td></tr>
                                <tr><td>Asset-Generator</td><td>Beschreibungen für Spiel-Assets erstellen</td></tr>
                            </tbody>
                        </table>

                        <h3>2.2. Verwendete KI-Modelle</h3>
                        <p>Wir verwenden Modelle von:</p>
                        <ul>
                            <li>OpenAI (GPT-4o, GPT-4o-mini)</li>
                            <li>Google (Gemini)</li>
                            <li>Anthropic (Claude)</li>
                            <li>DeepSeek</li>
                        </ul>
                        <p>Das Modell wird automatisch anhand des Abo-Plans und der Aufgabenart ausgewählt.</p>
                    </section>

                    <section id="su-dung">
                        <h2>3. Nutzungsbedingungen</h2>

                        <h3>3.1. Voraussetzungen</h3>
                        <ul>
                            <li>Ein LamGame.vn-Konto haben</li>
                            <li>Ein Abo abschließen (Free oder kostenpflichtig)</li>
                            <li>Diese Bedingungen akzeptieren</li>
                        </ul>

                        <h3>3.2. Abo-Pläne</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Preis</th>
                                    <th>Kontingent/Monat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Free</td><td>0 $</td><td>10 Anfragen</td></tr>
                                <tr><td>Pro</td><td>3,99 $/Monat</td><td>500 Anfragen</td></tr>
                                <tr><td>Business</td><td>11,99 $/Monat</td><td>Unbegrenzt</td></tr>
                            </tbody>
                        </table>
                    </section>

                    <section id="du-lieu">
                        <h2>4. Daten & Datenschutz</h2>

                        <h3>4.1. Daten, die wir erheben</h3>
                        <ul>
                            <li><strong>Prompts:</strong> Inhalte, die Sie an die KI senden</li>
                            <li><strong>Antworten:</strong> Ergebnisse, die die KI zurückgibt</li>
                            <li><strong>Metadaten:</strong> Zeit, verwendetes Modell, Tokens</li>
                        </ul>

                        <h3>4.2. Verwendung der Daten</h3>
                        <ul>
                            <li>Den KI-Dienst bereitstellen</li>
                            <li>Kontingent und Abrechnung berechnen</li>
                            <li>Servicequalität verbessern</li>
                            <li>Missbrauch erkennen</li>
                        </ul>

                        <h3>4.3. Weitergabe an KI-Anbieter</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ Ihre Prompts werden zur Verarbeitung an KI-Anbieter (OpenAI, Google, Anthropic) gesendet. Wir kontrollieren nicht, wie diese die Daten verwenden.</p>
                        </div>
                        <ul>
                            <li>OpenAI: <a href="https://openai.com/policies/privacy-policy" target="_blank">Privacy Policy</a></li>
                            <li>Google: <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a></li>
                            <li>Anthropic: <a href="https://www.anthropic.com/privacy" target="_blank">Privacy Policy</a></li>
                        </ul>

                        <h3>4.4. Empfehlungen</h3>
                        <ul>
                            <li>Senden Sie <strong>KEINE</strong> sensiblen Informationen (Passwörter, API-Schlüssel, persönliche Daten)</li>
                            <li>Senden Sie <strong>KEINEN</strong> Code mit Secrets oder Zugangsdaten</li>
                            <li>Entfernen Sie sensible Informationen, bevor Sie Code einfügen</li>
                        </ul>
                    </section>

                    <section id="so-huu">
                        <h2>5. Inhaltseigentum</h2>

                        <h3>5.1. Eingabe (Ihre Prompts)</h3>
                        <p>Sie behalten das Eigentum an den Inhalten, die Sie an die KI senden.</p>

                        <h3>5.2. Ausgabe (KI-Ergebnisse)</h3>
                        <ul>
                            <li>Sie dürfen die Ausgabe für persönliche und kommerzielle Zwecke nutzen</li>
                            <li>Die Ausgabe kann den Ergebnissen anderer Nutzer ähneln</li>
                            <li>Wir garantieren nicht, dass die Ausgabe einzigartig oder frei von Rechtsverletzungen ist</li>
                        </ul>

                        <h3>5.3. Prüfpflicht</h3>
                        <p>Sie sind verantwortlich für:</p>
                        <ul>
                            <li>Die Prüfung der Ausgabe vor der Nutzung</li>
                            <li>Die Sicherstellung, dass keine Urheberrechte anderer verletzt werden</li>
                            <li>Das Testen von Code vor dem Deployment</li>
                        </ul>
                    </section>

                    <section id="gioi-han">
                        <h2>6. Limits & Kontingent</h2>

                        <h3>6.1. Kontingent je Plan</h3>
                        <ul>
                            <li>Jede Anfrage verbraucht 1 Kontingent</li>
                            <li>Das Kontingent wird zu Monatsbeginn zurückgesetzt</li>
                            <li>Das Kontingent wird nicht in den nächsten Monat übertragen</li>
                        </ul>

                        <h3>6.2. Ratenbegrenzung</h3>
                        <ul>
                            <li>Bis zu 10 Anfragen/Minute</li>
                            <li>Bis zu 100 Anfragen/Stunde</li>
                            <li>Über dem Limit → warten oder Plan upgraden</li>
                        </ul>

                        <h3>6.3. Token-Limits</h3>
                        <ul>
                            <li>Free: 2.000 Tokens/Anfrage</li>
                            <li>Pro: 4.000 Tokens/Anfrage</li>
                            <li>Business: 8.000 Tokens/Anfrage</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Nutzungsverpflichtungen</h2>

                        <h3>7.1. Erlaubte Nutzung</h3>
                        <ul>
                            <li>GDDs für Spielprojekte erstellen</li>
                            <li>Code für die Spieleentwicklung generieren</li>
                            <li>Code debuggen und überprüfen</li>
                            <li>Lernen und Forschen</li>
                        </ul>

                        <h3>7.2. Verbotene Nutzung</h3>
                        <ul>
                            <li>Erstellen von Malware, Viren, Schadcode</li>
                            <li>Erstellen illegaler oder hasserfüllter Inhalte</li>
                            <li>Spamming oder Missbrauch des Systems</li>
                            <li>Umgehen von Ratenbegrenzungen oder Kontingenten</li>
                            <li>Weiterverkauf oder Weiterverbreitung des Dienstes</li>
                            <li>Reverse Engineering der API</li>
                        </ul>

                        <h3>7.3. Verstöße</h3>
                        <p>Verstöße können zu Folgendem führen:</p>
                        <ul>
                            <li>Verwarnung</li>
                            <li>Temporäre Kontosperrung</li>
                            <li>Abo-Kündigung (keine Rückerstattung)</li>
                            <li>Dauerhafte Sperrung</li>
                        </ul>
                    </section>

                    <section id="mien-tru">
                        <h2>8. Haftungsausschluss</h2>

                        <h3>8.1. Genauigkeit</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ KI kann ungenaue, veraltete oder ungeeignete Inhalte erzeugen. Wir garantieren NICHT die Genauigkeit der Ausgabe.</p>
                        </div>

                        <h3>8.2. Keine Gewährleistung</h3>
                        <ul>
                            <li>Code funktioniert zu 100%</li>
                            <li>Keine Bugs oder Sicherheitsprobleme</li>
                            <li>Geeignet für einen bestimmten Zweck</li>
                            <li>Der Dienst ist ununterbrochen</li>
                        </ul>

                        <h3>8.3. Haftungsbeschränkung</h3>
                        <p>Wir haften nicht für:</p>
                        <ul>
                            <li>Schäden durch die Nutzung von KI-Ausgaben</li>
                            <li>Datenverlust oder Dienstunterbrechung</li>
                            <li>Urheberrechtsverletzungen durch KI-Ausgaben</li>
                            <li>Geschäftsentscheidungen auf Basis von KI-Ratschlägen</li>
                        </ul>

                        <h3>8.4. Entschädigung</h3>
                        <p>In jedem Fall übersteigt unsere maximale Haftung nicht den Betrag, den Sie für das Abo in den letzten 3 Monaten gezahlt haben.</p>

                        <div class="lg-legal__contact">
                            <p><strong>KI-Tools-Support</strong></p>
                            <p>E-Mail: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Betreff: [AI SUPPORT] Problem beschreiben</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Durch die Nutzung der KI-Tools bestätigen Sie, dass Sie diese Bedingungen gelesen haben und ihnen zustimmen.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
