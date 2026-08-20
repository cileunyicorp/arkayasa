document.addEventListener("DOMContentLoaded", function () {
    const roller = document.getElementById("year-roller");
    const yearInput = document.getElementById("year");

    if (roller && yearInput) {
        roller.addEventListener("scroll", function () {
            const items = roller.querySelectorAll(".lottery-item[data-value]");
            const rollerRect = roller.getBoundingClientRect();
            const rollerCenter = rollerRect.top + (rollerRect.height / 2);

            let closestYear = "";
            let minDistance = Infinity;

            items.forEach(function (item) {
                const itemRect = item.getBoundingClientRect();
                const itemCenter = itemRect.top + (itemRect.height / 2);
                const distance = Math.abs(rollerCenter - itemCenter);

                if (distance < minDistance) {
                    minDistance = distance;
                    closestYear = item.dataset.value;
                }
            });

            if (closestYear) {
                yearInput.value = closestYear;
            }
        });
    }

    // Inisialisasi Autocomplete Brand & Partner
    setupBrandAutocomplete();
    setupPartnerAutocomplete();
});

function togglePartnerSelect() {
    const ownershipEl = document.getElementById('ownership_type');
    const container = document.getElementById('partner-select-container');
    if (!ownershipEl || !container) return;

    const ownership = ownershipEl.value;
    if (ownership === 'Investor' || ownership === 'Rent to Rent') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        const partnerIdEl = document.getElementById('partner_id');
        const partnerNameEl = document.getElementById('partner_name_input');
        if (partnerIdEl) partnerIdEl.value = '';
        if (partnerNameEl) partnerNameEl.value = '';
    }
}

// -------------------------------------------------------------
// Autocomplete Brand Mobil
// -------------------------------------------------------------
function setupBrandAutocomplete() {
    const brandInput = document.getElementById('brand');
    const listContainer = document.getElementById('brand-autocomplete-list');
    let currentFocus = -1;

    if (!brandInput || !listContainer) return;

    const availableBrands = window.availableBrands || [
        "Toyota", "Honda", "Daihatsu", "Mitsubishi", "Suzuki",
        "Hyundai", "Wuling", "Nissan", "Isuzu", "Mercedes-Benz",
        "BMW", "Mazda", "Kia", "BYD", "Chery"
    ];

    function showSuggestions() {
        const val = brandInput.value.trim().toLowerCase();
        listContainer.innerHTML = '';
        currentFocus = -1;

        if (val.length < 1) {
            listContainer.classList.add('hidden');
            return;
        }

        const matches = availableBrands.filter(brand =>
            brand.toLowerCase().includes(val)
        );

        if (matches.length === 0) {
            listContainer.classList.add('hidden');
            return;
        }

        const limitedMatches = matches.slice(0, 5);

        limitedMatches.forEach(brand => {
            const item = document.createElement('div');
            item.className = 'px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-primary-600/10 hover:text-primary cursor-pointer transition-colors border-b border-slate-100 dark:border-slate-800 last:border-none';

            const regex = new RegExp(`(${val})`, 'gi');
            item.innerHTML = brand.replace(regex, '<strong class="text-primary font-bold">$1</strong>');

            item.addEventListener('click', function () {
                brandInput.value = brand;
                listContainer.classList.add('hidden');
            });

            listContainer.appendChild(item);
        });

        listContainer.classList.remove('hidden');
    }

    brandInput.addEventListener('input', showSuggestions);

    brandInput.addEventListener('keydown', function (e) {
        const items = listContainer.getElementsByTagName('div');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            if (currentFocus > -1 && items[currentFocus]) {
                e.preventDefault();
                items[currentFocus].click();
            }
        } else if (e.key === 'Escape') {
            listContainer.classList.add('hidden');
        }
    });

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;

        items[currentFocus].classList.add('bg-primary-600/20', 'text-primary', 'font-bold');
        items[currentFocus].scrollIntoView({ block: 'nearest' });
    }

    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('bg-primary-600/20', 'text-primary', 'font-bold');
        }
    }

    document.addEventListener('click', function (e) {
        if (e.target !== brandInput && e.target !== listContainer) {
            listContainer.classList.add('hidden');
        }
    });
}

// -------------------------------------------------------------
// Autocomplete Rent Partner / Mitra
// -------------------------------------------------------------
function setupPartnerAutocomplete() {
    const partnerInput = document.getElementById('partner_name_input');
    const partnerIdInput = document.getElementById('partner_id');
    const listContainer = document.getElementById('partner-autocomplete-list');
    let currentFocus = -1;

    if (!partnerInput || !listContainer) return;

    const availablePartners = window.availablePartners || [];

    function showSuggestions() {
        const val = partnerInput.value.trim().toLowerCase();
        listContainer.innerHTML = '';
        currentFocus = -1;

        if (val.length < 1) {
            listContainer.classList.add('hidden');
            if (partnerIdInput) partnerIdInput.value = '';
            return;
        }

        const matches = availablePartners.filter(p =>
            p.name.toLowerCase().includes(val) || (p.company && p.company.toLowerCase().includes(val))
        );

        if (matches.length === 0) {
            listContainer.classList.add('hidden');
            return;
        }

        const limitedMatches = matches.slice(0, 5);

        limitedMatches.forEach(p => {
            const item = document.createElement('div');
            item.className = 'px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-primary-600/10 hover:text-primary cursor-pointer transition-colors border-b border-slate-100 dark:border-slate-800 last:border-none';

            const regex = new RegExp(`(${val})`, 'gi');
            const highlightedName = p.name.replace(regex, '<strong class="text-primary font-bold">$1</strong>');
            const companyText = p.company ? ` <span class="text-xs text-slate-400">(${p.company})</span>` : '';

            item.innerHTML = highlightedName + companyText;

            item.addEventListener('click', function () {
                partnerInput.value = p.name;
                if (partnerIdInput) partnerIdInput.value = p.id;
                listContainer.classList.add('hidden');
            });

            listContainer.appendChild(item);
        });

        listContainer.classList.remove('hidden');
    }

    partnerInput.addEventListener('input', showSuggestions);

    partnerInput.addEventListener('keydown', function (e) {
        const items = listContainer.getElementsByTagName('div');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            if (currentFocus > -1 && items[currentFocus]) {
                e.preventDefault();
                items[currentFocus].click();
            }
        } else if (e.key === 'Escape') {
            listContainer.classList.add('hidden');
        }
    });

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;

        items[currentFocus].classList.add('bg-primary-600/20', 'text-primary', 'font-bold');
        items[currentFocus].scrollIntoView({ block: 'nearest' });
    }

    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('bg-primary-600/20', 'text-primary', 'font-bold');
        }
    }

    document.addEventListener('click', function (e) {
        if (e.target !== partnerInput && e.target !== listContainer) {
            listContainer.classList.add('hidden');
        }
    });
}
