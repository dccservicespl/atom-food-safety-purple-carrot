<footer class="footer">
    <div class="row g-0 justify-content-between fs-10 mt-4 mb-3">
        <div class="col-md-12 col-sm-auto text-center">
            <p class="mb-0 text-600">
                <span class="d-none d-sm-inline-block">
                </span><br class="d-sm-none" /> {{ date('Y') }} &copy;
                <a href="https://dccil.com/" target="_blank"> Data Consultants Corporation
                </a>
            </p>
        </div>
    </div>
</footer>
</div>
</div>
</main>
<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="/assets/vendors/popper/popper.min.js"></script>
<script src="/assets/vendors/bootstrap/bootstrap.min.js"></script>
<script src="/assets/vendors/anchorjs/anchor.min.js"></script>
<script src="/assets/vendors/is/is.min.js"></script>
<script src="/assets/vendors/echarts/echarts.min.js"></script>
<script src="/assets/vendors/fontawesome/all.min.js"></script>
<script src="/assets/vendors/lodash/lodash.min.js"></script>
<script src="/assets/vendors/list.js/list.min.js"></script>
<script src="/assets/js/theme.js"></script>
{{-- //Datatable  --}}
<script src="/assets/vendors/jquery/jquery.min.js"></script>
<script src="/assets/vendors/datatables.net/jquery.dataTables.min.js"></script>
<script src="/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.min.js"></script>
<script src="/assets/vendors/datatables.net-fixedcolumns/dataTables.fixedColumns.min.js"></script>

{{-- //date picker --}}
<script src="/assets/js/flatpickr.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

{{-- Cunstom JS --}}
<script src="/assets/js/custom.js"></script>

{{-- Sweet Alert Cdn --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script>
    $(document).ready(function() {
        $('.cta_btn_form').on('submit', function(e) {
            e.preventDefault();
            $('#overlay').show();
        })
    })
</script> --}}

<script>
    $(document).ready(function() {
        $('.cta_btn_form').on('submit', function() {
            if (this.checkValidity()) {
                $('#overlay').show();
            }
        });
    });
</script>

@yield('scripts')
@livewireScripts
</body>

</html>
