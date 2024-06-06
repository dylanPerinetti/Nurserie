document.addEventListener("DOMContentLoaded", function() {
    const onButton = document.getElementById("on-cam");
    const offButton = document.getElementById("off-cam");
    const liveStream = document.getElementById("live-stream");
    const streamOff = document.getElementById("stream-off");
    const startRecordingButton = document.getElementById('start-recording');
    const stopRecordingButton = document.getElementById('stop-recording');

    function setCamState(state) {
        if (state === 'ON') {
            liveStream.style.display = 'block';
            streamOff.style.display = 'none';
            onButton.style.backgroundColor = '#00FF00AA'; // Vert pour ON
            offButton.style.backgroundColor = 'grey'; // Gris pour inactif
        } else {
            liveStream.style.display = 'none';
            streamOff.style.display = 'block';
            offButton.style.backgroundColor = '#FF0000AA'; // Rouge pour OFF
            onButton.style.backgroundColor = 'grey'; // Gris pour inactif
        }
    }

    function logError(error) {
        fetch('log_error.php', {
            method: 'POST',
            body: error
        }).catch(console.error); // Log to console if the error logging fails
    }

    function startRecording() {
        fetch('http://172.16.20.92:5050/start_recording')
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                startRecordingButton.classList.add('active');
                startRecordingButton.classList.remove('inactive');
                stopRecordingButton.classList.add('inactive');
                stopRecordingButton.classList.remove('active');
            })
            .catch(error => {
                alert('Erreur : ' + error);
                logError('Start Recording Error: ' + error);
            });
    }

    function stopRecording() {
        fetch('http://172.16.20.92:5050/stop_recording')
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                stopRecordingButton.classList.add('active');
                stopRecordingButton.classList.remove('inactive');
                startRecordingButton.classList.add('inactive');
                startRecordingButton.classList.remove('active');
            })
            .catch(error => {
                alert('Erreur : ' + error);
                logError('Stop Recording Error: ' + error);
            });
    }

    onButton.addEventListener("click", function() {
        setCamState('ON');
    });

    offButton.addEventListener("click", function() {
        setCamState('OFF');
    });

    liveStream.addEventListener('error', function() {
        setCamState('OFF');
        logError('Live Stream Error: Failed to load live stream.');
    });

    if (startRecordingButton && stopRecordingButton) {
        startRecordingButton.addEventListener('click', startRecording);
        stopRecordingButton.addEventListener('click', stopRecording);
    }

    setCamState('ON');
});
