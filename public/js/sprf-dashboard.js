(function () {
    const SPRF = window.SPRF_DASHBOARD || {};
    const AVAILABLE_MONTHS = Array.isArray(SPRF.availableMonths) ? SPRF.availableMonths : [];
    window._sprfAvailMonths = AVAILABLE_MONTHS;

    const SALES_LABELS = Array.isArray(SPRF.salesLabels) ? SPRF.salesLabels : [];
    const SALES_AMOUNT_DATA = Array.isArray(SPRF.salesAmountData) ? SPRF.salesAmountData : [];
    const ORDERS_DATA = Array.isArray(SPRF.ordersData) ? SPRF.ordersData : [];
    const PRODUCT_LABELS = Array.isArray(SPRF.productLabels) ? SPRF.productLabels : [];
    const PRODUCT_PERCENTAGES = Array.isArray(SPRF.productPercentages) ? SPRF.productPercentages : [];
    const PRODUCT_COLORS = Array.isArray(SPRF.productColors) ? SPRF.productColors : [];
    const FORECAST_LABELS = Array.isArray(SPRF.forecastLabels) ? SPRF.forecastLabels : [];
    const FORECAST_TARGET_AMOUNTS = Array.isArray(SPRF.forecastTargets) ? SPRF.forecastTargets : [];
    const FORECAST_ACTUAL_AMOUNTS = Array.isArray(SPRF.forecastActuals) ? SPRF.forecastActuals : [];

    function init() {
        initSectionToggles();
        initDataFilters();
        initCalendarInputs();
        initChartWidgets();
    }

    function positionPanel() {
        const trigger = document.getElementById('sprf-dash-filter-trigger');
        const panel = document.getElementById('sprf-dash-filter-panel');
        if (!trigger || !panel) return;
        const rect = trigger.getBoundingClientRect();
        panel.style.top = (rect.bottom + 8) + 'px';
        panel.style.right = (window.innerWidth - rect.right) + 'px';
        panel.style.left = 'auto';
    }

    window.toggleSprfDashFilter = function () {
        const panel = document.getElementById('sprf-dash-filter-panel');
        const overlay = document.getElementById('sprf-dash-filter-overlay');
        if (!panel || !overlay) return;

        if (panel.classList.contains('hidden')) {
            positionPanel();
            panel.classList.remove('hidden');
            overlay.classList.remove('hidden');
        } else {
            closeSprfDashFilter();
        }
    };

    window.closeSprfDashFilter = function () {
        const panel = document.getElementById('sprf-dash-filter-panel');
        const overlay = document.getElementById('sprf-dash-filter-overlay');
        if (panel) panel.classList.add('hidden');
        if (overlay) overlay.classList.add('hidden');
    };

    window.switchSprfFilterTab = function (tab) {
        ['sections', 'data'].forEach(function (t) {
            const tabPanel = document.getElementById('sprf-filter-tab-' + t);
            const tabBtn = document.getElementById('sprf-tab-btn-' + t);
            if (!tabPanel || !tabBtn) return;
            const isActive = (t === tab);
            tabPanel.classList.toggle('hidden', !isActive);

            if (isActive) {
                tabBtn.classList.add('text-green-800', 'border-green-700');
                tabBtn.classList.remove('text-gray-500', 'border-transparent');
            } else {
                tabBtn.classList.remove('text-green-800', 'border-green-700');
                tabBtn.classList.add('text-gray-500', 'border-transparent');
            }
        });
    };

    function initSectionToggles() {
        document.querySelectorAll('.sprf-section-toggle').forEach(function (cb) {
            cb.addEventListener('change', function () {
                const sec = document.getElementById(this.dataset.section);
                if (sec) sec.style.display = this.checked ? '' : 'none';
                updateBadge();
            });
        });
    }

    function applyDataFilters() {
        const rep = document.getElementById('sprf-data-rep')?.value || '';
        const region = document.getElementById('sprf-data-region')?.value || '';
        const product = document.getElementById('sprf-data-product')?.value || '';

        document.querySelectorAll('tr[data-rep]').forEach(function (row) {
            row.style.display = (!rep || row.dataset.rep === rep) ? '' : 'none';
        });

        document.querySelectorAll('tr[data-region]').forEach(function (row) {
            row.style.display = (!region || row.dataset.region === region) ? '' : 'none';
        });

        document.querySelectorAll('.sprf-product-item[data-product]').forEach(function (item) {
            const match = !product || item.dataset.product === product;
            item.style.opacity = match ? '1' : '0.2';
            item.style.transition = 'opacity 0.2s';
        });

        updateBadge();
    }

    function initDataFilters() {
        ['sprf-data-rep', 'sprf-data-region', 'sprf-data-product'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', applyDataFilters);
        });
    }

    function updateBadge() {
        let count = 0;
        document.querySelectorAll('.sprf-section-toggle').forEach(function (cb) {
            if (!cb.checked) count++;
        });

        ['sprf-data-rep', 'sprf-data-region', 'sprf-data-product'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el && el.value) count++;
        });

        const badge = document.getElementById('sprf-dash-filter-badge');
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    }

    window.resetSprfDashFilters = function () {
        document.querySelectorAll('.sprf-section-toggle').forEach(function (cb) {
            cb.checked = true;
            const sec = document.getElementById(cb.dataset.section);
            if (sec) sec.style.display = '';
        });

        ['sprf-data-rep', 'sprf-data-region', 'sprf-data-product'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        document.querySelectorAll('tr[data-rep], tr[data-region]').forEach(function (row) {
            row.style.display = '';
        });

        document.querySelectorAll('.sprf-product-item[data-product]').forEach(function (item) {
            item.style.opacity = '1';
        });

        updateBadge();
    };

    window.downloadCSV = function (tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) return;
        const rows = Array.from(table.querySelectorAll('tr'));
        const csv = rows.map(row => {
            const cols = Array.from(row.querySelectorAll('td, th'));
            return cols.map(cell => {
                let data = cell.innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s+)/gm, ' ');
                data = data.replace(/"/g, '""');
                return '"' + data + '"';
            }).join(',');
        }).join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    };

    function getState(popupId) {
        if (!window._sprfCalendarState) {
            window._sprfCalendarState = {};
        }
        if (!window._sprfCalendarState[popupId]) {
            const now = new Date();
            window._sprfCalendarState[popupId] = {
                view: 'month',
                cursor: { year: now.getFullYear(), month: now.getMonth() },
                startDate: null,
                endDate: null,
                hoveredDate: null,
            };
        }
        return window._sprfCalendarState[popupId];
    }

    function fmtDate(d) {
        return MONTHS_SHORT[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    function fmtRange(s, e) {
        if (!s) return 'No range selected';
        if (!e) return fmtDate(s) + ' — pick end date';
        return fmtDate(s) + ' - ' + fmtDate(e);
    }

    function parseRange(str) {
        const re = /^([A-Za-z]+)\s+(\d{1,2})(?:,\s*(\d{4}))?\s*-\s*([A-Za-z]+)\s+(\d{1,2}),\s*(\d{4})$/;
        const m = str.trim().match(re);
        if (!m) return null;
        const startYear = m[3] ? parseInt(m[3]) : parseInt(m[6]);
        const sm = MONTHS_SHORT.findIndex(x => x.toLowerCase() === m[1].slice(0, 3).toLowerCase());
        const em = MONTHS_SHORT.findIndex(x => x.toLowerCase() === m[4].slice(0, 3).toLowerCase());
        if (sm < 0 || em < 0) return null;
        const s = new Date(startYear, sm, parseInt(m[2]));
        const e = new Date(parseInt(m[6]), em, parseInt(m[5]));
        if (isNaN(s) || isNaN(e)) return null;
        return { start: s, end: e };
    }

    function sameDay(a, b) {
        return a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    }

    function isBetween(d, s, e) {
        return s && e && d > s && d < e;
    }

    function updateSelectionLabel(popupId) {
        const st = getState(popupId);
        const labelEl = document.getElementById(popupId === 'sprf-cal-popup' ? 'sprf-cal-selection-label' : 'deals-cal-selection-label');
        if (labelEl) labelEl.textContent = fmtRange(st.startDate, st.endDate);
        const applyBtn = document.querySelector('#' + popupId + ' [id$="-cal-apply"]');
        if (applyBtn) applyBtn.disabled = !(st.startDate && st.endDate);
    }

    function renderGrid(popupId) {
        const st = getState(popupId);
        const gridId = popupId === 'sprf-cal-popup' ? 'sprf-cal-grid' : 'deals-cal-grid';
        const grid = document.getElementById(gridId);
        if (!grid) return;

        document.querySelectorAll('#' + popupId + ' .cal-view-btn').forEach(btn => {
            const v = btn.getAttribute('data-view');
            if (v && v === st.view) {
                btn.className = 'cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold bg-green-800 text-white';
            } else {
                btn.className = 'cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold border border-[#e3ddc9] text-gray-600 hover:bg-green-50 hover:text-green-800 transition';
            }
        });

        if (st.view === 'month') renderMonthGrid(popupId, grid);
        else if (st.view === 'quarter') renderQuarterGrid(popupId, grid);
        else renderYearGrid(popupId, grid);
    }

    function renderMonthGrid(popupId, grid) {
        const st = getState(popupId);
        const { year, month } = st.cursor;
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const _ym = year + '-' + String(month + 1).padStart(2, '0');
        const _hasData = !AVAILABLE_MONTHS.length || AVAILABLE_MONTHS.includes(_ym);

        let html = `<div class="cal-nav-row">
                <button class="cal-nav-btn" data-nav="-1">&#8249;</button>
                <span class="cal-nav-title">${MONTHS[month]} ${year}</span>
                <button class="cal-nav-btn" data-nav="1">&#8250;</button>
            </div>
            ${AVAILABLE_MONTHS.length ? `<div class="text-center pb-2 -mt-1"><span class="inline-flex items-center gap-1 text-[9px] font-bold ${_hasData ? 'text-green-600' : 'text-amber-500'}">${_hasData ? '● Data available' : '◌ No data for this period'}</span></div>` : ''}
            <div class="cal-grid-month">`;

        DAYS.forEach(d => { html += `<div class="cal-day-head">${d}</div>`; });
        for (let i = 0; i < firstDay; i++) html += '<div></div>';

        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(year, month, d);
            const isStart = sameDay(date, st.startDate);
            const isEnd = sameDay(date, st.endDate);
            const inRange = isBetween(date, st.startDate, st.endDate);
            const inHoverRange = !st.endDate && st.startDate && date > st.startDate && st.hoveredDate && date < st.hoveredDate;
            const isHoverEnd = !st.endDate && st.startDate && sameDay(date, st.hoveredDate) && date > st.startDate;

            let wrapClass = 'cal-day-wrap' + ((inRange || inHoverRange) ? ' in-range' : '');
            let dayClass = 'cal-day';
            if (isStart || isEnd) dayClass += ' is-selected';
            else if (inRange || inHoverRange) dayClass += ' in-range';
            if (isHoverEnd) dayClass += ' hover-end';

            html += `<div class="${wrapClass}" data-y="${year}" data-m="${month}" data-d="${d}">
                        <div class="${dayClass}" data-pick="day">${d}</div>
                     </div>`;
        }
        html += '</div>';
        grid.innerHTML = html;

        grid.querySelectorAll('.cal-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => calNav(popupId, parseInt(btn.dataset.nav, 10)));
        });

        grid.addEventListener('click', function handler(e) {
            const target = e.target.closest('[data-pick="day"]');
            if (!target) return;
            const wrap = target.closest('.cal-day-wrap');
            if (wrap) calPickDay(popupId, parseInt(wrap.dataset.y, 10), parseInt(wrap.dataset.m, 10), parseInt(wrap.dataset.d, 10));
        });

        grid.addEventListener('mouseover', function (e) {
            const wrap = e.target.closest('.cal-day-wrap');
            if (!wrap) return;
            calHover(popupId, parseInt(wrap.dataset.y, 10), parseInt(wrap.dataset.m, 10), parseInt(wrap.dataset.d, 10));
        });

        grid.addEventListener('mouseleave', function () { calLeave(popupId); });
    }

    function renderQuarterGrid(popupId, grid) {
        const st = getState(popupId);
        const year = st.cursor.year;
        const quarters = [
            { label: 'Q1', months: [0, 1, 2] },
            { label: 'Q2', months: [3, 4, 5] },
            { label: 'Q3', months: [6, 7, 8] },
            { label: 'Q4', months: [9, 10, 11] },
        ];
        let html = `<div class="cal-nav-row">
                <button class="cal-nav-btn" data-nav-year="-1">&#8249;</button>
                <span class="cal-nav-title">${year}</span>
                <button class="cal-nav-btn" data-nav-year="1">&#8250;</button>
            </div>
            <div class="cal-quarter-grid">`;

        quarters.forEach(q => {
            const s = new Date(year, q.months[0], 1);
            const e = new Date(year, q.months[2] + 1, 0);
            const isActive = st.startDate && st.endDate && sameDay(s, st.startDate) && sameDay(e, st.endDate);
            const _qHasData = !AVAILABLE_MONTHS.length || q.months.some(m => AVAILABLE_MONTHS.includes(year + '-' + String(m + 1).padStart(2, '0')));
            html += `<button class="cal-quarter-btn${isActive ? ' is-selected' : ''}${AVAILABLE_MONTHS.length && !_qHasData ? ' opacity-40' : ''}" data-qsy="${year}" data-qsm="${q.months[0]}" data-qey="${year}" data-qem="${q.months[2] + 1}">
                        ${q.label}<span class="cal-quarter-sub">${MONTHS_SHORT[q.months[0]]}&ndash;${MONTHS_SHORT[q.months[2]]}</span>
                        ${AVAILABLE_MONTHS.length ? `<span style="display:block;font-size:8px;margin-top:2px;color:${_qHasData ? '#15803d' : '#f59e0b'}">${_qHasData ? '● data' : 'no data'}</span>` : ''}
                     </button>`;
        });
        html += '</div>';
        grid.innerHTML = html;

        grid.querySelectorAll('.cal-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => calNavYear(popupId, parseInt(btn.dataset.navYear, 10)));
        });
        grid.querySelectorAll('.cal-quarter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                calPickRange(popupId, parseInt(btn.dataset.qsy, 10), parseInt(btn.dataset.qsm, 10), 1, parseInt(btn.dataset.qey, 10), parseInt(btn.dataset.qem, 10), 0);
            });
        });
    }

    function renderYearGrid(popupId, grid) {
        const st = getState(popupId);
        const currentYear = new Date().getFullYear();
        let html = '<div class="cal-year-grid">';
        for (let y = currentYear - 2; y <= currentYear + 1; y++) {
            const s = new Date(y, 0, 1);
            const e = new Date(y, 11, 31);
            const isActive = st.startDate && st.endDate && sameDay(s, st.startDate) && sameDay(e, st.endDate);
            const _yHasData = !AVAILABLE_MONTHS.length || AVAILABLE_MONTHS.some(m => m.startsWith(y + '-'));
            html += `<button class="cal-year-btn${isActive ? ' is-selected' : ''}${AVAILABLE_MONTHS.length && !_yHasData ? ' opacity-40' : ''}" data-y="${y}">${y}${AVAILABLE_MONTHS.length ? `<span style="display:block;font-size:8px;color:${_yHasData ? '#15803d' : '#f59e0b'}">${_yHasData ? '●' : '◌'}</span>` : ''}</button>`;
        }
        html += '</div>';
        grid.innerHTML = html;

        grid.querySelectorAll('.cal-year-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const y = parseInt(btn.dataset.y, 10);
                calPickRange(popupId, y, 0, 1, y, 11, 31);
            });
        });
    }

    window.openCalendarPopup = function (popupId, currentRange) {
        const popup = document.getElementById(popupId);
        if (!popup) return;
        const st = getState(popupId);
        if (currentRange) {
            const parsed = parseRange(currentRange);
            if (parsed) {
                st.startDate = parsed.start;
                st.endDate = parsed.end;
                st.cursor = { year: parsed.start.getFullYear(), month: parsed.start.getMonth() };
            }
        }
        const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
        const input = document.getElementById(inputId);
        if (input && st.startDate && st.endDate) input.value = fmtRange(st.startDate, st.endDate);
        popup.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        renderGrid(popupId);
        updateSelectionLabel(popupId);
    };

    window.closeCalendarPopup = function (popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    window.calNav = function (popupId, dir) {
        const st = getState(popupId);
        let m = st.cursor.month + dir;
        let y = st.cursor.year;
        if (m < 0) { m = 11; y--; }
        if (m > 11) { m = 0; y++; }
        st.cursor = { year: y, month: m };
        renderGrid(popupId);
    };

    window.calNavYear = function (popupId, dir) {
        const st = getState(popupId);
        st.cursor.year += dir;
        renderGrid(popupId);
    };

    window.calHover = function (popupId, y, m, d) {
        const st = getState(popupId);
        if (st.startDate && !st.endDate) {
            st.hoveredDate = new Date(y, m, d);
            renderGrid(popupId);
        }
    };

    window.calLeave = function (popupId) {
        getState(popupId).hoveredDate = null;
        renderGrid(popupId);
    };

    window.calPickDay = function (popupId, y, m, d) {
        const st = getState(popupId);
        const picked = new Date(y, m, d);
        if (!st.startDate || (st.startDate && st.endDate)) {
            st.startDate = picked;
            st.endDate = null;
        } else {
            if (picked < st.startDate) {
                st.endDate = st.startDate;
                st.startDate = picked;
            } else {
                st.endDate = picked;
            }
        }
        const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
        const input = document.getElementById(inputId);
        if (input && st.startDate && st.endDate) input.value = fmtRange(st.startDate, st.endDate);
        updateSelectionLabel(popupId);
        renderGrid(popupId);
    };

    window.calPickRange = function (popupId, sy, sm, sd, ey, em, ed) {
        const st = getState(popupId);
        st.startDate = new Date(sy, sm, sd);
        st.endDate = new Date(ey, em, ed);
        const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
        const input = document.getElementById(inputId);
        if (input) input.value = fmtRange(st.startDate, st.endDate);
        updateSelectionLabel(popupId);
        renderGrid(popupId);
    };

    window.applyCalPreset = function (btn, popupId, preset) {
        const now = new Date();
        const y = now.getFullYear();
        const m = now.getMonth();
        let s, e;

        if (preset === 'thisMonth') {
            s = new Date(y, m, 1);
            e = new Date(y, m + 1, 0);
        } else if (preset === 'lastMonth') {
            s = new Date(y, m - 1, 1);
            e = new Date(y, m, 0);
        } else if (preset === 'thisQuarter') {
            const q = Math.floor(m / 3);
            s = new Date(y, q * 3, 1);
            e = new Date(y, q * 3 + 3, 0);
        } else if (preset === 'thisYear') {
            s = new Date(y, 0, 1);
            e = new Date(y, 11, 31);
        }

        const st = getState(popupId);
        st.startDate = s;
        st.endDate = e;
        st.cursor = { year: s.getFullYear(), month: s.getMonth() };

        document.querySelectorAll('#' + popupId + ' .cal-preset-btn').forEach(b => {
            b.className = 'cal-preset-btn px-3 py-1 rounded-full border text-[10px] font-bold transition ' +
                (b === btn ? 'bg-green-800 text-white border-green-800' : 'border-[#e3ddc9] text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800');
        });

        const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
        const input = document.getElementById(inputId);
        if (input) input.value = fmtRange(s, e);
        updateSelectionLabel(popupId);
        renderGrid(popupId);
    };

    window.setCalView = function (btn, popupId, view) {
        const st = getState(popupId);
        st.view = view;
        renderGrid(popupId);
    };

    window.onCalTextInput = function (input, popupId) {
        const errId = popupId === 'sprf-cal-popup' ? 'sprf-cal-input-err' : 'deals-cal-input-err';
        const errEl = document.getElementById(errId);
        const val = input.value.trim();
        const parsed = parseRange(val);
        if (parsed) {
            if (errEl) errEl.classList.add('hidden');
            input.classList.remove('border-red-400');
            const st = getState(popupId);
            st.startDate = parsed.start;
            st.endDate = parsed.end;
            st.cursor = { year: parsed.start.getFullYear(), month: parsed.start.getMonth() };
            updateSelectionLabel(popupId);
            renderGrid(popupId);
        } else if (val.length > 5) {
            if (errEl) errEl.classList.remove('hidden');
            input.classList.add('border-red-400');
        } else {
            if (errEl) errEl.classList.add('hidden');
            input.classList.remove('border-red-400');
        }
    };

    window.applyCalendarSelection = function (popupId) {
        const st = getState(popupId);
        if (!st.startDate || !st.endDate) return;
        const range = fmtRange(st.startDate, st.endDate);
        window.closeCalendarPopup(popupId);
        window.location.href = '?date_range=' + encodeURIComponent(range);
    };

    function initChartWidgets() {
        if (typeof Chart !== 'undefined') {
            const ctx1 = document.getElementById('salesPerformanceChart')?.getContext('2d');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: SALES_LABELS,
                        datasets: [
                            {
                                label: 'Sales',
                                type: 'line',
                                data: SALES_AMOUNT_DATA,
                                borderColor: '#3aa0c9',
                                backgroundColor: '#3aa0c9',
                                borderWidth: 2.5,
                                pointRadius: 4,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#3aa0c9',
                                pointBorderWidth: 2,
                                fill: false,
                                yAxisID: 'ySales'
                            },
                            {
                                label: 'Orders',
                                data: ORDERS_DATA,
                                backgroundColor: '#a3d9a5',
                                borderColor: '#3f8a4a',
                                borderWidth: 0,
                                borderRadius: 4,
                                yAxisID: 'yOrders',
                                barPercentage: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#8a8a7a' }
                            },
                            ySales: {
                                type: 'linear',
                                position: 'left',
                                grid: { color: '#f5f2e9' },
                                ticks: {
                                    callback: function (value) { return (value / 1000) + 'K'; },
                                    font: { size: 10, weight: 'bold' },
                                    color: '#8a8a7a'
                                }
                            },
                            yOrders: {
                                type: 'linear',
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#8a8a7a' }
                            }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('salesByProductChart')?.getContext('2d');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: PRODUCT_LABELS,
                        datasets: [{
                            data: PRODUCT_PERCENTAGES,
                            backgroundColor: PRODUCT_COLORS,
                            borderWidth: 3,
                            borderColor: '#fffefb'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: { legend: { display: false } }
                    }
                });
            }

            const ctx3 = document.getElementById('forecastChart')?.getContext('2d');
            if (ctx3) {
                new Chart(ctx3, {
                    type: 'bar',
                    data: {
                        labels: FORECAST_LABELS,
                        datasets: [
                            {
                                label: 'Target',
                                data: FORECAST_TARGET_AMOUNTS,
                                backgroundColor: '#555555',
                                borderRadius: 4,
                                barPercentage: 0.35,
                                categoryPercentage: 0.7
                            },
                            {
                                label: 'Actual',
                                data: FORECAST_ACTUAL_AMOUNTS,
                                backgroundColor: '#e08a3a',
                                borderRadius: 4,
                                barPercentage: 0.35,
                                categoryPercentage: 0.7
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#8a8a7a' } },
                            y: { grid: { color: '#f5f2e9' }, ticks: { font: { size: 10, weight: 'bold' }, color: '#8a8a7a' } }
                        }
                    }
                });
            }
        }
    }

    let sortDirections = {};

    window.sortSPRFTable = function (tableId, colIndex, type, headerEl) {
        const el = document.getElementById(tableId);
        if (!el) return;
        const tbody = el.tagName === 'TBODY' ? el : el.querySelector('tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.innerText.includes('No ongoing deals') && !r.innerText.includes('No past deals') && !r.innerText.includes('No recent deals'));
        if (!rows.length) return;

        const headerRow = headerEl.closest('tr');
        headerRow.querySelectorAll('th').forEach(th => {
            th.classList.remove('text-blue-600');
            th.classList.add('text-gray-950');
            const icon = th.querySelector('i');
            if (icon && !icon.classList.contains('fa-filter')) {
                icon.className = 'fas fa-sort text-[10px] text-gray-400';
            }
        });

        headerEl.classList.remove('text-gray-950');
        headerEl.classList.add('text-blue-600');

        const dirKey = tableId + '_' + colIndex;
        const isAsc = !sortDirections[dirKey];
        sortDirections[dirKey] = isAsc;

        const arrowIcon = headerEl.querySelector('i');
        if (arrowIcon) {
            arrowIcon.className = isAsc ? 'fas fa-sort-up text-[10px] text-blue-600' : 'fas fa-sort-down text-[10px] text-blue-600';
        }

        const parseVal = td => {
            if (!td) return '';
            let text = td.innerText.trim();
            if (type === 'currency') {
                return parseFloat(text.replace(/[^\d.-]/g, '')) || 0;
            }
            if (type === 'date') {
                return new Date(text) || new Date(0);
            }
            return text.toLowerCase();
        };

        rows.sort((a, b) => {
            const valA = parseVal(a.cells[colIndex]);
            const valB = parseVal(b.cells[colIndex]);
            if (valA < valB) return isAsc ? -1 : 1;
            if (valA > valB) return isAsc ? 1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    };

    window.toggleStageFilterMenu = function (tableId, headerEl, event) {
        event.stopPropagation();

        const existingMenu = document.getElementById('sprf-stage-menu');
        if (existingMenu) {
            existingMenu.remove();
            if (existingMenu.dataset.header === headerEl.outerHTML) return;
        }

        const el = document.getElementById(tableId);
        if (!el) return;
        const tbody = el.tagName === 'TBODY' ? el : el.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.innerText.includes('No ongoing deals') && !r.innerText.includes('No past deals') && !r.innerText.includes('No recent deals'));
        if (!rows.length) return;

        const stages = new Set();
        rows.forEach(r => {
            const stageCell = r.cells[2];
            if (stageCell) {
                const stageText = stageCell.innerText.trim();
                if (stageText) stages.add(stageText);
            }
        });

        const menu = document.createElement('div');
        menu.id = 'sprf-stage-menu';
        menu.className = 'absolute bg-white border border-[#eedcbe] rounded-xl shadow-xl p-3 z-50 flex flex-col gap-2 min-w-[140px] text-xs font-semibold text-gray-700';
        menu.dataset.header = headerEl.outerHTML;

        const rect = headerEl.getBoundingClientRect();
        menu.style.top = (rect.bottom + window.scrollY + 6) + 'px';
        menu.style.left = (rect.left + window.scrollX) + 'px';

        stages.forEach(st => {
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 cursor-pointer select-none py-1 hover:text-gray-950';
            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.value = st;
            cb.checked = true;
            cb.className = 'rounded text-green-700 focus:ring-green-700';

            const currentFilter = headerEl.dataset.filterStage;
            if (currentFilter) {
                const activeFilters = currentFilter.split(',');
                cb.checked = activeFilters.includes(st);
            }

            cb.addEventListener('change', () => {
                const checkedStages = Array.from(menu.querySelectorAll('input:checked')).map(i => i.value);
                headerEl.dataset.filterStage = checkedStages.join(',');

                if (checkedStages.length < stages.size) {
                    headerEl.classList.add('text-blue-600');
                    headerEl.querySelector('i').className = 'fas fa-filter text-[10px] text-blue-600';
                } else {
                    headerEl.classList.remove('text-blue-600');
                    const icon = headerEl.querySelector('i');
                    if (icon) icon.className = 'fas fa-filter text-[10px] text-gray-400';
                }

                rows.forEach(row => {
                    const rowStage = row.cells[2].innerText.trim();
                    row.style.display = checkedStages.includes(rowStage) ? '' : 'none';
                });
            });

            label.appendChild(cb);
            label.appendChild(document.createTextNode(' ' + st));
            menu.appendChild(label);
        });

        document.body.appendChild(menu);

        const clickOutside = e => {
            if (!menu.contains(e.target) && e.target !== headerEl) {
                menu.remove();
                document.removeEventListener('click', clickOutside);
            }
        };

        setTimeout(() => document.addEventListener('click', clickOutside), 10);
    };

    function initCalendarInputs() {
        const dateSelect = document.getElementById('sprf-date-range-select');
        if (dateSelect) {
            dateSelect.addEventListener('change', function () {
                window.location.href = '?date_range=' + encodeURIComponent(this.value);
            });
        }
    }

    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const DAYS = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
