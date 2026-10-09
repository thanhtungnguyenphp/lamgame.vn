<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Revision Policy</h1>
                <p class="policy-intro">
                    We are committed to maintaining content accuracy. If you find an error or
                    inaccurate information, we will review and correct it promptly.
                </p>
            </header>

            <section class="policy-section">
                <h2>📝 Types of revision</h2>

                <div class="correction-type">
                    <h3>Minor revision</h3>
                    <p>Typos, grammar, formatting — fixed as soon as they are found, with no note needed.</p>
                </div>

                <div class="correction-type">
                    <h3>Content revision</h3>
                    <p>
                        Technical errors, wrong information, non-working code — fixed and noted as
                        "Updated [date]: [change description]" at the end of the article.
                    </p>
                </div>

                <div class="correction-type">
                    <h3>Major revision</h3>
                    <p>
                        Significant changes to viewpoint, conclusion or recommendation — clearly noted
                        with the reason for the change and highlighted at the top of the article.
                    </p>
                </div>
            </section>

            <section class="policy-section">
                <h2>🔧 Handling process</h2>
                <ol>
                    <li>
                        <strong>Receive the request</strong>
                        <p>You submit a request via the contact form or an article comment.</p>
                    </li>
                    <li>
                        <strong>Verify</strong>
                        <p>The editorial team verifies the information within 2-3 business days.</p>
                    </li>
                    <li>
                        <strong>Correct</strong>
                        <p>If an error is confirmed, the content is fixed immediately. If not, we will explain why.</p>
                    </li>
                    <li>
                        <strong>Respond</strong>
                        <p>You will receive a confirmation email when the correction is complete.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📋 How to submit a correction request</h2>
                <p>For the fastest handling, please provide:</p>
                <ul>
                    <li>A link to the article that needs correcting</li>
                    <li>The specific location of the incorrect content (paragraph, code block, etc.)</li>
                    <li>A description of the error and a suggested fix</li>
                    <li>Reference sources (if any)</li>
                </ul>

                <div class="cta-box">
                    <p><strong>Submit a correction request:</strong></p>
                    <a href="{{ route('lamgame.lien-he') }}?subject=yeu-cau-chinh-sua" class="btn btn-primary">
                        Contact the editorial team
                    </a>
                </div>
            </section>

            <section class="policy-section">
                <h2>⏱️ Processing time</h2>
                <table class="timeline-table">
                    <thead>
                        <tr>
                            <th>Request type</th>
                            <th>Processing time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Typos, formatting</td>
                            <td>1-2 business days</td>
                        </tr>
                        <tr>
                            <td>Code errors, technical information</td>
                            <td>2-5 business days</td>
                        </tr>
                        <tr>
                            <td>Major content update requests</td>
                            <td>5-10 business days</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="policy-section">
                <h2>🚫 What we do NOT change</h2>
                <ul>
                    <li>Valid personal opinions of the author</li>
                    <li>Information that was accurate at the time of publication</li>
                    <li>Content that does not violate legal regulations</li>
                    <li>Requests without basis or reference sources</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📜 Revision history</h2>
                <p>
                    Major revisions are recorded at the end of each article in the format:
                </p>
                <div class="example-box">
                    <p><em>— Updated 15/08/2026: Fixed Unity 6 sample code not working with the new Input System</em></p>
                    <p><em>— Updated 10/08/2026: Added instructions for macOS</em></p>
                </div>
            </section>

            <footer class="policy-footer">
                <p><strong>Last updated:</strong> {{ now()->format('d/m/Y') }}</p>
                <p>
                    See also: <a href="{{ route('lamgame.chinh-sach-bien-tap') }}">Editorial Policy</a>
                </p>
            </footer>
        </article>
    </div>
</div>
