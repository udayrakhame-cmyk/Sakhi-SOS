// assets/js/map.js

function initMap(lat, lng, containerId) {
    const mapElement = document.getElementById(containerId);
    if (!mapElement) return;

    if (typeof google === 'undefined') {
        mapElement.innerHTML = `
            <div class="alert alert-warning h-100 d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-geo-alt-fill fs-1 text-danger mb-2"></i>
                <h5>Map Unavailable</h5>
                <p class="mb-0 text-center">Google Maps API key is required to render the interactive map.</p>
                <p class="text-muted mt-2">Captured Coordinates: ${lat}, ${lng}</p>
                <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="btn btn-outline-danger mt-3">Open in Google Maps</a>
            </div>`;
        return;
    }

    const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
    const map = new google.maps.Map(mapElement, {
        zoom: 16,
        center: position,
        // Dark mode styles
        styles: [
            { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
            { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
            { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
            {
              featureType: "administrative.locality",
              elementType: "labels.text.fill",
              stylers: [{ color: "#d59563" }],
            },
            {
              featureType: "road",
              elementType: "geometry",
              stylers: [{ color: "#38414e" }],
            },
            {
              featureType: "road",
              elementType: "geometry.stroke",
              stylers: [{ color: "#212a37" }],
            },
            {
              featureType: "road",
              elementType: "labels.text.fill",
              stylers: [{ color: "#9ca5b3" }],
            },
            {
              featureType: "water",
              elementType: "geometry",
              stylers: [{ color: "#17263c" }],
            }
        ]
    });

    new google.maps.Marker({
        position: position,
        map: map,
        title: "SOS Location",
        animation: google.maps.Animation.DROP
    });
}
