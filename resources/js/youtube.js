import $ from 'jquery';

$('#youtubeForm').on('submit', function (e) {
    e.preventDefault();
    console.log('we got here')

    const videoId = $('#videoID').val();

    $.ajax({
        url: '/youtube/video',
        method: 'GET',
        data: { video_id: videoId },
        success: function (response) {
            $('#video-result').html(`
                <h3>${response.snippet.title}</h3>
                <p>${response.snippet.description}</p>
                <p>Views: ${response.statistics.viewCount}</p>
            `);
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.error || 'Something went wrong';
            $('#video-result').html(`<p class="error">${message}</p>`);
        }
    });
});
