document.addEventListener('DOMContentLoaded', function() {
    let wantedTemp = parseFloat(document.getElementById('wanted-temp').innerText);
    let currentTemp = parseFloat(document.getElementById('temp-value').innerText);
    const btnOn = document.getElementById('on-temp');
    const btnOff = document.getElementById('off-temp');
    let heaterState = document.getElementById('heater-state').innerText;

    function logError(error) {
        fetch('log_error.php', {
            method: 'POST',
            body: error
        }).catch(console.error); // Log to console if the error logging fails
    }

    function updateWantedTemperature(value) {
        wantedTemp = Math.max(0, wantedTemp + value);
        document.getElementById('wanted-temp').innerText = wantedTemp.toFixed(1) + '°C';
        saveWantedTemperature(wantedTemp.toFixed(1));
        checkHeaterState();
    }

    function saveWantedTemperature(temp) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ wanted_temp: temp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error saving temperature:', data.error);
                logError('Error saving temperature: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            logError('Fetch error: ' + error);
        });
    }

    function checkHeaterState() {
        if (wantedTemp > currentTemp) {
            setHeaterState('ON');
        } else {
            setHeaterState('OFF');
        }
    }

    function setHeaterState(state) {
        if (state === 'ON') {
            btnOn.style.backgroundColor = '#00FF00AA';
            btnOff.style.backgroundColor = '';
            fetch('http://172.16.21.222:5000/check?param=1')
                .then(handleFetchResponse)
                .then(data => console.log('Success:', data))
                .catch(error => {
                    console.error('Fetch error:', error);
                    logError('Fetch error when setting heater state to ON: ' + error);
                });
        } else {
            btnOff.style.backgroundColor = '#FF0000AA';
            btnOn.style.backgroundColor = '';
            fetch('http://172.16.21.222:5000/check?param=0')
                .then(handleFetchResponse)
                .then(data => console.log('Success:', data))
                .catch(error => {
                    console.error('Fetch error:', error);
                    logError('Fetch error when setting heater state to OFF: ' + error);
                });
        }
        saveHeaterState(state);
    }

    function saveHeaterState(state) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ state: state })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error saving state:', data.error);
                logError('Error saving state: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            logError('Fetch error: ' + error);
        });
    }

    function handleFetchResponse(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    }

    function handleFetchError(error) {
        console.error('There was a problem with the fetch operation:', error);
        logError('Fetch error: ' + error);
    }

    document.getElementById('decrease-temp').addEventListener('click', function() {
        updateWantedTemperature(-0.1);
    });

    document.getElementById('increase-temp').addEventListener('click', function() {
        updateWantedTemperature(0.1);
    });

    function initializeButtons() {
        if (heaterState === 'ON') {
            btnOn.style.backgroundColor = '#00FF00AA';
            btnOff.style.backgroundColor = '';
        } else {
            btnOff.style.backgroundColor = '#FF0000AA';
            btnOn.style.backgroundColor = '';
        }
    }

    btnOn.addEventListener('click', function() {
        setHeaterState('ON');
    });

    btnOff.addEventListener('click', function() {
        setHeaterState('OFF');
    });

    initializeButtons();
});
