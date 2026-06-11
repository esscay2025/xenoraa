@extends('layouts.admin')
@section('content')

<style>
:root {
    --cf-bg: var(--bg-primary, #f8fafc);
    --cf-card: var(--bg-card, #ffffff);
    --cf-border: var(--border, #e2e8f0);
    --cf-accent: var(--accent, #6366f1);
    --cf-text: var(--text-primary, #1e293b);
    --cf-muted: var(--text-muted, #94a3b8);
    --cf-label: var(--text-secondary, #64748b);
    --cf-radius: 10px;
    --cf-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.cf-page { padding: 1.5rem; background: var(--cf-bg); min-height: 100vh; }
.cf-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.cf-header h1 { font-size: 1.4rem; font-weight: 700; color: var(--cf-text); margin: 0; }
.cf-back { display: inline-flex; align-items: center; gap: .4rem; color: var(--cf-accent);
           text-decoration: none; font-size: .85rem; padding: .4rem .8rem;
           border: 1px solid var(--cf-accent); border-radius: 6px; }
.cf-back:hover { background: var(--cf-accent); color: #fff; }
.cf-section { background: var(--cf-card); border: 1px solid var(--cf-border);
              border-radius: var(--cf-radius); margin-bottom: 1.25rem;
              box-shadow: var(--cf-shadow); overflow: hidden; }
.cf-section-header { display: flex; align-items: center; justify-content: space-between;
                     padding: .75rem 1.25rem; background: var(--cf-accent);
                     cursor: pointer; user-select: none; }
.cf-section-header h3 { color: #fff; font-size: .9rem; font-weight: 600; margin: 0; }
.cf-section-header .cf-chevron { color: #fff; transition: transform .2s; font-size: .8rem; }
.cf-section-body { padding: 1.25rem; }
.cf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.cf-grid-2 { grid-template-columns: repeat(2, 1fr); }
.cf-grid-3 { grid-template-columns: repeat(3, 1fr); }
.cf-field { display: flex; flex-direction: column; gap: .3rem; }
.cf-field label { font-size: .78rem; font-weight: 600; color: var(--cf-label); text-transform: uppercase; letter-spacing: .04em; }
.cf-field input, .cf-field select, .cf-field textarea {
    padding: .5rem .75rem; border: 1px solid var(--cf-border); border-radius: 6px;
    background: var(--cf-bg); color: var(--cf-text); font-size: .88rem;
    transition: border-color .15s; width: 100%; }
.cf-field input:focus, .cf-field select:focus, .cf-field textarea:focus {
    outline: none; border-color: var(--cf-accent); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.cf-field textarea { resize: vertical; min-height: 80px; }
.cf-field-full { grid-column: 1 / -1; }
.cf-actions { display: flex; gap: .75rem; margin-top: 1.5rem; flex-wrap: wrap; }
.cf-btn { padding: .55rem 1.4rem; border-radius: 7px; font-size: .88rem; font-weight: 600;
          cursor: pointer; border: none; transition: all .15s; text-decoration: none;
          display: inline-flex; align-items: center; gap: .4rem; }
.cf-btn-primary { background: var(--cf-accent); color: #fff; }
.cf-btn-primary:hover { opacity: .88; }
        /* Cascading address dropdowns */
        .addr-select { width:100%; padding:.45rem .6rem; border-radius:6px; border:1px solid var(--cf-border,#444); background:var(--cf-input-bg,#1e1e2e); color:var(--cf-text,#e0e0e0); font-size:.85rem; }
        /* Address two-column parallel layout */
        .addr-two-col { display:grid; grid-template-columns:1fr auto 1fr; gap:0; align-items:stretch; }
        .addr-panel { padding:0 1.5rem; }
        .addr-panel:first-child { padding-left:0; padding-right:1.5rem; }
        .addr-panel:last-child { padding-left:1.5rem; padding-right:0; }
        .addr-panel-title { color:var(--cf-accent); margin:0 0 .75rem; font-size:.85rem; font-weight:600; line-height:2rem; }
        .addr-divider { display:flex; flex-direction:column; align-items:center; gap:.5rem; padding:0 .75rem; }
        .addr-divider-line { flex:1; width:1px; background:var(--cf-border,#444); min-height:1rem; }
        .copy-addr-btn { padding:.5rem .65rem; background:var(--cf-accent,#6c63ff); color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:1rem; line-height:1; flex-shrink:0; }
        .copy-addr-btn:hover { opacity:.85; }

.cf-btn-secondary { background: transparent; color: var(--cf-accent);
                    border: 1px solid var(--cf-accent); }
.cf-btn-secondary:hover { background: var(--cf-accent); color: #fff; }
.cf-btn-danger { background: #ef4444; color: #fff; }
.cf-btn-danger:hover { background: #dc2626; }

/* View page */
.cv-page { padding: 1.5rem; background: var(--cf-bg); min-height: 100vh; }
.cv-header { display: flex; align-items: flex-start; justify-content: space-between;
             margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.cv-title-block h1 { font-size: 1.5rem; font-weight: 700; color: var(--cf-text); margin: 0 0 .3rem; }
.cv-badge { display: inline-block; padding: .25rem .7rem; border-radius: 20px; font-size: .75rem;
            font-weight: 600; background: var(--cf-accent); color: #fff; }
.cv-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
.cv-section { background: var(--cf-card); border: 1px solid var(--cf-border);
              border-radius: var(--cf-radius); margin-bottom: 1.25rem; overflow: hidden;
              box-shadow: var(--cf-shadow); }
.cv-section-header { padding: .65rem 1.25rem; background: var(--cf-accent); }
.cv-section-header h3 { color: #fff; font-size: .85rem; font-weight: 600; margin: 0; }
.cv-section-body { padding: 1.25rem; }
.cv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
.cv-field { display: flex; flex-direction: column; gap: .2rem; }
.cv-field .cv-label { font-size: .72rem; font-weight: 600; color: var(--cf-muted);
                      text-transform: uppercase; letter-spacing: .04em; }
.cv-field .cv-value { font-size: .9rem; color: var(--cf-text); font-weight: 500; }
.cv-field .cv-empty { color: var(--cf-muted); font-style: italic; }

/* Line items table */
.li-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.li-table th { background: var(--cf-accent); color: #fff; padding: .5rem .75rem;
               text-align: left; font-size: .78rem; font-weight: 600; }
.li-table td { padding: .5rem .75rem; border-bottom: 1px solid var(--cf-border); color: var(--cf-text); }
.li-table td input, .li-table td select { padding: .3rem .5rem; border: 1px solid var(--cf-border);
    border-radius: 4px; background: var(--cf-bg); color: var(--cf-text); font-size: .82rem; width: 100%; }
.li-table .li-total-row td { background: var(--cf-bg); font-weight: 600; }
.li-add-btn { margin-top: .5rem; padding: .35rem .9rem; background: var(--cf-accent); color: #fff;
              border: none; border-radius: 5px; cursor: pointer; font-size: .82rem; }
.li-remove-btn { background: #ef4444; color: #fff; border: none; border-radius: 4px;
                 padding: .2rem .5rem; cursor: pointer; font-size: .75rem; }
.li-summary { margin-top: 1rem; display: flex; flex-direction: column; align-items: flex-end; gap: .4rem; }
.li-summary-row { display: flex; gap: 2rem; align-items: center; font-size: .88rem; }
.li-summary-row label { color: var(--cf-label); font-weight: 600; min-width: 120px; text-align: right; }
.li-summary-row input { width: 140px; padding: .35rem .6rem; border: 1px solid var(--cf-border);
                        border-radius: 5px; background: var(--cf-bg); color: var(--cf-text); font-size: .88rem; }
.li-grand-total { font-size: 1rem; font-weight: 700; color: var(--cf-accent); }
</style>
<script>
function toggleSection(el) {
    const body = el.nextElementSibling;
    const chevron = el.querySelector('.cf-chevron');
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
    chevron.style.transform = body.style.display === 'none' ? 'rotate(-90deg)' : '';
}
function addLineItem(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => i.value = '');
    tbody.appendChild(row);
    recalcTotals(tableId);
}
function removeLineItem(btn, tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    if (tbody.rows.length > 1) { btn.closest('tr').remove(); recalcTotals(tableId); }
}
function recalcTotals(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    let subtotal = 0;
    tbody.querySelectorAll('tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.li-qty')?.value) || 0;
        const price = parseFloat(row.querySelector('.li-price')?.value) || 0;
        const disc = parseFloat(row.querySelector('.li-disc')?.value) || 0;
        const tax = parseFloat(row.querySelector('.li-tax')?.value) || 0;
        const amt = qty * price;
        const total = amt - disc + tax;
        if (row.querySelector('.li-amt')) row.querySelector('.li-amt').value = amt.toFixed(2);
        if (row.querySelector('.li-total')) row.querySelector('.li-total').value = total.toFixed(2);
        subtotal += total;
    });
    const discEl = document.getElementById(tableId + '_discount');
    const taxEl = document.getElementById(tableId + '_tax');
    const adjEl = document.getElementById(tableId + '_adjustment');
    const grandEl = document.getElementById(tableId + '_grand');
    const subEl = document.getElementById(tableId + '_subtotal');
    if (subEl) subEl.value = subtotal.toFixed(2);
    const disc = parseFloat(discEl?.value) || 0;
    const tax = parseFloat(taxEl?.value) || 0;
    const adj = parseFloat(adjEl?.value) || 0;
    if (grandEl) grandEl.value = (subtotal - disc + tax + adj).toFixed(2);
}
function serializeLineItems(tableId, fieldId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const items = [];
    tbody.querySelectorAll('tr').forEach(row => {
        items.push({
            product: row.querySelector('.li-product')?.value || '',
            qty: row.querySelector('.li-qty')?.value || '',
            price: row.querySelector('.li-price')?.value || '',
            discount: row.querySelector('.li-disc')?.value || '',
            tax: row.querySelector('.li-tax')?.value || '',
            total: row.querySelector('.li-total')?.value || ''
        });
    });
    document.getElementById(fieldId).value = JSON.stringify(items);
}

/* ── Product Search & Select ─────────────────────────────────────────── */
let productSearchTimeout = null;
function initProductSearch(inputEl, tableId) {
    inputEl.addEventListener('input', function() {
        clearTimeout(productSearchTimeout);
        const q = this.value.trim();
        const dropdown = inputEl.parentElement.querySelector('.product-dropdown');
        if (q.length < 1) { if (dropdown) dropdown.remove(); return; }
        productSearchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.crm2.inventory.products.search') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(products => {
                    let existing = inputEl.parentElement.querySelector('.product-dropdown');
                    if (existing) existing.remove();
                    if (!products.length) return;
                    const dd = document.createElement('div');
                    dd.className = 'product-dropdown';
                    dd.style.cssText = 'position:absolute;z-index:9999;background:var(--bg-card,#fff);border:1px solid var(--border,#e2e8f0);border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:220px;overflow-y:auto;min-width:280px;';
                    products.forEach(p => {
                        const item = document.createElement('div');
                        item.style.cssText = 'padding:.5rem .75rem;cursor:pointer;font-size:.85rem;border-bottom:1px solid var(--border,#e2e8f0);';
                        item.innerHTML = `<strong>${p.name}</strong>${p.product_code ? ' <span style="color:#94a3b8;font-size:.78rem">['+p.product_code+']</span>' : ''}<br><span style="color:var(--accent,#6366f1);font-size:.8rem">\u20b9${parseFloat(p.unit_price).toLocaleString('en-IN',{minimumFractionDigits:2})} ${p.usage_unit ? '/ '+p.usage_unit : ''}</span>`;
                        item.addEventListener('mouseenter', () => item.style.background = 'var(--bg-primary,#f8fafc)');
                        item.addEventListener('mouseleave', () => item.style.background = '');
                        item.addEventListener('click', () => {
                            inputEl.value = p.name;
                            inputEl.dataset.productId = p.id;
                            const row = inputEl.closest('tr');
                            if (row) {
                                const priceEl = row.querySelector('.li-price');
                                if (priceEl) { priceEl.value = parseFloat(p.unit_price).toFixed(2); priceEl.dispatchEvent(new Event('input')); }
                            }
                            dd.remove();
                        });
                        dd.appendChild(item);
                    });
                    inputEl.parentElement.style.position = 'relative';
                    inputEl.parentElement.appendChild(dd);
                    setTimeout(() => document.addEventListener('click', function handler(e) {
                        if (!dd.contains(e.target) && e.target !== inputEl) { dd.remove(); document.removeEventListener('click', handler); }
                    }), 10);
                });
        }, 250);
    });
}
function addLineItemWithSearch(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => {
        if (i.classList.contains('li-qty')) i.value = '1';
        else if (i.classList.contains('li-price') || i.classList.contains('li-disc') || i.classList.contains('li-tax') || i.classList.contains('li-amt') || i.classList.contains('li-total')) i.value = '0';
        else i.value = '';
        delete i.dataset.productId;
    });
    row.querySelectorAll('.product-dropdown').forEach(d => d.remove());
    tbody.appendChild(row);
    const newInput = row.querySelector('.li-product');
    if (newInput) initProductSearch(newInput, tableId);
    recalcTotals(tableId);
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.li-product').forEach(inp => {
        const tableId = inp.closest('table')?.id;
        if (tableId) initProductSearch(inp, tableId);
    });
});


    /* ===== Cascading Country / State / City + Copy Address ===== */
    const GEO_DATA = {
        "India": {
            "Andhra Pradesh": ["Visakhapatnam","Vijayawada","Guntur","Nellore","Kurnool","Tirupati","Kakinada","Rajahmundry","Kadapa","Anantapur"],
            "Arunachal Pradesh": ["Itanagar","Naharlagun","Pasighat","Tawang","Ziro"],
            "Assam": ["Guwahati","Silchar","Dibrugarh","Jorhat","Nagaon","Tinsukia","Tezpur"],
            "Bihar": ["Patna","Gaya","Bhagalpur","Muzaffarpur","Purnia","Darbhanga","Bihar Sharif","Arrah"],
            "Chhattisgarh": ["Raipur","Bhilai","Bilaspur","Korba","Durg","Rajnandgaon"],
            "Goa": ["Panaji","Margao","Vasco da Gama","Mapusa","Ponda"],
            "Gujarat": ["Ahmedabad","Surat","Vadodara","Rajkot","Bhavnagar","Jamnagar","Gandhinagar","Junagadh","Anand"],
            "Haryana": ["Faridabad","Gurugram","Panipat","Ambala","Yamunanagar","Rohtak","Hisar","Karnal","Sonipat"],
            "Himachal Pradesh": ["Shimla","Dharamshala","Solan","Mandi","Kullu","Manali"],
            "Jharkhand": ["Ranchi","Jamshedpur","Dhanbad","Bokaro","Deoghar","Hazaribagh"],
            "Karnataka": ["Bengaluru","Mysuru","Hubli","Mangaluru","Belagavi","Kalaburagi","Davangere","Ballari","Tumkur","Shivamogga"],
            "Kerala": ["Thiruvananthapuram","Kochi","Kozhikode","Thrissur","Kollam","Palakkad","Alappuzha","Kannur","Kottayam"],
            "Madhya Pradesh": ["Bhopal","Indore","Jabalpur","Gwalior","Ujjain","Sagar","Dewas","Satna","Ratlam"],
            "Maharashtra": ["Mumbai","Pune","Nagpur","Nashik","Aurangabad","Solapur","Amravati","Kolhapur","Thane","Navi Mumbai"],
            "Manipur": ["Imphal","Thoubal","Bishnupur","Churachandpur"],
            "Meghalaya": ["Shillong","Tura","Jowai"],
            "Mizoram": ["Aizawl","Lunglei","Champhai"],
            "Nagaland": ["Kohima","Dimapur","Mokokchung"],
            "Odisha": ["Bhubaneswar","Cuttack","Rourkela","Berhampur","Sambalpur","Puri","Balasore"],
            "Punjab": ["Ludhiana","Amritsar","Jalandhar","Patiala","Bathinda","Mohali","Firozpur","Hoshiarpur"],
            "Rajasthan": ["Jaipur","Jodhpur","Udaipur","Kota","Bikaner","Ajmer","Bhilwara","Alwar","Bharatpur"],
            "Sikkim": ["Gangtok","Namchi","Gyalshing"],
            "Tamil Nadu": ["Chennai","Coimbatore","Madurai","Tiruchirappalli","Salem","Tirunelveli","Tiruppur","Vellore","Erode","Thoothukudi","Thanjavur"],
            "Telangana": ["Hyderabad","Warangal","Nizamabad","Karimnagar","Khammam","Ramagundam","Mahbubnagar"],
            "Tripura": ["Agartala","Dharmanagar","Udaipur"],
            "Uttar Pradesh": ["Lucknow","Kanpur","Agra","Varanasi","Meerut","Allahabad","Ghaziabad","Noida","Bareilly","Aligarh","Moradabad","Saharanpur"],
            "Uttarakhand": ["Dehradun","Haridwar","Roorkee","Haldwani","Rudrapur","Kashipur","Rishikesh"],
            "West Bengal": ["Kolkata","Asansol","Siliguri","Durgapur","Bardhaman","Malda","Barasat","Krishnanagar"],
            "Delhi": ["New Delhi","North Delhi","South Delhi","East Delhi","West Delhi","Central Delhi","Dwarka","Rohini","Janakpuri"],
            "Jammu and Kashmir": ["Srinagar","Jammu","Anantnag","Baramulla","Sopore"],
            "Ladakh": ["Leh","Kargil"],
            "Chandigarh": ["Chandigarh"],
            "Puducherry": ["Puducherry","Karaikal","Mahe","Yanam"],
            "Andaman and Nicobar Islands": ["Port Blair"],
            "Dadra and Nagar Haveli and Daman and Diu": ["Daman","Diu","Silvassa"],
            "Lakshadweep": ["Kavaratti"]
        },
        "United States": {
            "California": ["Los Angeles","San Francisco","San Diego","San Jose","Sacramento","Fresno","Long Beach","Oakland","Bakersfield","Anaheim"],
            "New York": ["New York City","Buffalo","Rochester","Yonkers","Syracuse","Albany","New Rochelle","Mount Vernon","Schenectady"],
            "Texas": ["Houston","San Antonio","Dallas","Austin","Fort Worth","El Paso","Arlington","Corpus Christi","Plano","Lubbock"],
            "Florida": ["Jacksonville","Miami","Tampa","Orlando","St. Petersburg","Hialeah","Tallahassee","Fort Lauderdale","Port St. Lucie"],
            "Illinois": ["Chicago","Aurora","Joliet","Naperville","Rockford","Springfield","Elgin","Peoria","Champaign"],
            "Pennsylvania": ["Philadelphia","Pittsburgh","Allentown","Erie","Reading","Scranton","Bethlehem","Lancaster"],
            "Ohio": ["Columbus","Cleveland","Cincinnati","Toledo","Akron","Dayton","Parma","Canton","Youngstown"],
            "Georgia": ["Atlanta","Augusta","Columbus","Macon","Savannah","Athens","Sandy Springs","Roswell"],
            "North Carolina": ["Charlotte","Raleigh","Greensboro","Durham","Winston-Salem","Fayetteville","Cary","Wilmington"],
            "Michigan": ["Detroit","Grand Rapids","Warren","Sterling Heights","Ann Arbor","Lansing","Flint","Dearborn"]
        },
        "United Kingdom": {
            "England": ["London","Birmingham","Manchester","Leeds","Sheffield","Liverpool","Bristol","Leicester","Coventry","Bradford","Nottingham"],
            "Scotland": ["Glasgow","Edinburgh","Aberdeen","Dundee","Inverness","Stirling","Perth"],
            "Wales": ["Cardiff","Swansea","Newport","Wrexham","Barry","Neath"],
            "Northern Ireland": ["Belfast","Derry","Lisburn","Newry","Armagh","Ballymena"]
        },
        "Canada": {
            "Ontario": ["Toronto","Ottawa","Mississauga","Brampton","Hamilton","London","Markham","Vaughan","Kitchener","Windsor"],
            "Quebec": ["Montreal","Quebec City","Laval","Gatineau","Longueuil","Sherbrooke","Saguenay","Levis","Trois-Rivieres"],
            "British Columbia": ["Vancouver","Surrey","Burnaby","Richmond","Kelowna","Abbotsford","Coquitlam","Langley","Saanich"],
            "Alberta": ["Calgary","Edmonton","Red Deer","Lethbridge","St. Albert","Medicine Hat","Grande Prairie","Airdrie"],
            "Manitoba": ["Winnipeg","Brandon","Steinbach","Thompson","Portage la Prairie"],
            "Saskatchewan": ["Saskatoon","Regina","Prince Albert","Moose Jaw","Swift Current"]
        },
        "Australia": {
            "New South Wales": ["Sydney","Newcastle","Wollongong","Maitland","Coffs Harbour","Wagga Wagga","Albury","Port Macquarie"],
            "Victoria": ["Melbourne","Geelong","Ballarat","Bendigo","Shepparton","Melton","Mildura","Wodonga"],
            "Queensland": ["Brisbane","Gold Coast","Sunshine Coast","Townsville","Cairns","Toowoomba","Mackay","Rockhampton"],
            "Western Australia": ["Perth","Bunbury","Geraldton","Kalgoorlie","Mandurah","Albany","Broome"],
            "South Australia": ["Adelaide","Mount Gambier","Whyalla","Murray Bridge","Port Augusta","Port Pirie"],
            "Tasmania": ["Hobart","Launceston","Devonport","Burnie"]
        },
        "Germany": {
            "Bavaria": ["Munich","Nuremberg","Augsburg","Regensburg","Ingolstadt","Wurzburg","Fürth","Erlangen"],
            "North Rhine-Westphalia": ["Cologne","Düsseldorf","Dortmund","Essen","Duisburg","Bochum","Wuppertal","Bielefeld","Bonn","Münster"],
            "Baden-Württemberg": ["Stuttgart","Mannheim","Karlsruhe","Freiburg","Heidelberg","Heilbronn","Ulm","Pforzheim"],
            "Berlin": ["Berlin"],
            "Hamburg": ["Hamburg"],
            "Hesse": ["Frankfurt","Wiesbaden","Kassel","Darmstadt","Offenbach","Hanau"],
            "Saxony": ["Leipzig","Dresden","Chemnitz","Zwickau","Plauen"],
            "Lower Saxony": ["Hanover","Braunschweig","Osnabrück","Oldenburg","Wolfsburg","Göttingen"]
        },
        "France": {
            "Île-de-France": ["Paris","Boulogne-Billancourt","Saint-Denis","Argenteuil","Montreuil","Versailles","Nanterre","Créteil"],
            "Auvergne-Rhône-Alpes": ["Lyon","Grenoble","Clermont-Ferrand","Saint-Étienne","Annecy","Chambéry","Valence"],
            "Provence-Alpes-Côte d'Azur": ["Marseille","Nice","Toulon","Aix-en-Provence","Avignon","Cannes","Antibes"],
            "Nouvelle-Aquitaine": ["Bordeaux","Limoges","Poitiers","Pau","Bayonne","La Rochelle","Périgueux"],
            "Occitanie": ["Toulouse","Montpellier","Nîmes","Perpignan","Béziers","Narbonne","Albi"]
        },
        "Singapore": {
            "Central Region": ["Downtown Core","Marina Bay","Orchard","Novena","Toa Payoh","Bishan","Ang Mo Kio"],
            "East Region": ["Tampines","Pasir Ris","Bedok","Geylang","Kallang","Marine Parade"],
            "North Region": ["Woodlands","Sembawang","Yishun","Mandai"],
            "North-East Region": ["Sengkang","Punggol","Hougang","Serangoon","Buangkok"],
            "West Region": ["Jurong East","Jurong West","Clementi","Bukit Batok","Choa Chu Kang","Bukit Panjang","Tengah"]
        },
        "UAE": {
            "Dubai": ["Dubai","Deira","Bur Dubai","Jumeirah","Al Quoz","Business Bay","Downtown Dubai","Dubai Marina","JLT","Palm Jumeirah"],
            "Abu Dhabi": ["Abu Dhabi","Al Ain","Al Dhafra","Khalifa City","Masdar City","Yas Island"],
            "Sharjah": ["Sharjah","Khor Fakkan","Kalba","Dibba Al Hisn"],
            "Ajman": ["Ajman","Masfout"],
            "Ras Al Khaimah": ["Ras Al Khaimah","Al Jazirah Al Hamra"],
            "Fujairah": ["Fujairah","Dibba Al Fujairah"],
            "Umm Al Quwain": ["Umm Al Quwain"]
        },
        "Malaysia": {
            "Selangor": ["Shah Alam","Petaling Jaya","Subang Jaya","Klang","Ampang","Sepang","Rawang"],
            "Kuala Lumpur": ["Kuala Lumpur","Chow Kit","Brickfields","Bangsar","Mont Kiara","Kepong","Setapak"],
            "Johor": ["Johor Bahru","Muar","Batu Pahat","Kluang","Segamat","Pontian"],
            "Penang": ["George Town","Butterworth","Bukit Mertajam","Bayan Lepas","Nibong Tebal"],
            "Perak": ["Ipoh","Taiping","Teluk Intan","Manjung","Kuala Kangsar"],
            "Sabah": ["Kota Kinabalu","Sandakan","Tawau","Lahad Datu","Keningau"],
            "Sarawak": ["Kuching","Miri","Sibu","Bintulu","Limbang"]
        }
    };

    function populateStates(countrySelectId, stateSelectId, citySelectId, selectedState, selectedCity) {
        const country = document.getElementById(countrySelectId)?.value || '';
        const stateEl = document.getElementById(stateSelectId);
        const cityEl = document.getElementById(citySelectId);
        if (!stateEl || !cityEl) return;
        stateEl.innerHTML = '<option value="">-- Select State --</option>';
        cityEl.innerHTML = '<option value="">-- Select City --</option>';
        const states = GEO_DATA[country] ? Object.keys(GEO_DATA[country]) : [];
        states.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s; opt.textContent = s;
            if (s === selectedState) opt.selected = true;
            stateEl.appendChild(opt);
        });
        if (selectedState) populateCities(countrySelectId, stateSelectId, citySelectId, selectedCity);
    }

    function populateCities(countrySelectId, stateSelectId, citySelectId, selectedCity) {
        const country = document.getElementById(countrySelectId)?.value || '';
        const state = document.getElementById(stateSelectId)?.value || '';
        const cityEl = document.getElementById(citySelectId);
        if (!cityEl) return;
        cityEl.innerHTML = '<option value="">-- Select City --</option>';
        const cities = (GEO_DATA[country] && GEO_DATA[country][state]) ? GEO_DATA[country][state] : [];
        cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c; opt.textContent = c;
            if (c === selectedCity) opt.selected = true;
            cityEl.appendChild(opt);
        });
    }

    function buildCountrySelect(id, selectedVal) {
        const el = document.getElementById(id);
        if (!el) return;
        const countries = Object.keys(GEO_DATA);
        // Sort: India first, then alphabetical
        countries.sort((a,b) => a === 'India' ? -1 : b === 'India' ? 1 : a.localeCompare(b));
        let html = '<option value="">-- Select Country --</option>';
        countries.forEach(c => {
            html += `<option value="${c}"${c === selectedVal ? ' selected' : ''}>${c}</option>`;
        });
        el.innerHTML = html;
    }

    function copyBillingToShipping() {
    // Copy text fields
    ['building','street','zip'].forEach(f => {
        const src = document.querySelector('[name="bill_'+f+'"]');
        const dst = document.querySelector('[name="ship_'+f+'"]');
        if (src && dst) dst.value = src.value;
    });
    // Copy cascading dropdowns
    const billCountry = document.getElementById('bill_country')?.value || '';
    const billState   = document.getElementById('bill_state')?.value || '';
    const billCity    = document.getElementById('bill_city')?.value || '';
    if (billCountry) {
        buildCountrySelect('ship_country', billCountry);
        populateStates('ship_country','ship_state','ship_city', billState, billCity);
    }
}

    // Initialize address dropdowns reliably regardless of DOM state
    function initAddressDropdowns() {
        // Billing
        const billCountry = document.getElementById('bill_country')?.getAttribute('data-val') || '';
        const billState   = document.getElementById('bill_state')?.getAttribute('data-val') || '';
        const billCity    = document.getElementById('bill_city')?.getAttribute('data-val') || '';
        buildCountrySelect('bill_country', billCountry);
        if (billCountry) populateStates('bill_country','bill_state','bill_city', billState, billCity);

        // Shipping
        const shipCountry = document.getElementById('ship_country')?.getAttribute('data-val') || '';
        const shipState   = document.getElementById('ship_state')?.getAttribute('data-val') || '';
        const shipCity    = document.getElementById('ship_city')?.getAttribute('data-val') || '';
        buildCountrySelect('ship_country', shipCountry);
        if (shipCountry) populateStates('ship_country','ship_state','ship_city', shipState, shipCity);
        // Wire up change events
        const bc = document.getElementById('bill_country');
        const bs = document.getElementById('bill_state');
        const sc = document.getElementById('ship_country');
        const ss = document.getElementById('ship_state');
        if (bc) bc.addEventListener('change', () => populateStates('bill_country','bill_state','bill_city','',''));
        if (bs) bs.addEventListener('change', () => populateCities('bill_country','bill_state','bill_city',''));
        if (sc) sc.addEventListener('change', () => populateStates('ship_country','ship_state','ship_city','',''));
        if (ss) ss.addEventListener('change', () => populateCities('ship_country','ship_state','ship_city',''));
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAddressDropdowns);
    } else {
        setTimeout(initAddressDropdowns, 0);
    }
    /* ===== End Address JS ===== */

    </script>

<div class="cf-page">
    <div class="cf-header">
        <a href="{{ route('admin.crm2.inventory.quotes') }}" class="cf-back">&#8592; Quotes</a>
        <h1>New Quote</h1>
    </div>
    <form method="POST" action="{{ route('admin.crm2.inventory.store') }}" onsubmit="serializeLineItems('quote_items','quote_items_json')">
        @csrf
        <input type="hidden" name="type" value="quote">

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Quote Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-3">
                    <div class="cf-field">
                        <label>Quote Owner</label>
                        <select name="owner_id">
                            <option value="">-- Select Owner --</option>
                            @foreach($staff as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cf-field">
                        <label>Subject *</label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" placeholder="Quote subject">
                    </div>
                    <div class="cf-field">
                        <label>Quote Stage</label>
                        <select name="stage">
                            <option value="Draft">Draft</option>
                            <option value="Delivered">Delivered</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Closed Won">Closed Won</option>
                            <option value="Closed Lost">Closed Lost</option>
                        </select>
                    </div>
                    <div class="cf-field">
                        <label>Valid Until</label>
                        <input type="date" name="valid_until" value="{{ old('valid_until') }}">
                    </div>
                    <div class="cf-field">
                        <label>Team</label>
                        <input type="text" name="team" value="{{ old('team') }}" placeholder="Team name">
                    </div>
                    <div class="cf-field">
                        <label>Carrier</label>
                        <select name="carrier">
                            <option value="">-- Select --</option>
                            <option value="FedEx">FedEx</option>
                            <option value="DHL">DHL</option>
                            <option value="UPS">UPS</option>
                            <option value="DTDC">DTDC</option>
                            <option value="Blue Dart">Blue Dart</option>
                            <option value="India Post">India Post</option>
                        </select>
                    </div>
                    <div class="cf-field">
                        <label>Account</label>
                        <select name="account_id">
                            <option value="">-- Select Account --</option>
                            @foreach($accounts as $a)
                            <option value="{{ $a->id }}" {{ old('account_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cf-field">
                        <label>Contact</label>
                        <select name="contact_id">
                            <option value="">-- Select Contact --</option>
                            @foreach($contacts as $c)
                            <option value="{{ $c->id }}" {{ old('contact_id') == $c->id ? 'selected' : '' }}>{{ $c->first_name }} {{ $c->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cf-field">
                        <label>Deal</label>
                        <select name="deal_id">
                            <option value="">-- Select Deal --</option>
                            @foreach($deals as $d)
                            <option value="{{ $d->id }}" {{ old('deal_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Address Information</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="addr-two-col">
                    <div class="addr-panel">
                        <h4 class="addr-panel-title">Billing Address</h4>
                        <div class="cf-grid">
                            <div class="cf-field cf-field-full"><label>Building / Apartment</label><input type="text" name="bill_building" value="{{ old('bill_building') }}"></div>
                            <div class="cf-field cf-field-full"><label>Street Address</label><input type="text" name="bill_street" value="{{ old('bill_street') }}"></div>
                            <div class="cf-field"><label>City</label><select name="bill_city" id="bill_city" class="addr-select"><option value="">-- Select City --</option></select></div>
                            <div class="cf-field"><label>State / Province</label><select name="bill_state" id="bill_state" class="addr-select" onchange="populateCities('bill_country','bill_state','bill_city','')"><option value="">-- Select State --</option></select></div>
                            <div class="cf-field cf-field-full"><label>Country / Region</label><select name="bill_country" id="bill_country" class="addr-select" onchange="populateStates('bill_country','bill_state','bill_city','','')"><option value="">-- Select Country --</option></select></div>
                            <div class="cf-field"><label>Zip / Postal Code</label><input type="text" name="bill_zip" id="bill_zip" value="{{ old('bill_zip') }}"></div>
                        </div>
                    </div>
                    <div class="addr-divider">
                        <div class="addr-divider-line"></div>
                        <button type="button" class="copy-addr-btn" onclick="copyBillingToShipping()" title="Copy Billing to Shipping">&#x2398;</button>
                        <div class="addr-divider-line"></div>
                    </div>
                    <div class="addr-panel">
                        <h4 class="addr-panel-title">Shipping Address</h4>
                        <div class="cf-grid">
                            <div class="cf-field cf-field-full"><label>Building / Apartment</label><input type="text" name="ship_building" value="{{ old('ship_building') }}"></div>
                            <div class="cf-field cf-field-full"><label>Street Address</label><input type="text" name="ship_street" value="{{ old('ship_street') }}"></div>
                            <div class="cf-field"><label>City</label><select name="ship_city" id="ship_city" class="addr-select"><option value="">-- Select City --</option></select></div>
                            <div class="cf-field"><label>State / Province</label><select name="ship_state" id="ship_state" class="addr-select" onchange="populateCities('ship_country','ship_state','ship_city','')"><option value="">-- Select State --</option></select></div>
                            <div class="cf-field cf-field-full"><label>Country / Region</label><select name="ship_country" id="ship_country" class="addr-select" onchange="populateStates('ship_country','ship_state','ship_city','','')"><option value="">-- Select Country --</option></select></div>
                            <div class="cf-field"><label>Zip / Postal Code</label><input type="text" name="ship_zip" id="ship_zip" value="{{ old('ship_zip') }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Quoted Items</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <input type="hidden" name="line_items" id="quote_items_json">
                <table class="li-table" id="quote_items">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>List Price (₹)</th>
                            <th>Amount (₹)</th>
                            <th>Discount (₹)</th>
                            <th>Tax (₹)</th>
                            <th>Total (₹)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="text" class="li-product" placeholder="Search product..." autocomplete="off"></td>
                            <td><input type="number" class="li-qty" value="1" min="1" style="width:60px" oninput="recalcTotals('quote_items')"></td>
                            <td><input type="number" class="li-price" step="0.01" value="0" style="width:90px" oninput="recalcTotals('quote_items')"></td>
                            <td><input type="number" class="li-amt" step="0.01" value="0" style="width:90px" readonly></td>
                            <td><input type="number" class="li-disc" step="0.01" value="0" style="width:80px" oninput="recalcTotals('quote_items')"></td>
                            <td><input type="number" class="li-tax" step="0.01" value="0" style="width:80px" oninput="recalcTotals('quote_items')"></td>
                            <td><input type="number" class="li-total" step="0.01" value="0" style="width:90px" readonly></td>
                            <td><button type="button" class="li-remove-btn" onclick="removeLineItem(this,'quote_items')">&#10005;</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="li-add-btn" onclick="addLineItemWithSearch('quote_items')">+ Add Row</button>
                <div class="li-summary">
                    <div class="li-summary-row"><label>Sub Total (₹)</label><input type="number" id="quote_items_subtotal" name="subtotal" step="0.01" value="0" readonly></div>
                    <div class="li-summary-row"><label>Discount (₹)</label><input type="number" id="quote_items_discount" name="discount_amount" step="0.01" value="0" oninput="recalcTotals('quote_items')"></div>
                    <div class="li-summary-row"><label>Tax (₹)</label><input type="number" id="quote_items_tax" name="tax_amount" step="0.01" value="0" oninput="recalcTotals('quote_items')"></div>
                    <div class="li-summary-row"><label>Adjustment (₹)</label><input type="number" id="quote_items_adjustment" name="adjustment" step="0.01" value="0" oninput="recalcTotals('quote_items')"></div>
                    <div class="li-summary-row li-grand-total"><label>Grand Total (₹)</label><input type="number" id="quote_items_grand" name="grand_total" step="0.01" value="0" readonly></div>
                </div>
            </div>
        </div>

        <div class="cf-section">
            <div class="cf-section-header" onclick="toggleSection(this)">
                <h3>Terms &amp; Description</h3><span class="cf-chevron">&#9660;</span>
            </div>
            <div class="cf-section-body">
                <div class="cf-grid cf-grid-2">
                    <div class="cf-field">
                        <label>Terms and Conditions</label>
                        <textarea name="terms" rows="4" placeholder="Enter terms...">{{ old('terms') }}</textarea>
                    </div>
                    <div class="cf-field">
                        <label>Description / Notes</label>
                        <textarea name="notes" rows="4" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="cf-actions">
            <button type="submit" class="cf-btn cf-btn-primary">&#10003; Save Quote</button>
            <a href="{{ route('admin.crm2.inventory.quotes') }}" class="cf-btn cf-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
