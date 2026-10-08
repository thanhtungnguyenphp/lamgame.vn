<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Privacy Policy
            </span>
            <h1>Privacy Policy</h1>
            <p>Last updated: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Table of Contents</h3>
                    <ul>
                        <li><a href="#gioi-thieu">1. Introduction</a></li>
                        <li><a href="#thu-thap">2. Information We Collect</a></li>
                        <li><a href="#su-dung">3. How We Use Information</a></li>
                        <li><a href="#chia-se">4. Information Sharing</a></li>
                        <li><a href="#bao-mat">5. Data Security</a></li>
                        <li><a href="#cookie">6. Cookies</a></li>
                        <li><a href="#quyen-loi">7. Your Rights</a></li>
                        <li><a href="#lien-he">8. Contact</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Introduction</h2>
                        <p>Welcome to LamGame.vn ("we", "us", "our"). We are committed to protecting your privacy and personal information.</p>
                        <p>This policy explains how we collect, use and protect information when you use the LamGame.vn website and related services, including:</p>
                        <ul>
                            <li>The game source code marketplace</li>
                            <li>AI tools for game developers</li>
                            <li>Blog and learning materials</li>
                            <li>The community forum</li>
                        </ul>
                    </section>

                    <section id="thu-thap">
                        <h2>2. Information We Collect</h2>

                        <h3>2.1. Information you provide directly</h3>
                        <ul>
                            <li><strong>Account information:</strong> Full name, email, phone number at registration</li>
                            <li><strong>Payment information:</strong> Processed via LemonSqueezy/PayPal — we do not store card details</li>
                            <li><strong>Seller information:</strong> Shop name, bank details (for payouts)</li>
                            <li><strong>User content:</strong> Forum posts, product reviews, contact messages</li>
                        </ul>

                        <h3>2.2. Information collected automatically</h3>
                        <ul>
                            <li><strong>Device data:</strong> Browser type, operating system, IP address</li>
                            <li><strong>Usage data:</strong> Pages viewed, visit time, referral source</li>
                            <li><strong>AI data:</strong> Prompts and responses when using AI tools (to improve the service)</li>
                        </ul>
                    </section>

                    <section id="su-dung">
                        <h2>3. How We Use Information</h2>
                        <p>We use your information to:</p>
                        <ul>
                            <li>Provide and maintain the service</li>
                            <li>Process transactions and send order confirmations</li>
                            <li>Deliver digital products (source code, license keys)</li>
                            <li>Provide customer support and respond to requests</li>
                            <li>Send order notifications and product updates</li>
                            <li>Analyze and improve the service</li>
                            <li>Prevent fraud and protect security</li>
                            <li>Comply with legal obligations</li>
                        </ul>
                    </section>

                    <section id="chia-se">
                        <h2>4. Information Sharing</h2>
                        <p>We <strong>do not sell</strong> your personal information. We only share it in the following cases:</p>

                        <h3>4.1. Service partners</h3>
                        <ul>
                            <li><strong>LemonSqueezy/PayPal:</strong> Payment processing</li>
                            <li><strong>SMTP2GO:</strong> Email delivery</li>
                            <li><strong>OpenAI/Google/Anthropic:</strong> AI services (only prompt content is sent)</li>
                            <li><strong>Cloudflare:</strong> CDN and security</li>
                        </ul>

                        <h3>4.2. Sellers</h3>
                        <p>When you buy a product, the seller receives your name and email to provide product support.</p>

                        <h3>4.3. Legal requests</h3>
                        <p>When required by competent authorities under Vietnamese law.</p>
                    </section>

                    <section id="bao-mat">
                        <h2>5. Data Security</h2>
                        <p>We apply the following security measures:</p>
                        <ul>
                            <li>SSL/TLS encryption for all connections</li>
                            <li>Password hashing with bcrypt</li>
                            <li>Download files stored in private storage</li>
                            <li>24/7 monitoring and intrusion detection</li>
                            <li>Regular data backups</li>
                        </ul>
                        <div class="lg-legal__notice">
                            <p><strong>Note:</strong> No method of transmission over the Internet is 100% secure. We strive to protect your data but cannot guarantee absolute security.</p>
                        </div>
                    </section>

                    <section id="cookie">
                        <h2>6. Cookies and Tracking Technologies</h2>
                        <p>LamGame categorizes cookies and similar technologies by purpose:</p>
                        <ul>
                            <li><strong>Essential:</strong> Maintain login session, security and cart; always enabled.</li>
                            <li><strong>Analytics:</strong> Google Analytics is loaded only after your consent so we can measure performance and usage behavior.</li>
                            <li><strong>Advertising:</strong> When ads are enabled, ad storage, measurement and personalization cookies are only turned on after your consent.</li>
                            <li><strong>Functional:</strong> Remember preferences needed for the experience you request.</li>
                        </ul>
                        <p>You can accept, reject or change Analytics and Advertising preferences separately at any time. Rejecting non-essential cookies does not affect login, cart or transactions.</p>
                        <button type="button" class="lg-legal__button" onclick="window.openPrivacyPreferences?.()">Manage cookie preferences</button>
                    </section>

                    <section id="quyen-loi">
                        <h2>7. Your Rights</h2>
                        <p>Under Vietnamese law and international practice, you have the right to:</p>
                        <ul>
                            <li><strong>Access:</strong> Request a copy of your personal data</li>
                            <li><strong>Rectification:</strong> Update inaccurate information</li>
                            <li><strong>Erasure:</strong> Request deletion of your account and data (except data retained by law)</li>
                            <li><strong>Opt-out:</strong> Decline marketing emails</li>
                            <li><strong>Data portability:</strong> Receive your data in a readable format</li>
                        </ul>
                        <p>To exercise these rights, please contact: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                    </section>

                    <section id="lien-he">
                        <h2>8. Contact</h2>
                        <p>If you have questions about this policy, please contact:</p>
                        <div class="lg-legal__contact">
                            <p><strong>LamGame.vn</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Website: <a href="{{ url('/lien-he') }}">lamgame.vn/lien-he</a></p>
                        </div>
                        <p>We will respond within 7 business days.</p>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>This policy may be updated periodically. Significant changes will be announced via email or on the website.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
