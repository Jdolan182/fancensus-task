import $ from 'jquery';

$('#youtubeForm').on('submit', function (e) {
    e.preventDefault();
    console.log('we got here')

    const videoId = $('#videoID').val();

    $.ajax({
        url: '/youtube/video',
        method: 'GET',
        data: { video_id: videoId },
        success: function (video) {
            const $tbody = $('#videoResult tbody');
            $tbody.empty();

            $.each(video, function (key, value) {
                let displayValue;

                if (Array.isArray(value)) {
                    displayValue = value.map(item =>
                        (typeof item === 'object' && item !== null) ? JSON.stringify(item) : item
                    ).join('<br>');
                } else if (typeof value === 'object' && value !== null) {
                    displayValue = Object.entries(value)
                        .map(([k, v]) => `${k}: ${v}`)
                        .join('<br>');
                } else {
                    displayValue = value;
                }

                $tbody.append(`
                    <tr>
                        <td class="py-4 pr-3 align-top text-sm font-medium text-gray-900 dark:text-white">${key}</td>
                        <td class="py-4 pr-3 text-sm text-gray-700 dark:text-gray-300">${displayValue}</td>
                    </tr>
                `);
            });

            $('#videoResultWrapper').removeClass('hidden');
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.error || 'Something went wrong';
            $('#video-result').html(`<p class="error">${message}</p>`);
        }
    });
});
