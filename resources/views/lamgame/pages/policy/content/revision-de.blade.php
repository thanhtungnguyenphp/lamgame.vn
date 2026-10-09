<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Korrekturrichtlinie</h1>
                <p class="policy-intro">
                    Wir verpflichten uns, die Genauigkeit der Inhalte zu wahren. Wenn Sie einen Fehler oder
                    ungenaue Informationen finden, prüfen und korrigieren wir sie umgehend.
                </p>
            </header>

            <section class="policy-section">
                <h2>📝 Arten von Korrekturen</h2>

                <div class="correction-type">
                    <h3>Kleine Korrektur</h3>
                    <p>Tippfehler, Grammatik, Formatierung — werden sofort nach Entdeckung behoben, ohne Hinweis.</p>
                </div>

                <div class="correction-type">
                    <h3>Inhaltskorrektur</h3>
                    <p>
                        Technische Fehler, falsche Informationen, nicht funktionierender Code — werden behoben und mit
                        „Aktualisiert [Datum]: [Beschreibung der Änderung]" am Ende des Artikels vermerkt.
                    </p>
                </div>

                <div class="correction-type">
                    <h3>Wesentliche Korrektur</h3>
                    <p>
                        Erhebliche Änderungen an Standpunkt, Schlussfolgerung oder Empfehlung — werden deutlich
                        mit dem Grund der Änderung vermerkt und am Anfang des Artikels hervorgehoben.
                    </p>
                </div>
            </section>

            <section class="policy-section">
                <h2>🔧 Bearbeitungsprozess</h2>
                <ol>
                    <li>
                        <strong>Anfrage erhalten</strong>
                        <p>Sie senden eine Anfrage über das Kontaktformular oder einen Artikelkommentar.</p>
                    </li>
                    <li>
                        <strong>Verifizieren</strong>
                        <p>Das Redaktionsteam verifiziert die Informationen innerhalb von 2-3 Werktagen.</p>
                    </li>
                    <li>
                        <strong>Korrigieren</strong>
                        <p>Wird ein Fehler bestätigt, wird der Inhalt sofort behoben. Wenn nicht, erklären wir den Grund.</p>
                    </li>
                    <li>
                        <strong>Antworten</strong>
                        <p>Sie erhalten eine Bestätigungs-E-Mail, wenn die Korrektur abgeschlossen ist.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📋 So reichen Sie eine Korrekturanfrage ein</h2>
                <p>Für die schnellste Bearbeitung geben Sie bitte an:</p>
                <ul>
                    <li>Einen Link zum Artikel, der korrigiert werden muss</li>
                    <li>Den genauen Ort des fehlerhaften Inhalts (Absatz, Code-Block usw.)</li>
                    <li>Eine Beschreibung des Fehlers und einen Korrekturvorschlag</li>
                    <li>Quellenangaben (falls vorhanden)</li>
                </ul>

                <div class="cta-box">
                    <p><strong>Korrekturanfrage einreichen:</strong></p>
                    <a href="{{ route('lamgame.lien-he') }}?subject=yeu-cau-chinh-sua" class="btn btn-primary">
                        Das Redaktionsteam kontaktieren
                    </a>
                </div>
            </section>

            <section class="policy-section">
                <h2>⏱️ Bearbeitungszeit</h2>
                <table class="timeline-table">
                    <thead>
                        <tr>
                            <th>Anfragetyp</th>
                            <th>Bearbeitungszeit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tippfehler, Formatierung</td>
                            <td>1-2 Werktage</td>
                        </tr>
                        <tr>
                            <td>Code-Fehler, technische Informationen</td>
                            <td>2-5 Werktage</td>
                        </tr>
                        <tr>
                            <td>Größere Inhaltsaktualisierungen</td>
                            <td>5-10 Werktage</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="policy-section">
                <h2>🚫 Was wir NICHT ändern</h2>
                <ul>
                    <li>Gültige persönliche Meinungen des Autors</li>
                    <li>Informationen, die zum Zeitpunkt der Veröffentlichung korrekt waren</li>
                    <li>Inhalte, die keine gesetzlichen Vorschriften verletzen</li>
                    <li>Anfragen ohne Grundlage oder Quellenangaben</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📜 Korrekturverlauf</h2>
                <p>
                    Wesentliche Korrekturen werden am Ende jedes Artikels im folgenden Format festgehalten:
                </p>
                <div class="example-box">
                    <p><em>— Aktualisiert 15.08.2026: Unity-6-Beispielcode korrigiert, der mit dem neuen Input System nicht funktionierte</em></p>
                    <p><em>— Aktualisiert 10.08.2026: Anleitung für macOS ergänzt</em></p>
                </div>
            </section>

            <footer class="policy-footer">
                <p><strong>Zuletzt aktualisiert:</strong> {{ now()->format('d/m/Y') }}</p>
                <p>
                    Siehe auch: <a href="{{ route('lamgame.chinh-sach-bien-tap') }}">Redaktionsrichtlinie</a>
                </p>
            </footer>
        </article>
    </div>
</div>
