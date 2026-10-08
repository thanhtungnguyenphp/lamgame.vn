<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Terms of Service
            </span>
            <h1>Terms of Use</h1>
            <p>Last updated: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Table of Contents</h3>
                    <ul>
                        <li><a href="#chap-nhan">1. Acceptance of Terms</a></li>
                        <li><a href="#dich-vu">2. Service Description</a></li>
                        <li><a href="#tai-khoan">3. Account</a></li>
                        <li><a href="#mua-hang">4. Purchases & Payment</a></li>
                        <li><a href="#san-pham-so">5. Digital Products</a></li>
                        <li><a href="#noi-dung">6. User Content</a></li>
                        <li><a href="#cam-ket">7. Usage Commitments</a></li>
                        <li><a href="#trach-nhiem">8. Limitation of Liability</a></li>
                        <li><a href="#cham-dut">9. Termination</a></li>
                        <li><a href="#lien-he">10. Contact</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="chap-nhan">
                        <h2>1. Acceptance of Terms</h2>
                        <p>By accessing and using the LamGame.vn website (the "Service"), you agree to comply with these terms. If you do not agree, please do not use the Service.</p>
                        <p>Additional terms may apply to specific services:</p>
                        <ul>
                            <li><a href="{{ url('/dieu-khoan-marketplace') }}">Marketplace Terms</a> — For buyers/sellers of source code</li>
                            <li><a href="{{ url('/dieu-khoan-ai') }}">AI Terms</a> — For the AI tools</li>
                            <li><a href="{{ url('/chinh-sach-hoan-tien') }}">Refund Policy</a></li>
                        </ul>
                    </section>

                    <section id="dich-vu">
                        <h2>2. Service Description</h2>
                        <p>LamGame.vn provides:</p>
                        <ul>
                            <li><strong>Marketplace:</strong> A platform to buy and sell game source code, assets and templates</li>
                            <li><strong>AI Tools:</strong> AI tools for game development (GDD Generator, Code Assistant, Asset Generator)</li>
                            <li><strong>Learning:</strong> Blog, tutorials and courses on game development</li>
                            <li><strong>Community:</strong> A forum to exchange knowledge</li>
                        </ul>
                        <p>We reserve the right to change, suspend or discontinue any part of the Service with reasonable notice.</p>
                    </section>

                    <section id="tai-khoan">
                        <h2>3. User Accounts</h2>

                        <h3>3.1. Registration</h3>
                        <ul>
                            <li>You must be 18 or older, or have parental consent</li>
                            <li>Provide accurate and up-to-date information</li>
                            <li>Each person may use only one account</li>
                        </ul>

                        <h3>3.2. Account security</h3>
                        <ul>
                            <li>You are responsible for keeping your password secure</li>
                            <li>Notify us immediately if you detect unauthorized access</li>
                            <li>Do not share your account with others</li>
                        </ul>

                        <h3>3.3. Account suspension</h3>
                        <p>We reserve the right to suspend or delete your account if you violate the terms, including:</p>
                        <ul>
                            <li>Providing false information</li>
                            <li>Fraud or scams</li>
                            <li>Copyright infringement</li>
                            <li>Spamming or harassing other users</li>
                        </ul>
                    </section>

                    <section id="mua-hang">
                        <h2>4. Purchases & Payment</h2>

                        <h3>4.1. Pricing</h3>
                        <ul>
                            <li>Prices are shown in USD, including VAT (if applicable)</li>
                            <li>Prices may change without prior notice</li>
                            <li>The applicable price is the one at the time of order</li>
                        </ul>

                        <h3>4.2. Payment methods</h3>
                        <ul>
                            <li><strong>PayPal:</strong> International payment (Visa, Mastercard, PayPal balance)</li>
                            <li><strong>LemonSqueezy:</strong> Apple Pay, Google Pay, Cards</li>
                        </ul>
                        <p>All payments are processed by third-party partners. We do not store card details.</p>

                        <h3>4.3. Order confirmation</h3>
                        <p>Orders are confirmed by email after successful payment. Digital products are delivered immediately.</p>
                    </section>

                    <section id="san-pham-so">
                        <h2>5. Digital Products & License</h2>

                        <h3>5.1. Delivery</h3>
                        <ul>
                            <li>Digital products are delivered via a download link in your account</li>
                            <li>License keys (if any) are sent by email</li>
                            <li>Download links have a limited number of downloads</li>
                        </ul>

                        <h3>5.2. Usage rights</h3>
                        <p>When you buy a product, you are granted usage rights according to the license type:</p>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>License</th>
                                    <th>Rights</th>
                                    <th>Limitations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Personal</td>
                                    <td>Use in 1 personal project</td>
                                    <td>Non-commercial, no resale</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td>Use in 1 commercial project</td>
                                    <td>No reselling the source</td>
                                </tr>
                                <tr>
                                    <td>Extended</td>
                                    <td>Use in unlimited projects</td>
                                    <td>No reselling the original source</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>5.3. Prohibited</h3>
                        <ul>
                            <li>Reselling or redistributing the source code</li>
                            <li>Sharing license/download links</li>
                            <li>Removing copyright information in the code</li>
                            <li>Using it for illegal purposes</li>
                        </ul>
                    </section>

                    <section id="noi-dung">
                        <h2>6. User Content</h2>

                        <h3>6.1. Content you post</h3>
                        <p>When you post content (articles, reviews, comments), you:</p>
                        <ul>
                            <li>Retain ownership of your content</li>
                            <li>Grant us the right to display and store the content</li>
                            <li>Are responsible for the legality of the content</li>
                        </ul>

                        <h3>6.2. Prohibited content</h3>
                        <ul>
                            <li>Copyright or trademark infringement</li>
                            <li>Illegal, pornographic or violent content</li>
                            <li>Spam or unauthorized advertising</li>
                            <li>False or misleading information</li>
                            <li>Insulting or harassing others</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Usage Commitments</h2>
                        <p>You commit to:</p>
                        <ul>
                            <li>Comply with Vietnamese law</li>
                            <li>Not harm the system (hacking, DDoS, malware)</li>
                            <li>Not collect other users' information</li>
                            <li>Not impersonate other people or organizations</li>
                            <li>Not interfere with the operation of the Service</li>
                        </ul>
                    </section>

                    <section id="trach-nhiem">
                        <h2>8. Limitation of Liability</h2>

                        <h3>8.1. Service "as is"</h3>
                        <p>The Service is provided "as is". We do not guarantee that:</p>
                        <ul>
                            <li>The Service will be uninterrupted or error-free</li>
                            <li>A product fits your specific purpose</li>
                            <li>AI Tools results are 100% accurate</li>
                        </ul>

                        <h3>8.2. Liability cap</h3>
                        <p>In any case, our liability shall not exceed the amount you paid in the last 12 months.</p>

                        <h3>8.3. Products from Sellers</h3>
                        <p>We are an intermediary platform. Sellers are responsible for the quality of their products. We help resolve disputes per the <a href="{{ url('/chinh-sach-hoan-tien') }}">Refund Policy</a>.</p>
                    </section>

                    <section id="cham-dut">
                        <h2>9. Termination</h2>
                        <p>You may stop using the Service at any time.</p>
                        <p>We may terminate or suspend your account if:</p>
                        <ul>
                            <li>You violate these terms</li>
                            <li>Fraudulent or illegal behavior</li>
                            <li>Request from competent authorities</li>
                        </ul>
                        <p>After termination:</p>
                        <ul>
                            <li>Access to the Service is revoked</li>
                            <li>Purchased licenses remain valid (if not in violation)</li>
                            <li>Unwithdrawn balance is handled per policy</li>
                        </ul>
                    </section>

                    <section id="lien-he">
                        <h2>10. Contact & Governing Law</h2>

                        <h3>10.1. Contact</h3>
                        <div class="lg-legal__contact">
                            <p><strong>LamGame.vn</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Website: <a href="{{ url('/lien-he') }}">lamgame.vn/lien-he</a></p>
                        </div>

                        <h3>10.2. Governing law</h3>
                        <p>These terms are governed by Vietnamese law. Any disputes will be resolved at the competent courts in Vietnam.</p>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>By continuing to use LamGame.vn, you confirm that you have read and agree to these terms.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
