import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

// Kolbareal — Alte Mühlackerstrasse, Zürich-Affoltern
const COORDINATES = [8.506682, 47.424717]; // [lng, lat]

const initMap = () => {
	const el = document.getElementById('map');
	const token = import.meta.env.VITE_MAPBOX_TOKEN;

	if (!el || !token) {
		return;
	}

	mapboxgl.accessToken = token;

	const map = new mapboxgl.Map({
		container: el,
		style: 'mapbox://styles/marcelitoooo/ck16ms7m51nlo1cmwnqrbjuyq?optimize=true',
		center: COORDINATES,
		zoom: 14,
		cooperativeGestures: true,
	});

	map.addControl(new mapboxgl.NavigationControl(), 'top-right');
	map.scrollZoom.disable();

	const marker = document.createElement('div');
	marker.className = 'map-marker';
	marker.innerHTML =
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="#323c46" aria-hidden="true">' +
		'<path d="M128,64a40,40,0,1,0,40,40A40,40,0,0,0,128,64Zm0,64a24,24,0,1,1,24-24A24,24,0,0,1,128,128Zm0-112a88.1,88.1,0,0,0-88,88c0,31.4,14.51,64.68,42,96.25a254.19,254.19,0,0,0,41.45,38.3,8,8,0,0,0,9.18,0A254.19,254.19,0,0,0,174,200.25c27.45-31.57,42-64.85,42-96.25A88.1,88.1,0,0,0,128,16Zm0,206c-16.53-13-72-60.75-72-118a72,72,0,0,1,144,0C200,161.23,144.53,209,128,222Z"></path>' +
		'</svg>';

	map.on('load', () => {
		new mapboxgl.Marker(marker, { anchor: 'bottom' }).setLngLat(COORDINATES).addTo(map);
	});
};

if (document.readyState !== 'loading') {
	initMap();
} else {
	document.addEventListener('DOMContentLoaded', initMap);
}
