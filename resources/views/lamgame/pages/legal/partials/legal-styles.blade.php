{{-- Legal Pages Shared Styles --}}
/* ========================================
   LEGAL PAGES — DARK THEME V2
======================================== */

:root {
    --legal-bg: #0D0D1A;
    --legal-surface: #161625;
    --legal-surface-alt: #1E1E30;
    --legal-border: #2A2A40;
    --legal-text: #F0F0F5;
    --legal-text-muted: #8B8BA3;
    --legal-accent: #8B5CF6;
    --legal-accent-hover: #7C3AED;
}

.lg-legal {
    background: var(--legal-bg);
    min-height: 100vh;
}

/* Hero */
.lg-legal__hero {
    background: linear-gradient(180deg, #1a1a2e 0%, var(--legal-bg) 100%);
    padding: 60px 0 40px;
    text-align: center;
    position: relative;
}

.lg-legal__hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 40% at 50% 0%, rgba(139, 92, 246, 0.1), transparent);
    pointer-events: none;
}

.lg-legal__badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.3);
    color: var(--legal-accent);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 16px;
}

.lg-legal__hero h1 {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 800;
    color: var(--legal-text);
    margin-bottom: 12px;
}

.lg-legal__hero p {
    color: var(--legal-text-muted);
    font-size: 0.9rem;
}

/* Content */
.lg-legal__content {
    padding: 40px 0 80px;
}

.lg-legal__grid {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 40px;
    align-items: start;
}

/* Navigation */
.lg-legal__nav {
    position: sticky;
    top: 100px;
    background: var(--legal-surface);
    border: 1px solid var(--legal-border);
    border-radius: 12px;
    padding: 20px;
}

.lg-legal__nav h3 {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--legal-text);
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--legal-border);
}

.lg-legal__nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.lg-legal__nav li {
    margin-bottom: 8px;
}

.lg-legal__nav a {
    color: var(--legal-text-muted);
    text-decoration: none;
    font-size: 0.85rem;
    display: block;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s;
}

.lg-legal__nav a:hover {
    color: var(--legal-accent);
    background: rgba(139, 92, 246, 0.1);
}

/* Article */
.lg-legal__article {
    background: var(--legal-surface);
    border: 1px solid var(--legal-border);
    border-radius: 16px;
    padding: 40px;
}

.lg-legal__article section {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid var(--legal-border);
}

.lg-legal__article section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.lg-legal__article h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--legal-text);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.lg-legal__article h2::before {
    content: '';
    width: 4px;
    height: 24px;
    background: var(--legal-accent);
    border-radius: 2px;
}

.lg-legal__article h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--legal-text);
    margin: 24px 0 12px;
}

.lg-legal__article p {
    color: var(--legal-text-muted);
    line-height: 1.8;
    margin-bottom: 16px;
}

.lg-legal__article ul,
.lg-legal__article ol {
    color: var(--legal-text-muted);
    line-height: 1.8;
    margin-bottom: 16px;
    padding-left: 24px;
}

.lg-legal__article li {
    margin-bottom: 8px;
}

.lg-legal__article strong {
    color: var(--legal-text);
}

.lg-legal__article a {
    color: var(--legal-accent);
    text-decoration: none;
}

.lg-legal__article a:hover {
    text-decoration: underline;
}

/* Notice Box */
.lg-legal__notice {
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 10px;
    padding: 16px 20px;
    margin: 20px 0;
}

.lg-legal__notice p {
    margin: 0;
    color: var(--legal-text);
}

/* Warning Box */
.lg-legal__warning {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 10px;
    padding: 16px 20px;
    margin: 20px 0;
}

.lg-legal__warning p {
    margin: 0;
    color: #F59E0B;
}

/* Contact Box */
.lg-legal__contact {
    background: var(--legal-surface-alt);
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}

.lg-legal__contact p {
    margin: 4px 0;
}

/* Footer Note */
.lg-legal__footer-note {
    background: var(--legal-surface-alt);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}

.lg-legal__footer-note p {
    margin: 0;
    font-size: 0.85rem;
}

/* Table */
.lg-legal__table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.lg-legal__table th,
.lg-legal__table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--legal-border);
}

.lg-legal__table th {
    background: var(--legal-surface-alt);
    color: var(--legal-text);
    font-weight: 600;
}

.lg-legal__table td {
    color: var(--legal-text-muted);
}

/* Responsive */
@media (max-width: 900px) {
    .lg-legal__grid {
        grid-template-columns: 1fr;
    }
    
    .lg-legal__nav {
        position: static;
        margin-bottom: 24px;
    }
    
    .lg-legal__article {
        padding: 24px;
    }
}

@media (max-width: 600px) {
    .lg-legal__hero {
        padding: 40px 0 24px;
    }
    
    .lg-legal__article h2 {
        font-size: 1.2rem;
    }
}
