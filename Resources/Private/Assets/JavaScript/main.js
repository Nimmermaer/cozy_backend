import htmx from 'htmx.org';
import "bootstrap/scss/bootstrap.scss";
import "../Scss/main.scss";
import "bootstrap";
import "./custom.js";
// Neu: Slider importieren
import { initSliders } from "./slider.js";

window.htmx = htmx;

// Initialisierung beim Laden
document.addEventListener('DOMContentLoaded', () => {
  initSliders();
});
