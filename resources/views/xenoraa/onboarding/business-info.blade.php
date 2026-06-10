<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tell Us About Your Business — Xenoraa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --black: #0a0a0a; --card: #141414; --border: #222; --border2: #2a2a2a;
            --purple: #7c3aed; --purple-light: #a855f7; --cyan: #06b6d4;
            --text: #f5f5f5; --muted: #888; --subtle: #3f3f46;
        }
        body {
            font-family: 'Inter', sans-serif; background: var(--black); color: var(--text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .wrap { width: 100%; max-width: 760px; }

        /* Progress */
        .progress-bar { display: flex; align-items: center; gap: 0; margin-bottom: 2.5rem; }
        .step { display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 600; }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
        }
        .step.done .step-num { background: #22c55e; color: #fff; }
        .step.active .step-num { background: var(--purple); color: #fff; }
        .step.pending .step-num { background: var(--border2); color: var(--muted); }
        .step.done .step-label { color: #22c55e; }
        .step.active .step-label { color: #fff; }
        .step.pending .step-label { color: var(--muted); }
        .step-line { flex: 1; height: 2px; background: var(--border2); margin: 0 0.5rem; }
        .step-line.done { background: #22c55e; }

        /* Card */
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; }
        .card-icon { width: 56px; height: 56px; border-radius: 14px; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--purple-light); margin-bottom: 1.5rem; }
        .card-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
        .card-subtitle { font-size: 0.9rem; color: var(--muted); margin-bottom: 2rem; line-height: 1.6; }

        /* AI Badge */
        .ai-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.25); border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.8rem; font-weight: 600; color: var(--purple-light); margin-bottom: 1.5rem; }
        .ai-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--purple-light); animation: pulse 1.5s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Tabs */
        .tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; background: #111; border-radius: 10px; padding: 4px; }
        .tab-btn {
            flex: 1; padding: 0.6rem; border-radius: 8px; border: none; background: transparent;
            color: var(--muted); font-size: 0.85rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; gap: 0.4rem;
        }
        .tab-btn.active { background: var(--card); color: #fff; border: 1px solid var(--border); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Form */
        label { display: block; font-size: 0.8rem; font-weight: 600; color: #ccc; margin-bottom: 0.4rem; }
        textarea {
            width: 100%; background: #111; border: 1px solid var(--border2); border-radius: 10px;
            color: var(--text); font-family: 'Inter', sans-serif; font-size: 0.875rem; padding: 1rem;
            resize: vertical; min-height: 220px; transition: border-color 0.2s; outline: none; line-height: 1.6;
        }
        textarea:focus { border-color: var(--purple); }
        textarea::placeholder { color: var(--subtle); }

        /* File Upload */
        .upload-zone {
            border: 2px dashed var(--border2); border-radius: 12px; padding: 2.5rem; text-align: center;
            cursor: pointer; transition: all 0.2s; position: relative;
        }
        .upload-zone:hover, .upload-zone.drag-over { border-color: var(--purple); background: rgba(124,58,237,0.05); }
        .upload-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .upload-icon { font-size: 2.5rem; color: var(--subtle); margin-bottom: 1rem; }
        .upload-title { font-weight: 600; margin-bottom: 0.4rem; }
        .upload-hint { font-size: 0.8rem; color: var(--muted); }
        .file-types { display: flex; gap: 0.5rem; justify-content: center; margin-top: 1rem; flex-wrap: wrap; }
        .file-type-badge { background: var(--border2); border-radius: 4px; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 700; color: var(--muted); }
        .file-selected { display: none; align-items: center; gap: 0.75rem; background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1rem; font-size: 0.85rem; color: #86efac; }
        .file-selected i { color: #22c55e; }

        /* Hints */
        .hints { background: #111; border: 1px solid var(--border2); border-radius: 10px; padding: 1rem 1.25rem; margin-top: 1rem; }
        .hints-title { font-size: 0.75rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
        .hints ul { list-style: none; display: flex; flex-direction: column; gap: 0.4rem; }
        .hints ul li { font-size: 0.8rem; color: #aaa; display: flex; align-items: flex-start; gap: 0.5rem; }
        .hints ul li::before { content: '→'; color: var(--purple-light); flex-shrink: 0; }

        /* Skip */
        .skip-note { font-size: 0.8rem; color: var(--muted); text-align: center; margin-top: 1rem; }
        .skip-note a { color: var(--purple-light); text-decoration: none; }
        .skip-note a:hover { text-decoration: underline; }

        /* Buttons */
        .btn-primary {
            width: 100%; padding: 1rem; background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 700;
            font-family: 'Space Grotesk', sans-serif; cursor: pointer; transition: all 0.2s; margin-top: 1.5rem;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #6d28d9, #5b21b6); transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }
        .btn-primary .spinner { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* AI Processing overlay */
        .ai-processing { display: none; text-align: center; padding: 2rem; }
        .ai-processing .big-icon { font-size: 3rem; color: var(--purple-light); margin-bottom: 1rem; animation: pulse 1.5s ease-in-out infinite; }
        .ai-processing h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .ai-processing p { font-size: 0.85rem; color: var(--muted); }
        .ai-steps { list-style: none; margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .ai-steps li { font-size: 0.8rem; color: var(--muted); display: flex; align-items: center; gap: 0.5rem; }
        .ai-steps li.done { color: #86efac; }
        .ai-steps li.active { color: #fff; }
        .ai-steps li i { width: 16px; }

        @media(max-width:600px) { .card { padding: 1.5rem; } .card-title { font-size: 1.4rem; } }
    </style>
</head>
<body>
<div class="wrap">
    {{-- Progress --}}
    <div class="progress-bar">
        <div class="step done">
            <div class="step-num"><i class="fas fa-check"></i></div>
            <span class="step-label">Account</span>
        </div>
        <div class="step-line done"></div>
        <div class="step done">
            <div class="step-num"><i class="fas fa-check"></i></div>
            <span class="step-label">Payment</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="step-num">3</div>
            <span class="step-label">Business Info</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-num">4</div>
            <span class="step-label">Profile</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-num">5</div>
            <span class="step-label">Launch</span>
        </div>
    </div>

    <div class="card" id="mainCard">
        <div class="card-icon"><i class="fas fa-building"></i></div>
        <div class="card-title">Tell Us About Your Business</div>
        <div class="card-subtitle">
            Share information about your business, services, and goals. Our AI will use this to build your website with realistic, professional content tailored to your industry.
        </div>

        <div class="ai-badge">
            <div class="dot"></div>
            AI-Powered Content Generation
        </div>

        <form method="POST" action="{{ route('onboarding.business-info.save') }}" enctype="multipart/form-data" id="bizForm">
            @csrf

            <div class="tabs">
                <button type="button" class="tab-btn active" onclick="switchTab('text', this)">
                    <i class="fas fa-keyboard"></i> Type Your Info
                </button>
                <button type="button" class="tab-btn" onclick="switchTab('file', this)">
                    <i class="fas fa-file-upload"></i> Upload Document
                </button>
                <button type="button" class="tab-btn" onclick="switchTab('both', this)">
                    <i class="fas fa-layer-group"></i> Both
                </button>
            </div>

            {{-- Tab: Text --}}
            <div class="tab-panel active" id="tab-text">
                <label for="business_info">Describe Your Business</label>
                <textarea
                    name="business_info"
                    id="business_info"
                    placeholder="Tell us about your business...

For example:
• What does your business do?
• What products or services do you offer?
• Who are your target customers?
• What makes you different from competitors?
• Where are you located?
• What are your business goals?
• Any specific tone or style you prefer for your website?

The more detail you provide, the better your AI-generated website content will be."
                >{{ old('business_info') }}</textarea>

                <div class="hints">
                    <div class="hints-title">What to include</div>
                    <ul>
                        <li>Business name, type, and industry</li>
                        <li>Products or services you offer</li>
                        <li>Your target audience or ideal customers</li>
                        <li>Your unique selling points or competitive advantages</li>
                        <li>Location, years in business, team size</li>
                        <li>Any specific keywords or phrases important to your brand</li>
                    </ul>
                </div>
            </div>

            {{-- Tab: File --}}
            <div class="tab-panel" id="tab-file">
                <label>Upload a Business Document</label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="business_info_file" id="fileInput" accept=".pdf,.doc,.docx,.txt" onchange="handleFileSelect(this)">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-title">Drag & drop your file here</div>
                    <div class="upload-hint">or click to browse</div>
                    <div class="file-types">
                        <span class="file-type-badge">PDF</span>
                        <span class="file-type-badge">DOC</span>
                        <span class="file-type-badge">DOCX</span>
                        <span class="file-type-badge">TXT</span>
                    </div>
                </div>
                <div class="file-selected" id="fileSelected">
                    <i class="fas fa-file-check"></i>
                    <span id="fileName">No file selected</span>
                </div>

                <div class="hints" style="margin-top:1rem;">
                    <div class="hints-title">Accepted documents</div>
                    <ul>
                        <li>Company profile or brochure (PDF)</li>
                        <li>Business plan or proposal document</li>
                        <li>Product/service catalogue</li>
                        <li>About us page content or press kit</li>
                        <li>Any document describing your business (max 5MB)</li>
                    </ul>
                </div>
            </div>

            {{-- Tab: Both --}}
            <div class="tab-panel" id="tab-both">
                <label for="business_info_both">Describe Your Business</label>
                <textarea
                    name="business_info"
                    id="business_info_both"
                    placeholder="Add any additional details here to complement your uploaded document..."
                    style="min-height:140px;"
                >{{ old('business_info') }}</textarea>

                <div style="margin-top:1rem;">
                    <label>Upload a Business Document (Optional)</label>
                    <div class="upload-zone" id="uploadZone2">
                        <input type="file" name="business_info_file" id="fileInput2" accept=".pdf,.doc,.docx,.txt" onchange="handleFileSelect2(this)">
                        <div class="upload-icon" style="font-size:1.8rem;"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="upload-title" style="font-size:0.9rem;">Drag & drop or click to browse</div>
                        <div class="file-types">
                            <span class="file-type-badge">PDF</span>
                            <span class="file-type-badge">DOC</span>
                            <span class="file-type-badge">DOCX</span>
                            <span class="file-type-badge">TXT</span>
                        </div>
                    </div>
                    <div class="file-selected" id="fileSelected2">
                        <i class="fas fa-file-check"></i>
                        <span id="fileName2">No file selected</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">
                <span class="spinner" id="spinner"></span>
                <i class="fas fa-magic" id="submitIcon"></i>
                <span id="submitText">Generate My Website Content</span>
            </button>
        </form>

        <div class="skip-note">
            Not ready yet? <a href="{{ route('onboarding.profile') }}">Skip this step</a> and set up your site manually.
        </div>
    </div>

    {{-- AI Processing Overlay --}}
    <div class="card ai-processing" id="aiProcessing">
        <div class="big-icon"><i class="fas fa-robot"></i></div>
        <h3>AI is Building Your Website</h3>
        <p>This takes about 10–20 seconds. Please don't close this page.</p>
        <ul class="ai-steps" id="aiSteps">
            <li class="active" id="step1"><i class="fas fa-circle-notch fa-spin"></i> Analysing your business information…</li>
            <li id="step2"><i class="fas fa-circle"></i> Generating professional copy…</li>
            <li id="step3"><i class="fas fa-circle"></i> Creating service descriptions…</li>
            <li id="step4"><i class="fas fa-circle"></i> Writing SEO metadata…</li>
            <li id="step5"><i class="fas fa-circle"></i> Applying content to your site…</li>
        </ul>
    </div>
</div>

<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('fileSelected').style.display = 'flex';
        document.getElementById('fileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
    }
}

function handleFileSelect2(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('fileSelected2').style.display = 'flex';
        document.getElementById('fileName2').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
    }
}

// Drag & drop
['uploadZone', 'uploadZone2'].forEach(id => {
    const zone = document.getElementById(id);
    if (!zone) return;
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const input = zone.querySelector('input[type=file]');
        if (input && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            id === 'uploadZone' ? handleFileSelect(input) : handleFileSelect2(input);
        }
    });
});

// Show AI processing animation on submit
document.getElementById('bizForm').addEventListener('submit', function(e) {
    const hasText = document.getElementById('business_info')?.value?.trim() ||
                    document.getElementById('business_info_both')?.value?.trim();
    const hasFile = document.getElementById('fileInput')?.files?.length ||
                    document.getElementById('fileInput2')?.files?.length;

    if (hasText || hasFile) {
        document.getElementById('mainCard').style.display = 'none';
        document.getElementById('aiProcessing').style.display = 'block';

        // Animate steps
        const steps = ['step1','step2','step3','step4','step5'];
        let current = 0;
        const interval = setInterval(() => {
            if (current > 0) {
                const prev = document.getElementById(steps[current - 1]);
                prev.classList.remove('active');
                prev.classList.add('done');
                prev.querySelector('i').className = 'fas fa-check-circle';
            }
            if (current < steps.length) {
                const curr = document.getElementById(steps[current]);
                curr.classList.add('active');
                curr.querySelector('i').className = 'fas fa-circle-notch fa-spin';
                current++;
            } else {
                clearInterval(interval);
            }
        }, 3000);
    }
});
</script>
</body>
</html>
