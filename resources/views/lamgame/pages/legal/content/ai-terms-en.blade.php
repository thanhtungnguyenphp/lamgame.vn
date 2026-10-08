<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A1.5 1.5 0 1 0 9 14.5 1.5 1.5 0 0 0 7.5 13m9 0a1.5 1.5 0 1 0 1.5 1.5 1.5 1.5 0 0 0-1.5-1.5"/>
                </svg>
                AI Terms
            </span>
            <h1>AI Tools Terms</h1>
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
                        <li><a href="#dich-vu">2. The AI Tools</a></li>
                        <li><a href="#su-dung">3. Usage Conditions</a></li>
                        <li><a href="#du-lieu">4. Data & Privacy</a></li>
                        <li><a href="#so-huu">5. Content Ownership</a></li>
                        <li><a href="#gioi-han">6. Limits & Quota</a></li>
                        <li><a href="#cam-ket">7. Usage Commitments</a></li>
                        <li><a href="#mien-tru">8. Disclaimer</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Introduction</h2>
                        <p>LamGame AI Tools is a suite of AI tools for game developers. These terms supplement the general <a href="{{ url('/dieu-khoan-su-dung') }}">Terms of Use</a>.</p>

                        <div class="lg-legal__notice">
                            <p><strong>⚠️ Important:</strong> AI may generate inaccurate content. Always check and verify the results before using them.</p>
                        </div>
                    </section>

                    <section id="dich-vu">
                        <h2>2. The AI Tools</h2>

                        <h3>2.1. Available tools</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Tool</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>GDD Generator</td><td>Create a Game Design Document from an idea</td></tr>
                                <tr><td>Code Generator</td><td>Generate code for Unity, Godot, Unreal</td></tr>
                                <tr><td>Code Debug</td><td>Analyze and fix code errors</td></tr>
                                <tr><td>Code Review</td><td>Assess code quality</td></tr>
                                <tr><td>Test Generator</td><td>Automatically create unit tests</td></tr>
                                <tr><td>Asset Generator</td><td>Create descriptions for game assets</td></tr>
                            </tbody>
                        </table>

                        <h3>2.2. AI models used</h3>
                        <p>We use models from:</p>
                        <ul>
                            <li>OpenAI (GPT-4o, GPT-4o-mini)</li>
                            <li>Google (Gemini)</li>
                            <li>Anthropic (Claude)</li>
                            <li>DeepSeek</li>
                        </ul>
                        <p>The model is selected automatically based on the subscription plan and task type.</p>
                    </section>

                    <section id="su-dung">
                        <h2>3. Usage Conditions</h2>

                        <h3>3.1. Requirements</h3>
                        <ul>
                            <li>Have a LamGame.vn account</li>
                            <li>Subscribe to a plan (Free or paid)</li>
                            <li>Accept these terms</li>
                        </ul>

                        <h3>3.2. Subscription plans</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Price</th>
                                    <th>Quota/month</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Free</td><td>$0</td><td>10 requests</td></tr>
                                <tr><td>Pro</td><td>$3.99/month</td><td>500 requests</td></tr>
                                <tr><td>Business</td><td>$11.99/month</td><td>Unlimited</td></tr>
                            </tbody>
                        </table>
                    </section>

                    <section id="du-lieu">
                        <h2>4. Data & Privacy</h2>

                        <h3>4.1. Data we collect</h3>
                        <ul>
                            <li><strong>Prompts:</strong> Content you send to the AI</li>
                            <li><strong>Responses:</strong> Results the AI returns</li>
                            <li><strong>Metadata:</strong> Time, model used, tokens</li>
                        </ul>

                        <h3>4.2. How we use the data</h3>
                        <ul>
                            <li>Provide the AI service</li>
                            <li>Calculate quota and billing</li>
                            <li>Improve service quality</li>
                            <li>Detect abuse</li>
                        </ul>

                        <h3>4.3. Sharing with AI providers</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ Your prompts are sent to AI providers (OpenAI, Google, Anthropic) for processing. We do not control how they use the data.</p>
                        </div>
                        <ul>
                            <li>OpenAI: <a href="https://openai.com/policies/privacy-policy" target="_blank">Privacy Policy</a></li>
                            <li>Google: <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a></li>
                            <li>Anthropic: <a href="https://www.anthropic.com/privacy" target="_blank">Privacy Policy</a></li>
                        </ul>

                        <h3>4.4. Recommendations</h3>
                        <ul>
                            <li><strong>DO NOT</strong> send sensitive information (passwords, API keys, personal data)</li>
                            <li><strong>DO NOT</strong> send code containing secrets or credentials</li>
                            <li>Remove sensitive information before pasting code</li>
                        </ul>
                    </section>

                    <section id="so-huu">
                        <h2>5. Content Ownership</h2>

                        <h3>5.1. Input (your prompts)</h3>
                        <p>You retain ownership of the content you send to the AI.</p>

                        <h3>5.2. Output (AI results)</h3>
                        <ul>
                            <li>You may use the output for personal and commercial purposes</li>
                            <li>The output may be similar to other users' results</li>
                            <li>We do not guarantee the output is unique or non-infringing</li>
                        </ul>

                        <h3>5.3. Verification responsibility</h3>
                        <p>You are responsible for:</p>
                        <ul>
                            <li>Checking the output before use</li>
                            <li>Ensuring it does not infringe others' copyrights</li>
                            <li>Testing code before deployment</li>
                        </ul>
                    </section>

                    <section id="gioi-han">
                        <h2>6. Limits & Quota</h2>

                        <h3>6.1. Quota per plan</h3>
                        <ul>
                            <li>Each request uses 1 quota</li>
                            <li>Quota resets at the start of each month</li>
                            <li>Quota does not roll over to the next month</li>
                        </ul>

                        <h3>6.2. Rate limiting</h3>
                        <ul>
                            <li>Up to 10 requests/minute</li>
                            <li>Up to 100 requests/hour</li>
                            <li>Over the limit → wait or upgrade your plan</li>
                        </ul>

                        <h3>6.3. Token limits</h3>
                        <ul>
                            <li>Free: 2,000 tokens/request</li>
                            <li>Pro: 4,000 tokens/request</li>
                            <li>Business: 8,000 tokens/request</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Usage Commitments</h2>

                        <h3>7.1. Permitted use</h3>
                        <ul>
                            <li>Create GDDs for game projects</li>
                            <li>Generate code for game development</li>
                            <li>Debug and review code</li>
                            <li>Learning and research</li>
                        </ul>

                        <h3>7.2. Prohibited use</h3>
                        <ul>
                            <li>Creating malware, viruses, malicious code</li>
                            <li>Creating illegal or hateful content</li>
                            <li>Spamming or abusing the system</li>
                            <li>Bypassing rate limits or quota</li>
                            <li>Reselling or redistributing the service</li>
                            <li>Reverse engineering the API</li>
                        </ul>

                        <h3>7.3. Violations</h3>
                        <p>Violations may result in:</p>
                        <ul>
                            <li>Warning</li>
                            <li>Temporary account suspension</li>
                            <li>Subscription cancellation (no refund)</li>
                            <li>Permanent ban</li>
                        </ul>
                    </section>

                    <section id="mien-tru">
                        <h2>8. Disclaimer</h2>

                        <h3>8.1. Accuracy</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ AI may generate inaccurate, outdated or unsuitable content. We do NOT guarantee the accuracy of the output.</p>
                        </div>

                        <h3>8.2. No warranty</h3>
                        <ul>
                            <li>Code works 100%</li>
                            <li>No bugs or security issues</li>
                            <li>Fit for a specific purpose</li>
                            <li>The service is uninterrupted</li>
                        </ul>

                        <h3>8.3. Limitation of liability</h3>
                        <p>We are not responsible for:</p>
                        <ul>
                            <li>Damages from using AI output</li>
                            <li>Data loss or service interruption</li>
                            <li>Copyright infringement caused by AI output</li>
                            <li>Business decisions based on AI advice</li>
                        </ul>

                        <h3>8.4. Compensation</h3>
                        <p>In any case, our maximum liability shall not exceed the amount you paid for the subscription in the last 3 months.</p>

                        <div class="lg-legal__contact">
                            <p><strong>AI Tools Support</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Subject: [AI SUPPORT] Describe the issue</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>By using the AI Tools, you confirm that you have read and agree to these terms.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
