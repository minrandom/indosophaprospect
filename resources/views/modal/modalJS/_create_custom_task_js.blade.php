<script type="text/javascript">
    var provinceSelect = $("#ct_province_id");
    var hospitalSelect = $("#ct_hospital_id");
    var departmentSelect = $("#ct_department");

    $(function() {
    $.ajax({
     url: "{{ route('admin.prospectcreate') }}",
     method: "GET",
     success:function(response){

        var userrole = $("#theroles").val()
        var userpost = $("#thepost").val()

    populateSelectFromDatalist('ct_province_id', response.province,"Pilih Provinsi");
    function fetchHospitals2(provinceId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  if(userrole!="project"){
                  $.ajax({
                    url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
                    method: "GET",
                    success: function(response) {

                        populateSelectFromDatalist('ct_hospital_id', response.hosopt,"Pilih Rumah Sakit");
                        console.log('Hospitals fetched for province ID ' + provinceId + ':', response.hosopt);
                    }
                  });}
                  else {
                    $.ajax({
                    url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
                    method: "GET",
                    success: function (response) {
                            var filteredHospitals = response.hosopt.filter(function(hospital) {
                                // Replace 'desired_owner' with the actual owner value you want to filter by
                                return hospital.owned_by === 'TNI / POLRI';
                            });

                            // Use the filtered hospitals list to populate the select element
                            populateSelectFromDatalist('ct_hospital_id', filteredHospitals, "Pilih Rumah Sakit");

                    }
                  });
                  }
                }

                provinceSelect.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  fetchHospitals2(selectedProvinceId);
                });


                populateSelectFromDatalist('ct_department', response.dept,"Pilih Departemen");

            }
        });
    });

</script>


<script>
$(function () {
    $(document).on('submit', '.js-confirm-create-custom-task', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Create Custom Task?',
            text: 'This custom task will be added to task pool.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Create',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
