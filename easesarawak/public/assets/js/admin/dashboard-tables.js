/**
 * Dashboard pending orders / transactions: filters, sorting, pagination, CSV export.
 */
(function () {
    function downloadCsv(filename, rows) {
        var BOM = '\uFEFF';
        var csv =
            BOM +
            rows
                .map(function (r) {
                    return r
                        .map(function (c) {
                            return '"' + String(c == null ? '' : c).replace(/"/g, '""') + '"';
                        })
                        .join(',');
                })
                .join('\r\n');
        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.rel = 'noopener';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function initPendingOrders() {
        var tbody = document.getElementById('pendingOrdersBody');
        if (!tbody) {
            return;
        }

        var filterSvc = document.getElementById('pendingFilterService');
        var sortSel = document.getElementById('pendingSortOrder');
        var viewMoreBtn = document.getElementById('viewMoreBtn');
        var showLessBtn = document.getElementById('showLessBtn');
        var viewMoreWrap = document.getElementById('viewMoreContainer');
        var batchSize = 10;
        var visibleCount = batchSize;

        function matchingRows() {
            return Array.prototype.slice.call(tbody.querySelectorAll('.pending-order-row')).filter(function (r) {
                return !r.classList.contains('dsh-filter-hide');
            });
        }

        function applyPendingPagination() {
            var visible = matchingRows();
            visible.forEach(function (r, i) {
                r.classList.toggle('d-none', i >= visibleCount);
            });
            if (viewMoreBtn && showLessBtn) {
                var len = visible.length;
                viewMoreBtn.classList.toggle('d-none', visibleCount >= len || len <= batchSize);
                showLessBtn.classList.toggle('d-none', visibleCount <= batchSize);
                if (viewMoreWrap) {
                    viewMoreWrap.classList.toggle('d-none', len <= batchSize);
                }
            }
        }

        function applyFilterSort() {
            var svcVal = filterSvc ? filterSvc.value : '';
            var sortVal = sortSel ? sortSel.value : 'created-desc';
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('.pending-order-row'));

            rows.forEach(function (row) {
                var svc = (row.dataset.service || '').toLowerCase();
                var match = !svcVal || svc === svcVal;
                row.classList.toggle('dsh-filter-hide', !match);
            });

            var visible = rows.filter(function (r) {
                return !r.classList.contains('dsh-filter-hide');
            });
            visible.sort(function (a, b) {
                var ta = parseInt(a.dataset.created, 10) || 0;
                var tb = parseInt(b.dataset.created, 10) || 0;
                return sortVal === 'created-asc' ? ta - tb : tb - ta;
            });

            var frag = document.createDocumentFragment();
            visible.forEach(function (r) {
                frag.appendChild(r);
            });
            rows
                .filter(function (r) {
                    return r.classList.contains('dsh-filter-hide');
                })
                .forEach(function (r) {
                    frag.appendChild(r);
                });
            tbody.appendChild(frag);

            visibleCount = Math.min(batchSize, visible.length);
            applyPendingPagination();
        }

        if (filterSvc) {
            filterSvc.addEventListener('change', applyFilterSort);
        }
        if (sortSel) {
            sortSel.addEventListener('change', applyFilterSort);
        }

        if (viewMoreBtn && showLessBtn) {
            viewMoreBtn.addEventListener('click', function () {
                var visible = matchingRows();
                visibleCount = Math.min(visibleCount + batchSize, visible.length);
                applyPendingPagination();
            });
            showLessBtn.addEventListener('click', function () {
                visibleCount = batchSize;
                applyPendingPagination();
            });
        }

        if (filterSvc || sortSel) {
            applyFilterSort();
        } else {
            applyPendingPagination();
        }

        var exportBtn = document.getElementById('pendingExportCsv');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                var rows = matchingRows();
                var data = [['Order ID', 'Customer', 'Order Date', 'Service', 'Status']];
                rows.forEach(function (tr) {
                    var id = tr.dataset.orderId || '';
                    var tds = tr.querySelectorAll('td');
                    var cust = tds[0] ? tds[0].textContent.trim().replace(/\s+/g, ' ') : '';
                    var dt = tds[1] ? tds[1].textContent.trim() : '';
                    var svc = tds[2] ? tds[2].textContent.trim() : '';
                    var st = tds[3] ? tds[3].textContent.trim().replace(/\s+/g, ' ') : '';
                    data.push([id, cust, dt, svc, st]);
                });
                downloadCsv('ease-dashboard-pending-orders.csv', data);
            });
        }
    }

    function initCustomers() {
        var list = document.getElementById('dshCustomerList');
        var sortSel = document.getElementById('customerSortOrder');
        if (!list || !sortSel) {
            return;
        }

        function sortCustomers() {
            var desc = sortSel.value === 'created-desc';
            var rows = Array.prototype.slice.call(list.querySelectorAll('.dsh-customer-row'));
            rows.sort(function (a, b) {
                var ta = parseInt(a.dataset.created, 10) || 0;
                var tb = parseInt(b.dataset.created, 10) || 0;
                return desc ? tb - ta : ta - tb;
            });
            rows.forEach(function (r) {
                list.appendChild(r);
            });
        }

        sortSel.addEventListener('change', sortCustomers);
    }

    function initTransactions() {
        var tbody = document.getElementById('dshTxnBody');
        if (!tbody) {
            return;
        }

        var filterSt = document.getElementById('txnFilterStatus');
        var sortSel = document.getElementById('txnSortOrder');
        var viewMoreBtn = document.getElementById('transactionViewMoreBtn');
        var showLessBtn = document.getElementById('transactionShowLessBtn');
        var viewMoreWrap = document.getElementById('transactionViewMoreContainer');
        var batchSize = 10;
        var visibleCount = batchSize;

        function matchingRows() {
            return Array.prototype.slice.call(tbody.querySelectorAll('.transaction-row')).filter(function (r) {
                return !r.classList.contains('dsh-filter-hide');
            });
        }

        function applyTxnPagination() {
            var visible = matchingRows();
            visible.forEach(function (r, i) {
                r.classList.toggle('d-none', i >= visibleCount);
            });
            if (viewMoreBtn && showLessBtn) {
                var len = visible.length;
                viewMoreBtn.classList.toggle('d-none', visibleCount >= len || len <= batchSize);
                showLessBtn.classList.toggle('d-none', visibleCount <= batchSize);
                if (viewMoreWrap) {
                    viewMoreWrap.classList.toggle('d-none', len <= batchSize);
                }
            }
        }

        function applyFilterSort() {
            if (!filterSt || !sortSel) {
                applyTxnPagination();
                return;
            }

            var stVal = filterSt.value;
            var sortVal = sortSel.value;
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('.transaction-row'));

            rows.forEach(function (row) {
                var st = (row.dataset.status || '').toLowerCase();
                var match = !stVal || st === stVal;
                row.classList.toggle('dsh-filter-hide', !match);
            });

            var visible = rows.filter(function (r) {
                return !r.classList.contains('dsh-filter-hide');
            });

            visible.sort(function (a, b) {
                if (sortVal === 'amount-desc' || sortVal === 'amount-asc') {
                    var aa = parseInt(a.dataset.amount, 10) || 0;
                    var bb = parseInt(b.dataset.amount, 10) || 0;
                    return sortVal === 'amount-desc' ? bb - aa : aa - bb;
                }
                var ta = parseInt(a.dataset.created, 10) || 0;
                var tb = parseInt(b.dataset.created, 10) || 0;
                return sortVal === 'created-asc' ? ta - tb : tb - ta;
            });

            var frag = document.createDocumentFragment();
            visible.forEach(function (r) {
                frag.appendChild(r);
            });
            rows
                .filter(function (r) {
                    return r.classList.contains('dsh-filter-hide');
                })
                .forEach(function (r) {
                    frag.appendChild(r);
                });
            tbody.appendChild(frag);

            visibleCount = Math.min(batchSize, visible.length);
            applyTxnPagination();
        }

        if (filterSt) {
            filterSt.addEventListener('change', applyFilterSort);
        }
        if (sortSel) {
            sortSel.addEventListener('change', applyFilterSort);
        }

        if (viewMoreBtn && showLessBtn) {
            viewMoreBtn.addEventListener('click', function () {
                var visible = matchingRows();
                visibleCount = Math.min(visibleCount + batchSize, visible.length);
                applyTxnPagination();
            });
            showLessBtn.addEventListener('click', function () {
                visibleCount = batchSize;
                applyTxnPagination();
            });
        }

        if (filterSt && sortSel) {
            applyFilterSort();
        } else {
            applyTxnPagination();
        }

        var exportBtn = document.getElementById('transactionExportCsv');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                var rows = matchingRows();
                var data = [['Payment ref', 'Date & Time', 'Amount', 'Status']];
                rows.forEach(function (tr) {
                    var th = tr.querySelector('th');
                    var tds = tr.querySelectorAll('td');
                    var ref = th ? th.textContent.trim().replace(/\s+/g, ' ') : '';
                    var dt = tds[0] ? tds[0].textContent.trim() : '';
                    var amt = tds[1] ? tds[1].textContent.trim() : '';
                    var st = tds[2] ? tds[2].textContent.trim().replace(/\s+/g, ' ') : '';
                    data.push([ref, dt, amt, st]);
                });
                downloadCsv('ease-dashboard-transactions.csv', data);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initPendingOrders();
        initCustomers();
        initTransactions();
    });
})();
