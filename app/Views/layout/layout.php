<!DOCTYPE html>
<html lang="en">

<?php

$db = db_connect();

$result = $db->table('fire_cases')->where('is_open', 0)->orderBy('case_id', 'DESC')->get();

$count = $db->table('fire_cases')
    ->where('is_open', 0)
    ->countAllResults();


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('css/custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('sweetalert/sweetalert2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link rel="icon" href="<?= base_url('logo/logo.png') ?>" type="image/png">

    <title><?= $this->renderSection('title') ?></title>

    <style>
        .logout1 {
            transition: padding-right 0.3s ease-in, color 0.3s ease-in;
            color: white;
        }

        .logout1:hover {
            padding-right: 20px;
            color: orange !important;
        }


        /* Make the dropdown scrollable */
        .dropdown-menu.custom-scroll {
            overflow-y: auto;
            max-height: 250px;
            /* adjust as needed */
        }

        /* Custom scrollbar for modern browsers */
        .dropdown-menu.custom-scroll::-webkit-scrollbar {
            width: 8px;
            /* width of scrollbar */
        }

        .dropdown-menu.custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* track background */
            border-radius: 4px;
        }

        .dropdown-menu.custom-scroll::-webkit-scrollbar-thumb {
            background-color: #dc3545;
            /* matches Bootstrap danger/red */
            border-radius: 4px;
            border: 2px solid #f1f1f1;
            /* adds padding around thumb */
        }

        .dropdown-menu.custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #b02a37;
            /* darker on hover */
        }

        /* Optional: Firefox */
        .dropdown-menu.custom-scroll {
            scrollbar-width: thin;
            scrollbar-color: #dc3545 #f1f1f1;
        }

        
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="/admin">Barangay Dicayas Clearance Issuance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item me-2 ms-3">
                        <a class="nav-link <?= $this->renderSection('dashboard') ?> text-white" aria-current="page"
                            href="/admin">Dashboard</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?= $this->renderSection('requests') ?> text-white"
                            href="/admin/requests">Requests</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?= $this->renderSection('residents') ?> text-white"
                            href="/admin/residents">Registered Residents</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?= $this->renderSection('population') ?> text-white"
                            href="/admin/population">Population</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->renderSection('fire_list') ?> text-white"
                            href="/admin/fire-list">List of Fire
                            Incidents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->renderSection('reports') ?> text-white"
                            href="/admin/view-reports">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $this->renderSection('register') ?> text-white"
                            href="/admin/register">Register ESP</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="btn-group dropstart">
            <button type="button" class="btn btn-orange text-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill"></i>
                <?php if ($count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= esc($count) ?>
                        <span class="visually-hidden">unread messages</span>
                    </span>
                <?php endif; ?>
            </button>

            <ul class="dropdown-menu custom-scroll" style="max-height: 250px; width: 370px; z-index: 2000;">
                <?php if ($count == 0): ?>
                    <li class="dropdown-item text-center">No notifications available</li>
                <?php else: ?>
                    <?php foreach ($result->getResultArray() as $r): ?>
                        <li>
                            <a class="dropdown-item p-3" href="#" data-bs-toggle="modal" data-bs-target="#notification<?= esc($r['case_id']) ?>">
                                <strong class="<?= esc($r['status'] == 'emergency' ? 'text-danger' : 'text-warning') ?>"><?= esc(ucfirst($r['status'])) ?></strong>: <?= esc($r['household'] . " - " . $r['exact_location']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="">
            <a href="/logout" class="btn fw-bold text-white logout1">Logout</a>
        </div>
    </nav>

    <?php if ($count !== 0): ?>
        <?php foreach ($result->getResultArray() as $r): ?>
            <div class="modal fade" id="notification<?= esc($r['case_id']) ?>" tabindex="-1" aria-labelledby="notificationLabel<?= esc($r['case_id']) ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger">
                        <!-- Modal Header -->
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="notificationLabel<?= esc($r['case_id']) ?>">
                                🔥 FIRE ALERT DETECTED!
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <p><strong>Location:</strong> <?= esc($r['exact_location']) ?></p>
                            <p><strong>Household:</strong> <?= esc($r['household']) ?></p>
                            <p>
                                <strong>Alert Level:</strong>
                                <span class="fw-bold <?= esc($r['status'] == 'emergency' ? 'text-danger' : 'text-warning') ?>">
                                    <?= esc(strtoupper($r['status'])) ?>
                                </span>
                            </p>
                            <p><strong>Alert Type:</strong> <?= esc(strtoupper($r['alert_type'])) ?></p>
                            <p>
                                <strong>Date Occurrence:</strong> <?= esc(date('F d, Y | h:i A', strtotime($r['created_at']))) ?>
                            </p>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form method="post" action="/admin/fire-case">
                                <input type="hidden" name="id" value="<?= esc($r['id']) ?>">
                                <button class="btn btn-danger">View Fire Case</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


    <?= $this->renderSection('body') ?>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= session()->getFlashdata('success'); ?>',
                    showConfirmButton: true,
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?= session()->getFlashdata('error'); ?>',
                    showConfirmButton: true,
                });
            });
        </script>
    <?php endif; ?>

    <script src="<?= base_url('sweetalert/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        function checkFireNotifications() {
            fetch('/admin/check-fire-notifications') // your route to the PHP method
                .then(res => res.json())
                .then(data => {
                    // Ensure data format is valid
                    if (data.status === 200 && data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(fire => {
                            let message =
                                `🔥 FIRE ALERT DETECTED!

                                
                                
                                📍 Location: ${fire.exact_location || 'Unknown'}
                                                        
                                
                                
                                🏠 Household: ${fire.household || 'N/A'}
                                                        
                                
                                
                                ⚠️ ALERT LEVEL: ${fire.status ? fire.status.toUpperCase() : 'N/A'} 
                                
                                

                                ALERT TYPE: ${fire.alert_type.toUpperCase()}
                                `;


                            Swal.fire({
                                icon: 'warning',
                                title: 'Fire Case Alert',
                                text: message,
                                confirmButtonText: 'Acknowledge',
                                confirmButtonColor: '#d33',
                            }).then(result => {
                                if (result.isConfirmed) {
                                    // Optional: refresh data or UI after acknowledgement
                                    location.reload();
                                }
                            });
                        });
                    }
                })
                .catch(err => console.error('Error checking fire notifications:', err));
        }

        // Poll every 5 seconds
        setInterval(checkFireNotifications, 5000);
    </script>

</body>

</html>