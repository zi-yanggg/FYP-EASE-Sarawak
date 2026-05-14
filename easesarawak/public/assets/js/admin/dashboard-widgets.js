/**
 * Dashboard widget layout: reorder sections (drag) + show/hide KPIs and sections.
 * Persisted per browser via localStorage.
 */
(function () {
    var STORAGE_KEY = 'ease_dashboard_layout_v2';
    var DEFAULT = {
        // Default order matches the 5 visible top-level reorder items in the
        // dashboard.php markup. Two hidden placeholders ('messages',
        // 'service-mix') exist so older v1 layouts still resolve cleanly
        // when their data-dsh-widget IDs were stored.
        order: ['kpis', 'pending', 'customers', 'transactions', 'activity'],
        hiddenKpis: [],
        hiddenSections: [],
    };

    function loadState() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) {
                return JSON.parse(JSON.stringify(DEFAULT));
            }
            var parsed = JSON.parse(raw);
            return {
                order: Array.isArray(parsed.order) ? parsed.order : DEFAULT.order.slice(),
                hiddenKpis: Array.isArray(parsed.hiddenKpis) ? parsed.hiddenKpis : [],
                hiddenSections: Array.isArray(parsed.hiddenSections) ? parsed.hiddenSections : [],
            };
        } catch (e) {
            return JSON.parse(JSON.stringify(DEFAULT));
        }
    }

    function saveState(state) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        } catch (e) {
            /* ignore quota */
        }
    }

    function applyOrder(root, state) {
        var items = Array.prototype.slice.call(root.querySelectorAll('.dsh-reorder-item'));
        var map = new Map();
        items.forEach(function (el) {
            map.set(el.getAttribute('data-dsh-widget'), el);
        });
        var frag = document.createDocumentFragment();
        var seen = new Set();
        state.order.forEach(function (id) {
            var el = map.get(id);
            if (el) {
                frag.appendChild(el);
                seen.add(id);
            }
        });
        items.forEach(function (el) {
            var id = el.getAttribute('data-dsh-widget');
            if (!seen.has(id)) {
                frag.appendChild(el);
            }
        });
        root.appendChild(frag);
    }

    function applyVisibility(root, state) {
        root.querySelectorAll('.dsh-kpi-slot[data-dsh-kpi]').forEach(function (el) {
            var id = el.getAttribute('data-dsh-kpi');
            el.classList.toggle('dsh-widget-hidden', state.hiddenKpis.indexOf(id) !== -1);
        });
        root.querySelectorAll('.dsh-reorder-item[data-dsh-widget]').forEach(function (el) {
            var id = el.getAttribute('data-dsh-widget');
            if (id === 'kpis') {
                return;
            }
            el.classList.toggle('dsh-widget-hidden', state.hiddenSections.indexOf(id) !== -1);
        });
        // Sub-widgets that live inside another reorder-item (Service Mix
        // inside `pending`, Messages inside `activity`). They opt-in via
        // `data-dsh-side="<name>"` so the same hiddenSections flag toggles
        // them without the customize checkbox needing extra wiring.
        root.querySelectorAll('[data-dsh-side]').forEach(function (el) {
            var id = el.getAttribute('data-dsh-side');
            el.classList.toggle('dsh-widget-hidden', state.hiddenSections.indexOf(id) !== -1);
        });
        syncCheckboxes(state);
    }

    function syncCheckboxes(state) {
        document.querySelectorAll('[data-dsh-toggle-kpi]').forEach(function (cb) {
            var id = cb.getAttribute('data-dsh-toggle-kpi');
            cb.checked = state.hiddenKpis.indexOf(id) === -1;
        });
        document.querySelectorAll('[data-dsh-toggle-section]').forEach(function (cb) {
            var id = cb.getAttribute('data-dsh-toggle-section');
            cb.checked = state.hiddenSections.indexOf(id) === -1;
        });
    }

    function readCheckboxesIntoState(state) {
        state.hiddenKpis = Array.prototype.slice
            .call(document.querySelectorAll('[data-dsh-toggle-kpi]'))
            .filter(function (cb) {
                return !cb.checked;
            })
            .map(function (cb) {
                return cb.getAttribute('data-dsh-toggle-kpi');
            });
        state.hiddenSections = Array.prototype.slice
            .call(document.querySelectorAll('[data-dsh-toggle-section]'))
            .filter(function (cb) {
                return !cb.checked;
            })
            .map(function (cb) {
                return cb.getAttribute('data-dsh-toggle-section');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.getElementById('dshReorderRoot');
        var page = document.querySelector('.dsh-page');
        var toggleBtn = document.getElementById('dshToggleCustomize');
        var resetBtn = document.getElementById('dshResetLayout');
        var panel = document.getElementById('dshVisibilityPanel');
        var hint = document.getElementById('dshCustomizeHint');

        if (!root || !page || !toggleBtn || !panel) {
            return;
        }

        var state = loadState();
        applyOrder(root, state);
        applyVisibility(root, state);

        var sortableInst = null;

        function setSortableEnabled(on) {
            if (!window.Sortable) {
                return;
            }
            if (!sortableInst) {
                sortableInst = window.Sortable.create(root, {
                    animation: 150,
                    handle: '.dsh-drag-handle',
                    draggable: '.dsh-reorder-item',
                    disabled: !on,
                    onEnd: function () {
                        var order = Array.prototype.map.call(root.querySelectorAll('.dsh-reorder-item'), function (el) {
                            return el.getAttribute('data-dsh-widget');
                        });
                        state.order = order;
                        saveState(state);
                    },
                });
            } else {
                sortableInst.option('disabled', !on);
            }
        }

        function setEditMode(on) {
            page.classList.toggle('dsh-layout-edit', on);
            toggleBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
            panel.classList.toggle('d-none', !on);
            if (hint) {
                hint.classList.toggle('d-none', !on);
            }
            if (resetBtn) {
                resetBtn.classList.toggle('d-none', !on);
            }
            setSortableEnabled(on);
        }

        toggleBtn.addEventListener('click', function () {
            var on = !page.classList.contains('dsh-layout-edit');
            setEditMode(on);
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                try {
                    localStorage.removeItem(STORAGE_KEY);
                } catch (e) {
                    /* ignore */
                }
                window.location.reload();
            });
        }

        document.querySelectorAll('[data-dsh-toggle-kpi], [data-dsh-toggle-section]').forEach(function (cb) {
            cb.addEventListener('change', function () {
                readCheckboxesIntoState(state);
                saveState(state);
                applyVisibility(root, state);
            });
        });

        setSortableEnabled(false);
    });
})();
