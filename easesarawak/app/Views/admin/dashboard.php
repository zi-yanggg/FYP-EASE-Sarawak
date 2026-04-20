<?= $this->include('admin/header'); ?>

<div class="container">
    <div class="page-inner">
        <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">EASE Sarawak Admin Dashboard</h3>
            </div>
            <!-- <div class="ms-md-auto py-2 py-md-0">
                            <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                            <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                        </div> -->
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-visitor bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Visitors</p>
                                    <h4 class="card-title">1,294</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-admin bubble-shadow-small">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Admin</p>
                                    <h4 class="card-title"><?= esc($user_count); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-sales bubble-shadow-small">
                                    <i class="fas fa-luggage-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Sales</p>
                                    <h4 class="card-title">RM <?= esc($sales); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-order bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Order</p>
                                    <h4 class="card-title"><?= esc($orders); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                    <div class="card-title">Pending Orders</div>
                    <div class="card-tools">
                        <div class="dropdown">
                            <button class="btn btn-icon btn-clean me-0"
                                type="button"
                                id="pendingDropdownButton"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu"
                                aria-labelledby="pendingDropdownButton">
                                <a class="dropdown-item" href="#">Export</a>
                                <a class="dropdown-item" href="#">Mark all as viewed</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <?php if (!empty($pending_orders)): ?>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Service</th>
                                    <th scope="col" class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody id="pendingOrdersBody">
                                <?php foreach ($pending_orders as $index => $order): ?>
                                    <tr class="pending-order-row <?= $index >= 10 ? 'd-none' : ''; ?>"
                                        data-index="<?= $index; ?>">
                                        <th scope="row">
                                            <button class="btn btn-icon btn-round btn-warning btn-sm me-2">
                                                <i class="fa fa-clock"></i>
                                            </button>
                                            #<?= esc($order['order_id']); ?>
                                        </th>
                                        <td><?= esc($order['first_name']); ?> <?= esc($order['last_name']); ?></td>
                                        <td><?= date('M d, Y, g.i a', strtotime($order['created_date'])) ?></td>
                                        <td><?= strtoupper(esc($order['service_type'])); ?></td>
                                        <td class="text-end">
                                            <span class="badge badge-pending">Pending</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (count($pending_orders) > 10): ?>
                        <div class="text-center py-3" id="viewMoreContainer">
                            <button class="btn btn-sm btn-update me-2" id="viewMoreBtn">
                                View More
                            </button>
                            <button class="btn btn-sm btn-cancel d-none" id="showLessBtn">
                                Show Less
                            </button>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="text-muted p-3">No pending orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

        <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title">Users Geolocation</h4>
                                        <div class="card-tools">
                                            <button
                                                class="btn btn-icon btn-link btn-primary btn-xs">
                                                <span class="fa fa-angle-down"></span>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                                <span class="fa fa-sync-alt"></span>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-link btn-primary btn-xs">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="card-category">
                                        Map of the distribution of users around the world
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive table-hover table-sales">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/id.png"
                                                                        alt="indonesia" />
                                                                </div>
                                                            </td>
                                                            <td>Indonesia</td>
                                                            <td class="text-end">2.320</td>
                                                            <td class="text-end">42.18%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/us.png"
                                                                        alt="united states" />
                                                                </div>
                                                            </td>
                                                            <td>USA</td>
                                                            <td class="text-end">240</td>
                                                            <td class="text-end">4.36%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/au.png"
                                                                        alt="australia" />
                                                                </div>
                                                            </td>
                                                            <td>Australia</td>
                                                            <td class="text-end">119</td>
                                                            <td class="text-end">2.16%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/ru.png"
                                                                        alt="russia" />
                                                                </div>
                                                            </td>
                                                            <td>Russia</td>
                                                            <td class="text-end">1.081</td>
                                                            <td class="text-end">19.65%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/cn.png"
                                                                        alt="china" />
                                                                </div>
                                                            </td>
                                                            <td>China</td>
                                                            <td class="text-end">1.100</td>
                                                            <td class="text-end">20%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="flag">
                                                                    <img
                                                                        src="assets/img/flags/br.png"
                                                                        alt="brazil" />
                                                                </div>
                                                            </td>
                                                            <td>Brasil</td>
                                                            <td class="text-end">640</td>
                                                            <td class="text-end">11.63%</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mapcontainer">
                                                <div
                                                    id="world-map"
                                                    class="w-100"
                                                    style="height: 300px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">New Customers</div>
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-icon btn-clean me-0"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-bs-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-list py-4">
                            <?php foreach ($new_customers as $customer): ?>
                                <div class="item-list">
                                    <div class="avatar">
                                        <?php
                                        $letter = strtolower(substr($customer['first_name'], 0, 1));
                                        $color_class = 'bg-' . $letter;

                                        // fallback if no class exists
                                        if (!preg_match('/^[a-e]$/', $letter)) {
                                            $color_class = 'bg-a';
                                        }
                                        ?>
                                        <span class="avatar-title <?= $color_class ?> rounded-circle">
                                            <?= strtoupper(substr($customer['first_name'], 0, 2)) ?>
                                        </span>
                                    </div>

                                    <div class="info-user ms-3">
                                        <div class="username">
                                            <?= esc($customer['first_name']) ?>
                                        </div>
                                        <div class="status">
                                            <?= date('d M Y', strtotime($customer['created_date'])) ?>
                                        </div>
                                    </div>

                                    <button class="btn btn-icon btn-link op-8 me-1">
                                        <i class="far fa-envelope"></i>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-danger op-8">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">Transaction History</div>
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-icon btn-clean me-0"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-bs-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Payment Number</th>
                                        <th scope="col" class="text-end">Date & Time</th>
                                        <th scope="col" class="text-end">Amount</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php foreach ($transactions as $index => $t): ?>
                                            <tr class="transaction-row <?= $index >= 10 ? 'd-none' : ''; ?>"
                                                data-index="<?= $index; ?>">
                                                <th scope="row">
                                                    <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    Payment from #<?= esc($t['stripe_payment_id']) ?>
                                                </th>
                                                <td class="text-end">
                                                    <?= date('M d, Y, g.i a', strtotime($t['created_at'])) ?>
                                                </td>
                                                <td class="text-end">
                                                    $<?= number_format($t['amount_cents'] / 100, 2) ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($t['status'] == 'succeeded'): ?>
                                                        <span class="badge badge-completed">Completed</span>
                                                    <?php elseif ($t['status'] == 'Pending'): ?>
                                                        <span class="badge badge-warning">Pending</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Failed</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No transactions found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($transactions) && count($transactions) > 10): ?>
                            <div class="text-center py-3" id="transactionViewMoreContainer">
                                <button class="btn btn-sm btn-update me-2" id="transactionViewMoreBtn">
                                    View More
                                </button>
                                <button class="btn btn-sm btn-cancel d-none" id="transactionShowLessBtn">
                                    Show Less
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('admin/footer'); ?>

