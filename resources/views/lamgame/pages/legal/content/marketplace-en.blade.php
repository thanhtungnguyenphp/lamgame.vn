<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Marketplace Terms
            </span>
            <h1>Marketplace Terms</h1>
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
                        <li><a href="#nguoi-mua">2. Buyer Terms</a></li>
                        <li><a href="#nguoi-ban">3. Seller Terms</a></li>
                        <li><a href="#san-pham">4. Product Requirements</a></li>
                        <li><a href="#gia-phi">5. Pricing & Fees</a></li>
                        <li><a href="#thanh-toan">6. Seller Payouts</a></li>
                        <li><a href="#ban-quyen">7. Copyright & License</a></li>
                        <li><a href="#tranh-chap">8. Dispute Resolution</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Introduction</h2>
                        <p>LamGame Marketplace is a platform connecting buyers and sellers of game source code, assets and templates. These terms supplement the general <a href="{{ url('/dieu-khoan-su-dung') }}">Terms of Use</a>.</p>

                        <h3>Parties involved:</h3>
                        <ul>
                            <li><strong>LamGame:</strong> Intermediary platform providing the infrastructure</li>
                            <li><strong>Seller:</strong> Creates and sells products</li>
                            <li><strong>Buyer:</strong> Purchases and uses products</li>
                        </ul>
                    </section>

                    <section id="nguoi-mua">
                        <h2>2. Buyer Terms</h2>

                        <h3>2.1. Rights</h3>
                        <ul>
                            <li>Receive the product as described</li>
                            <li>Seller support during the first 30 days</li>
                            <li>Refunds per the <a href="{{ url('/chinh-sach-hoan-tien') }}">Refund Policy</a></li>
                            <li>Free updates (depending on seller policy)</li>
                            <li>Rate and review products</li>
                        </ul>

                        <h3>2.2. Responsibilities</h3>
                        <ul>
                            <li>Read the description and demo carefully before buying</li>
                            <li>Check compatibility with your environment</li>
                            <li>Comply with the product license</li>
                            <li>Do not share/resell the source code</li>
                            <li>Review honestly, no spam</li>
                        </ul>

                        <h3>2.3. Product support</h3>
                        <ul>
                            <li>Seller supports: Installation, bugs in the source, usage guidance</li>
                            <li>Seller does NOT support: Custom modifications on request, teaching coding from scratch</li>
                            <li>Response time: Depends on the seller, usually 24-72h</li>
                        </ul>
                    </section>

                    <section id="nguoi-ban">
                        <h2>3. Seller Terms</h2>

                        <h3>3.1. Seller registration</h3>
                        <ul>
                            <li>Register a seller account at <a href="{{ url('/seller/register') }}">lamgame.vn/seller/register</a></li>
                            <li>Provide accurate information: Name, email, bank details</li>
                            <li>Approval: 1-3 business days</li>
                        </ul>

                        <h3>3.2. Seller rights</h3>
                        <ul>
                            <li>List products on the marketplace</li>
                            <li>Set your own prices</li>
                            <li>Receive 70% of revenue (LamGame keeps 30%)</li>
                            <li>Access sales analytics and statistics</li>
                            <li>Marketing support from LamGame</li>
                        </ul>

                        <h3>3.3. Seller responsibilities</h3>
                        <ul>
                            <li>Ensure the product works as described</li>
                            <li>Support buyers for 30 days</li>
                            <li>Update the product for serious bugs</li>
                            <li>Respond to support requests within 72h</li>
                            <li>Comply with copyright law</li>
                        </ul>

                        <h3>3.4. Prohibited behavior</h3>
                        <ul>
                            <li>Selling copyright-infringing products</li>
                            <li>Selling malware, backdoors, malicious code</li>
                            <li>Misleading product descriptions</li>
                            <li>Fake reviews, manipulating ratings</li>
                            <li>Contacting buyers to transact outside the platform</li>
                        </ul>
                    </section>

                    <section id="san-pham">
                        <h2>4. Product Requirements</h2>

                        <h3>4.1. Minimum quality</h3>
                        <ul>
                            <li>Code runs with the stated engine version</li>
                            <li>All files needed to build/compile are included</li>
                            <li>Basic documentation or README</li>
                            <li>Real screenshots/demo video</li>
                        </ul>

                        <h3>4.2. Prohibited products</h3>
                        <ul>
                            <li>Copyright infringement (stolen assets, code)</li>
                            <li>Adult content, excessive violence</li>
                            <li>Containing malware or backdoors</li>
                            <li>Unlicensed gambling</li>
                            <li>Clones of copyrighted games</li>
                        </ul>

                        <h3>4.3. Review process</h3>
                        <ol>
                            <li>Seller submits the product</li>
                            <li>Admin review (1-5 days)</li>
                            <li>Approved → Live on the marketplace</li>
                            <li>Rejected → Receive feedback to fix</li>
                        </ol>
                    </section>

                    <section id="gia-phi">
                        <h2>5. Pricing & Fees</h2>

                        <h3>5.1. Product pricing</h3>
                        <ul>
                            <li>Sellers set their own price, from $2 and up</li>
                            <li>Can create multiple license tiers (Personal, Commercial, Extended)</li>
                            <li>Can run promotions and discounts</li>
                        </ul>

                        <h3>5.2. Marketplace fees</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Commission fee</td><td>30% per transaction</td></tr>
                                <tr><td>Payment fee</td><td>Included in the 30%</td></tr>
                                <tr><td>Withdrawal fee</td><td>Free (VN bank transfer)</td></tr>
                            </tbody>
                        </table>
                        <p><strong>Seller receives:</strong> 70% of the sale price × quantity sold</p>
                    </section>

                    <section id="thanh-toan">
                        <h2>6. Seller Payouts</h2>

                        <h3>6.1. Withdrawal conditions</h3>
                        <ul>
                            <li>Minimum balance: $5</li>
                            <li>Account with verified bank details</li>
                            <li>No pending disputes/refunds</li>
                        </ul>

                        <h3>6.2. Payout schedule</h3>
                        <ul>
                            <li>Withdrawal request: Anytime</li>
                            <li>Processing: 3-5 business days</li>
                            <li>Hold period: 14 days after transaction (to handle refunds if any)</li>
                        </ul>

                        <h3>6.3. Payout methods</h3>
                        <ul>
                            <li>Vietnamese bank transfer</li>
                            <li>Requirement: Account holder name matches the registered name</li>
                        </ul>
                    </section>

                    <section id="ban-quyen">
                        <h2>7. Copyright & License</h2>

                        <h3>7.1. Ownership</h3>
                        <ul>
                            <li>Sellers retain intellectual property of their products</li>
                            <li>Buyers receive usage rights per the purchased license</li>
                            <li>LamGame does not own sellers' products</li>
                        </ul>

                        <h3>7.2. License types</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>License</th>
                                    <th>Usage</th>
                                    <th>Resale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Personal</td><td>1 project, non-commercial</td><td>❌</td></tr>
                                <tr><td>Commercial</td><td>1 commercial project</td><td>❌</td></tr>
                                <tr><td>Extended</td><td>Unlimited projects</td><td>❌ (no source resale)</td></tr>
                            </tbody>
                        </table>

                        <h3>7.3. License violations</h3>
                        <p>If a violation is detected:</p>
                        <ul>
                            <li>First warning</li>
                            <li>Account ban on repeat offense</li>
                            <li>Sellers may claim compensation</li>
                        </ul>
                    </section>

                    <section id="tranh-chap">
                        <h2>8. Dispute Resolution</h2>

                        <h3>8.1. Process</h3>
                        <ol>
                            <li><strong>Direct discussion:</strong> Buyer and seller negotiate</li>
                            <li><strong>LamGame support:</strong> If unresolved, contact support</li>
                            <li><strong>Final decision:</strong> LamGame decides based on evidence</li>
                        </ol>

                        <h3>8.2. Common cases</h3>
                        <ul>
                            <li><strong>Product does not work:</strong> Seller fixes it or refunds</li>
                            <li><strong>Lack of support:</strong> Warn the seller, extend support for the buyer</li>
                            <li><strong>Copyright infringement:</strong> Remove the product, refund the buyer</li>
                        </ul>

                        <div class="lg-legal__contact">
                            <p><strong>Dispute Resolution Contact</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Subject: [DISPUTE] Order ID - Short description</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>These terms may be updated. Significant changes will be communicated to sellers by email.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
