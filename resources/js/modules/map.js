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
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 905 1200" fill="#323c46" aria-hidden="true">' +
		'<path d="M452.344 0C202.522 0 0 202.521 0 452.344C0 535.891 16.353 622.181 63.281 684.375L452.344 1200L841.407 684.375C884.032 627.885 904.688 528.019 904.688 452.344C904.688 202.521 702.166 0 452.344 0ZM452.344 261.987C557.46 261.987 642.7 347.228 642.7 452.343C642.7 557.46 557.46 642.7 452.344 642.7C347.228 642.7 261.988 557.46 261.988 452.344C261.988 347.228 347.228 261.987 452.344 261.987Z"></path>' +
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
