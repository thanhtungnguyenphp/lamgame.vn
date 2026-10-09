<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Editorial Policy</h1>
                <p class="policy-intro">
                    At LamGame.vn, we are committed to providing accurate, useful and reliable content
                    for the game developer community.
                </p>
            </header>

            <section class="policy-section">
                <h2>🎯 Content mission</h2>
                <p>
                    Our goal is to create high-quality game development resources that help developers
                    access the latest knowledge in the global game industry.
                </p>
            </section>

            <section class="policy-section">
                <h2>✍️ Our authors</h2>
                <p>Content on LamGame.vn is written by:</p>
                <ul>
                    <li><strong>The LamGame editorial team</strong> — Game development experts with many years of experience</li>
                    <li><strong>Guest authors</strong> — Developers and experts from game studios at home and abroad</li>
                    <li><strong>Contributors</strong> — Community members with verified expertise</li>
                </ul>
                <p>
                    Each author has their own profile page with information about their experience and expertise.
                    <a href="{{ route('authors.index') }}">View the author list →</a>
                </p>
            </section>

            <section class="policy-section">
                <h2>📋 Editorial process</h2>
                <p>Every article on LamGame.vn goes through a rigorous process:</p>
                <ol>
                    <li>
                        <strong>Research & Writing</strong>
                        <p>The author researches thoroughly and writes content based on real experience and official documentation.</p>
                    </li>
                    <li>
                        <strong>Technical check</strong>
                        <p>Code samples are tested on the engine/framework versions mentioned. Tutorials are verified to work correctly.</p>
                    </li>
                    <li>
                        <strong>Editorial review</strong>
                        <p>Editors check the accuracy, clarity and consistency of the content.</p>
                    </li>
                    <li>
                        <strong>Publishing & Updating</strong>
                        <p>Articles are published with clear dates. Content is updated when the engine/framework changes.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📚 References</h2>
                <p>We prioritize reliable sources:</p>
                <ul>
                    <li>Official documentation from Unity, Unreal Engine, Godot, etc.</li>
                    <li>Research and articles from GDC, Gamasutra, Game Developer Magazine</li>
                    <li>Real experience from shipped game projects</li>
                    <li>Interviews and insights from reputable developers</li>
                </ul>
                <p>References are clearly cited in each article when necessary.</p>
            </section>

            <section class="policy-section">
                <h2>🔄 Content updates</h2>
                <p>
                    Game development is a fast-moving field. We regularly review and update
                    articles to ensure accuracy:
                </p>
                <ul>
                    <li>Tutorial articles are reviewed when an engine/framework releases a new version</li>
                    <li>API information is updated according to official documentation</li>
                    <li>The "Last updated" date is shown on each article</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>⚠️ Disclaimer</h2>
                <ul>
                    <li>Code samples are illustrative only and need adjustment for production</li>
                    <li>Performance and results may vary depending on the environment</li>
                    <li>We are not responsible for damages arising from applying the content</li>
                    <li>Pricing and licensing information may change according to the provider</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📧 Feedback & Contributions</h2>
                <p>
                    We welcome all feedback to improve content quality:
                </p>
                <ul>
                    <li>Found a technical error? <a href="{{ route('lamgame.lien-he') }}">Report it to us</a></li>
                    <li>Want to contribute an article? <a href="{{ route('lamgame.lien-he') }}">Contact the editorial team</a></li>
                    <li>Request a content correction? See the <a href="{{ route('lamgame.chinh-sach-chinh-sua') }}">Revision Policy</a></li>
                </ul>
            </section>

            <footer class="policy-footer">
                <p><strong>Last updated:</strong> {{ now()->format('d/m/Y') }}</p>
            </footer>
        </article>
    </div>
</div>
