document.addEventListener("DOMContentLoaded", function() {
    const onButton = document.getElementById("on-cam");
    const offButton = document.getElementById("off-cam");
    const liveStream = document.getElementById("live-stream");
    const streamOff = document.getElementById("stream-off");
    const liveIndicator = document.getElementById("live-indicator");
    const streamContainer = document.getElementById("stream-container");
    const startRecordingButton = document.getElementById('start-recording');
    const stopRecordingButton = document.getElementById('stop-recording');

    function setCamState(state) {
        if (state === 'ON') {
            liveStream.style.display = 'block';
            streamOff.style.display = 'none';
            onButton.style.backgroundColor = '#00FF00AA'; // Vert pour ON
            offButton.style.backgroundColor = 'grey'; // Gris pour inactif
            liveIndicator.classList.add('live');
            liveIndicator.classList.remove('offline');
            streamContainer.classList.add('live');
            streamContainer.classList.remove('offline');
        } else {
            liveStream.style.display = 'none';
            streamOff.style.display = 'block';
            offButton.style.backgroundColor = '#FF0000AA'; // Rouge pour OFF
            onButton.style.backgroundColor = 'grey'; // Gris pour inactif
            liveIndicator.classList.add('offline');
            liveIndicator.classList.remove('live');
            streamContainer.classList.add('offline');
            streamContainer.classList.remove('live');
        }
    }

    function logError(error) {
        fetch('log_error.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ error: error })
        }).catch(console.error); // Log to console if the error logging fails
    }

    function showNotification(message, type = 'error') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerText = message;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    function startRecording() {
        fetch('http://172.16.20.92:5050/start_recording')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                showNotification(data.message, 'success');
                startRecordingButton.classList.add('active');
                startRecordingButton.classList.remove('inactive');
                stopRecordingButton.classList.add('inactive');
                stopRecordingButton.classList.remove('active');
            })
            .catch(error => {
                showNotification('Erreur : ' + error.message, 'error');
                logError('Start Recording Error: ' + error.message);
            });
    }

    function stopRecording() {
        fetch('http://172.16.20.92:5050/stop_recording')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                showNotification(data.message, 'success');
                stopRecordingButton.classList.add('active');
                stopRecordingButton.classList.remove('inactive');
                startRecordingButton.classList.add('inactive');
                startRecordingButton.classList.remove('active');
            })
            .catch(error => {
                showNotification('Erreur : ' + error.message, 'error');
                logError('Stop Recording Error: ' + error.message);
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
