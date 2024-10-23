@unless(request()->is('dashboard', 'category', 'asset-search', 'unexpected-index/*', 'material-index/*', 'salary-index/*', 'sparepart-index/*', 'fuel-index/*', 'unexpected-search/*', 
'material-search/*', 'salary-search/*', 'sparepart-search/*', 'fuel-search/*') || preg_match('/^[^\/]+\/[^\/]+$/', request()->path()))
<footer class="bg-white rounded shadow p-5 mb-4 mt-4">
    <div class="row">
        <div class="col-12 col-md-4 col-xl-6 mb-4 mb-md-0">
            <p class="mb-0 text-center text-lg-start">© 2019-
                <span class="current-year"></span> Designed<a class="text-primary fw-normal" href="https://themesberg.com" target="_blank"> Themesberg</a>
                <span> & Developed IT PT Satria Utama</span>
            </p>
        </div>
    </div>
</footer>
@endif