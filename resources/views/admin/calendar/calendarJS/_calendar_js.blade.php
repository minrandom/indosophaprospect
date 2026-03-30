<script>
    $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek',
                },
                defaultView: 'month',
                events: @json($activities->map(function($a) {
                    return [
                        'title' => $a->hospital->name . '||' . $a->purpose .'||'.$a->results.'||'.$a->department->name, // separator
                        'start' => $a->start,
                        'end' => $a->end

                    ];
                })),
                eventRender : function(event, element)
                {
                    var parts = event.title.split('||');
                    var hospital = parts[0] || '';
                    var purpose = parts[1] || '';
                    var results = parts[2] || '';
                    var dept = parts[3] || '';

                    var html = `
                        <div style="font-weight: bold; font-size: 16px;">${hospital} - ${dept}</div>
                        <div style="font-size: 12px; color: blue;">${purpose}</div>
                    `;
                    element.find('.fc-time').remove();
                     element.find('.fc-title').html(html);
                },
                eventClick: function(event, jsEvent, view) {
                    let [hospitalName, purpose,results,dept] = event.title.split('||');

    // Optional: fallback if title is malformed
                  hospitalName = hospitalName?.trim() || 'Unknown';
                purpose = purpose?.trim() || '-';
                results = results?.trim() || '-';
                dept = dept?.trim()|| 'Unknown';
                    // Fill modal fields
                    $('#activityDetailModal .modal-title').html(`<h5><b>${hospitalName}</b></h5>`);
                    $('#activityDetailModal .modal-body').html(`
                    <p><strong>Go to dept :</strong>${dept}</h4>
                        <p><strong>Purpose:</strong> ${purpose}</p>
                        <p><strong>Start:</strong> ${event.start.format('YYYY-MM-DD HH:mm')}</p>
                        <p><strong>End:</strong> ${event.end ? event.end.format('YYYY-MM-DD HH:mm') : '-'}</p>
                        <p><strong>Result :</strong> ${results ? results : '-'}</p>
                    `);
                    $('#activityDetailModal').modal('show');
                }


            });

            $('#activity-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("activities.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#activityModal').modal('hide');
                        location.reload(); // re-fetch events
                    },
                    error: function(err) {
                        alert('Error saving activity');
                    }
                });
            });
        });

</script>