<script>
    //Pending orders view more & show less function
    (function () {
        const viewMoreBtn = document.getElementById('viewMoreBtn');
        const showLessBtn = document.getElementById('showLessBtn');
        if (!viewMoreBtn || !showLessBtn) return;

        const allRows = document.querySelectorAll('.pending-order-row');
        const totalRows = allRows.length;
        const batchSize = 10;
        let visibleCount = 10;

        function updateButtons() {
            // Hide "View More" if everything is visible
            viewMoreBtn.classList.toggle('d-none', visibleCount >= totalRows);
            // Show "Show Less" only if more than initial 10 are visible
            showLessBtn.classList.toggle('d-none', visibleCount <= 10);
        }

        viewMoreBtn.addEventListener('click', function () {
            const nextVisible = Math.min(visibleCount + batchSize, totalRows);
            for (let i = visibleCount; i < nextVisible; i++) {
                allRows[i].classList.remove('d-none');
            }
            visibleCount = nextVisible;
            updateButtons();
        });

        showLessBtn.addEventListener('click', function () {
            for (let i = 10; i < totalRows; i++) {
                allRows[i].classList.add('d-none');
            }
            visibleCount = 10;
            updateButtons();
        });
    })();

    //transaction history view more & show less function
    (function () {
        const viewMoreBtn = document.getElementById('transactionViewMoreBtn');
        const showLessBtn = document.getElementById('transactionShowLessBtn');
        if (!viewMoreBtn || !showLessBtn) return;

        const allRows = document.querySelectorAll('.transaction-row');
        const totalRows = allRows.length;
        const batchSize = 10;
        let visibleCount = 10;

        function updateButtons() {
            viewMoreBtn.classList.toggle('d-none', visibleCount >= totalRows);
            showLessBtn.classList.toggle('d-none', visibleCount <= 10);
        }

        viewMoreBtn.addEventListener('click', function () {
            const nextVisible = Math.min(visibleCount + batchSize, totalRows);
            for (let i = visibleCount; i < nextVisible; i++) {
                allRows[i].classList.remove('d-none');
            }
            visibleCount = nextVisible;
            updateButtons();
        });

        showLessBtn.addEventListener('click', function () {
            for (let i = 10; i < totalRows; i++) {
                allRows[i].classList.add('d-none');
            }
            visibleCount = 10;
            updateButtons();
        });
    })();
</script>