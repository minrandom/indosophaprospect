<script>
$(function () {
    const rescheduleUrl = "{{ route('missions.runs.reschedule', ['run' => '__RUN_ID__']) }}";

    $(document).on('click', '.js-run-reschedule', function () {
        const runId = $(this).data('run-id');
        const runCode = $(this).data('run-code') || '-';

        $('#swap_with_run_id').val('');
        $('#rescheduleRunCode').html('Visit: <b>' + runCode + '</b>');
        $('#rescheduleRunForm').attr('action', rescheduleUrl.replace('__RUN_ID__', runId));

        $('#rescheduleRunModal').modal('show');
    });

    $('#rescheduleRunForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        Swal.fire({
            title: 'Reschedule visit?',
            text: 'System will check schedule clash first.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reschedule'
        }).then((result) => {
            if (!result.isConfirmed) return;

            submitReschedule(form);
        });
    });

    function submitReschedule(form) {
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    html: res.message || 'Visit rescheduled.'
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};

                if (xhr.status === 409 && res.can_swap && res.swap_with_run_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Schedule Clash',
                        html: res.message + '<br><br>Do you want to swap schedule?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, swap',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        $('#swap_with_run_id').val(res.swap_with_run_id);
                        submitReschedule(form);
                    });

                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Cannot Reschedule',
                    html: res.message || 'Failed to reschedule visit.'
                });
            }
        });
    }
});
</script>
