<style>
    .solutions-hero {
        padding: 6rem 2rem 4rem;
        background: linear-gradient(180deg, #111111 0%, #0a0a0a 100%);
        border-bottom: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }
    .solutions-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,255,255,0.02) 0%, transparent 70%);
        border-radius: 50%;
    }
    .solutions-hero-inner {
        max-width: 1000px;
        margin: 0 auto;
        text-align: center;
    }
    .solutions-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .solutions-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin: 0 0 1.5rem;
        letter-spacing: -1px;
    }
    .solutions-hero p {
        font-size: 1.2rem;
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .solutions-content {
        max-width: 1000px;
        margin: 4rem auto;
        padding: 0 2rem;
    }
    .solutions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        margin-bottom: 5rem;
    }
    .solutions-grid.reverse {
        direction: rtl;
    }
    .solutions-grid.reverse > * {
        direction: ltr;
    }
    .solutions-image-container {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        min-height: 320px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .solutions-image-container i {
        font-size: 6rem;
        color: var(--text-primary);
        opacity: 0.9;
    }
    .solutions-image-glow {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        filter: blur(40px);
        border-radius: 50%;
        z-index: 0;
    }
    .solutions-text h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }
    .solutions-text p {
        color: var(--text-secondary);
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    .process-section {
        background: var(--bg-secondary);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        padding: 5rem 2rem;
    }
    .process-inner {
        max-width: 1000px;
        margin: 0 auto;
    }
    .process-header {
        text-align: center;
        margin-bottom: 4rem;
    }
    .process-header h2 {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    .process-header p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    .process-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        position: relative;
    }
    .process-step-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        position: relative;
        transition: transform 0.2s, border-color 0.2s;
    }
    .process-step-card:hover {
        transform: translateY(-5px);
        border-color: var(--text-primary);
    }
    .process-step-num {
        position: absolute;
        top: -1.5rem;
        left: 1.5rem;
        width: 3rem;
        height: 3rem;
        background: var(--text-primary);
        color: var(--bg-primary);
        font-weight: 800;
        font-size: 1.2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .process-step-card h3 {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 1rem 0 0.75rem;
    }
    .process-step-card p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }
    .cta-section {
        padding: 6rem 2rem;
        text-align: center;
        background: linear-gradient(180deg, #0a0a0a 0%, #111111 100%);
    }
    .cta-card {
        max-width: 800px;
        margin: 0 auto;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 3.5rem 2rem;
    }
    .cta-card h2 {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    .cta-card p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    @media (max-width: 768px) {
        .solutions-grid { grid-template-columns: 1fr; gap: 2rem; margin-bottom: 3rem; }
        .solutions-grid.reverse { direction: ltr; }
        .process-steps { grid-template-columns: 1fr; gap: 2.5rem; }
        .solutions-hero h1 { font-size: 2.25rem; }
        .solutions-hero p { font-size: 1.05rem; }
        .process-step-card { padding-top: 2.5rem; }
    }
</style>
