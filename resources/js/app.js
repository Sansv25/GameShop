import 'instant.page'
import Alpine from 'alpinejs'
import ajax from '@imacrayon/alpine-ajax'
import Popover from './components/popover'

// GSAP
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { TextPlugin } from 'gsap/TextPlugin'

gsap.registerPlugin(ScrollTrigger, TextPlugin)

// Expose globally for inline Blade scripts
window.gsap = gsap
window.ScrollTrigger = ScrollTrigger

// FilePond
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

// Register plugins globally
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginFileValidateSize
);

// Expose to window for inline scripts
window.FilePond = { ...FilePond }; // Spread to ensure it's a plain object with all methods
window.FilePondPluginImagePreview = FilePondPluginImagePreview;
window.FilePondPluginImageResize = FilePondPluginImageResize;
window.FilePondPluginImageTransform = FilePondPluginImageTransform;
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;

Alpine.plugin(ajax)
Alpine.data('popover', Popover)
Alpine.start()
