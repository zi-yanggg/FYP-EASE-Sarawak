 <!-- <footer class="footer">
     <div class="container-fluid d-flex justify-content-between">
         <nav class="pull-left">
             <ul class="nav">
                 <li class="nav-item">
                     <a class="nav-link" href="http://www.themekita.com">
                         ThemeKita
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#"> Help </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#"> Licenses </a>
                 </li>
             </ul>
         </nav>
         <div class="copyright">
             2024, made with <i class="fa fa-heart heart text-danger"></i> by
             <a href="http://www.themekita.com">ThemeKita</a>
         </div>
         <div>
             Distributed by
             <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
         </div>
     </div>
 </footer> -->
 </div>
 </div>
 <!--   Core JS Files   -->
 <script src="<?= base_url('assets/js/admin/core/jquery-3.7.1.min.js') ?>"></script>
 <script>
     $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': getCsrfToken() } });
 </script>
 <script src="<?= base_url('assets/js/admin/core/popper.min.js') ?>"></script>
 <script src="<?= base_url('assets/js/admin/core/bootstrap.min.js') ?>"></script>

 <!-- jQuery Scrollbar -->
 <script src="<?= base_url('assets/js/admin/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>

 <!-- Chart JS -->
 <script src="<?= base_url('assets/js/admin/plugin/chart.js/chart.min.js') ?>"></script>

 <!-- jQuery Sparkline -->
 <script src="<?= base_url('assets/js/admin/plugin/jquery.sparkline/jquery.sparkline.min.js') ?>"></script>

 <!-- Chart Circle -->
 <script src="<?= base_url('assets/js/admin/plugin/chart-circle/circles.min.js') ?>"></script>

 <!-- Datatables -->
 <script src="<?= base_url('assets/js/admin/plugin/datatables/datatables.min.js') ?>"></script>

 <!-- Bootstrap Notify -->
 <script src="<?= base_url('assets/js/admin/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

 <!-- jQuery Vector Maps -->
 <script src="<?= base_url('assets/js/admin/plugin/jsvectormap/jsvectormap.min.js') ?>"></script>
 <script src="<?= base_url('assets/js/admin/plugin/jsvectormap/world.js') ?>"></script>

 <!-- Sweet Alert -->
 <script src="<?= base_url('assets/js/admin/plugin/sweetalert/sweetalert.min.js') ?>"></script>

 <!-- Kaiadmin JS -->
 <script src="<?= base_url('assets/js/admin/kaiadmin.min.js') ?>"></script>

 <!-- Kaiadmin DEMO methods, don't include it in your project! -->
 <!-- <script src="assets/js/admin/demo.js"></script> -->
 <script>
     $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#177dff",
         fillColor: "rgba(23, 125, 255, 0.14)",
     });

     $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#f3545d",
         fillColor: "rgba(243, 84, 93, .14)",
     });

     $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#ffa534",
         fillColor: "rgba(255, 165, 52, .14)",
     });
 </script>
 <?php
 $easeToast = session()->getFlashdata('toast');
 if (!empty($easeToast)):
     $tPalette = ['#5B532C', '#B8860B', '#0A0A0A', '#1A6CB0', '#2BA869', '#6A4FBB'];
     $tUid     = (int)($easeToast['user_id'] ?? 0);
     $tBg      = $tUid > 0 ? $tPalette[$tUid % count($tPalette)] : '#1A6CB0';
     $tFg      = $tBg === '#0A0A0A' ? '#F2BE00' : '#fff';
     $tInits   = !empty($easeToast['username']) ? strtoupper(substr($easeToast['username'], 0, 2)) : '??';
     $tSuper   = ($easeToast['role'] ?? '') === 'Superadmin';
     $tIcon    = $easeToast['icon']  ?? 'fas fa-check-circle';
     $tTitle   = $easeToast['title'] ?? 'Done';
 ?>
 <div id="easeToast" class="ease-toast" role="alert" aria-live="assertive">
     <div class="ease-toast__head">
         <i class="<?= esc($tIcon) ?>"></i>
         <span><?= esc($tTitle) ?></span>
         <button class="ease-toast__close" onclick="easeToastDismiss()" aria-label="Close">
             <i class="fas fa-times"></i>
         </button>
     </div>
     <div class="ease-toast__body">
         <?php if (!empty($easeToast['username'])): ?>
             <span class="ease-toast__av" style="background:<?= esc($tBg) ?>;color:<?= esc($tFg) ?>">
                 <?= esc($tInits) ?>
             </span>
             <div class="ease-toast__info">
                 <div class="ease-toast__name"><?= esc($easeToast['username']) ?></div>
                 <?php if (!empty($easeToast['email'])): ?>
                 <div class="ease-toast__email"><?= esc($easeToast['email']) ?></div>
                 <?php endif; ?>
                 <?php if (!empty($easeToast['role'])): ?>
                 <span class="ease-toast__role <?= $tSuper ? 'ease-toast__role--super' : 'ease-toast__role--admin' ?>">
                     <?= esc($easeToast['role']) ?>
                 </span>
                 <?php endif; ?>
             </div>
         <?php elseif (!empty($easeToast['message'])): ?>
             <p class="ease-toast__msg"><?= esc($easeToast['message']) ?></p>
         <?php endif; ?>
     </div>
     <div class="ease-toast__progress" id="easeToastProgress"></div>
 </div>
 <script>
 (function () {
     'use strict';
     var toast    = document.getElementById('easeToast');
     var progress = document.getElementById('easeToastProgress');
     if (!toast) return;
     var duration = 5000;
     var start    = null;
     function tick(ts) {
         if (!start) start = ts;
         var pct = Math.max(0, 100 - ((ts - start) / duration) * 100);
         progress.style.width = pct + '%';
         if (ts - start < duration) {
             requestAnimationFrame(tick);
         } else {
             easeToastDismiss();
         }
     }
     requestAnimationFrame(tick);
     window.easeToastDismiss = function () {
         toast.classList.add('ease-toast--out');
         setTimeout(function () { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 320);
     };
 }());
 </script>
 <?php endif; ?>

 </body>

 </html>