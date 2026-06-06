// assets/js/main.js

let sosActive = false;
let locationInterval;
let mediaRecorder;
let audioChunks = [];
let currentAlertId = null;

const sosBtn = document.getElementById('sosButton');
const statusText = document.getElementById('sosStatus');

if (sosBtn) {
    sosBtn.addEventListener('click', toggleSOS);
}

async function toggleSOS() {
    if (!sosActive) {
        // ACTIVATE SOS
        sosActive = true;
        sosBtn.classList.add('pulsate');
        sosBtn.innerText = 'SOS ACTIVE';
        if (statusText) statusText.innerText = 'Capturing Location & Audio...';
        
        try {
            let lat = null;
            let lng = null;
            
            try {
                const position = await getCurrentPosition();
                lat = position.coords.latitude;
                lng = position.coords.longitude;
            } catch (geoErr) {
                console.warn("Geolocation failed: ", geoErr);
                // Proceed with null location if GPS fails
            }
            
            // Send initial SOS to server
            const response = await fetch('/Sakhi%20SOS/api/sos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'trigger', lat, lng })
            });
            const data = await response.json();
            
            if (data.success) {
                currentAlertId = data.alert_id;
                if (statusText) statusText.innerHTML = `<span class="text-danger">Alert Sent! ${lat ? 'Tracking active.' : 'Location unavailable.'} Contacts notified.</span>`;
                
                // Start background tracking every 30 seconds
                if (lat) {
                    locationInterval = setInterval(() => updateLocation(currentAlertId), 30000);
                }
                
                // Start Audio Recording
                startAudioRecording(currentAlertId);
            } else {
                if (statusText) statusText.innerText = 'Failed to trigger alert: ' + (data.error || 'Unknown error');
                resetSOSState();
            }
        } catch (error) {
            console.error(error);
            if (statusText) statusText.innerText = 'Network Error: ' + (error.message || error);
            resetSOSState();
        }
    } else {
        // DEACTIVATE SOS
        if(confirm("Are you sure you want to deactivate the SOS alert? (This will notify contacts that you are safe)")) {
            resetSOSState();
            if (statusText) statusText.innerHTML = '<span class="text-success">SOS Deactivated.</span>';
            clearInterval(locationInterval);
            
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop(); // This triggers the upload in the onstop handler
            }
            
            // Send resolve to server
            if (currentAlertId) {
                fetch('/Sakhi%20SOS/api/sos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'resolve', alert_id: currentAlertId })
                });
            }
        }
    }
}

function resetSOSState() {
    sosActive = false;
    sosBtn.classList.remove('pulsate');
    sosBtn.innerText = 'SOS';
}

function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject('Geolocation is not supported by your browser');
        } else {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        }
    });
}

async function updateLocation(alertId) {
    try {
        const position = await getCurrentPosition();
        fetch('/Sakhi%20SOS/api/location.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                alert_id: alertId,
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
        });
    } catch (e) {
        console.error("Location update failed", e);
    }
}

async function startAudioRecording(alertId) {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.ondataavailable = e => {
            if (e.data.size > 0) audioChunks.push(e.data);
        };

        mediaRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            const formData = new FormData();
            formData.append('audio', audioBlob, `sos_${alertId}_${Date.now()}.webm`);
            formData.append('alert_id', alertId);

            // Upload audio
            fetch('/Sakhi%20SOS/api/upload_audio.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
              .then(data => console.log('Audio upload status:', data))
              .catch(err => console.error('Audio upload failed:', err));
              
            // Stop tracks to release mic
            stream.getTracks().forEach(track => track.stop());
        };

        mediaRecorder.start();
        console.log("Audio recording started...");
        
        // Auto stop after 2 minutes if not stopped manually (to save space)
        setTimeout(() => {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }
        }, 120000); 

    } catch (err) {
        console.error('Microphone access denied or error', err);
    }
}
