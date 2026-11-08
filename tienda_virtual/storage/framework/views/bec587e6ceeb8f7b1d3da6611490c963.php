<?php if (! $__env->hasRenderedOnce('2bb103e6-2b42-49d4-8d13-655b0e1d669b')): $__env->markAsRenderedOnce('2bb103e6-2b42-49d4-8d13-655b0e1d669b'); ?>
<script>
(function () {
    var raiz = document.documentElement;

    function leer() {
        try { return localStorage.getItem('theme'); } catch (e) { return null; }
    }

    function aplicar(tema) {
        if (tema !== 'oscuro' && tema !== 'claro') { tema = 'predeterminado'; }
        raiz.setAttribute('data-tema', tema);
        if (tema === 'oscuro') { raiz.classList.add('dark'); } else { raiz.classList.remove('dark'); }
    }

    window.aplicarTema = function (tema) {
        if (tema !== 'oscuro' && tema !== 'claro') { tema = 'predeterminado'; }
        try { localStorage.setItem('theme', tema); } catch (e) {}
        aplicar(tema);
    };

    window.temaGuardado = function () {
        var guardado = leer();
        return (guardado === 'oscuro' || guardado === 'claro') ? guardado : 'predeterminado';
    };

    aplicar(leer());
})();
</script>
<style>
    html {
        --bg-color: #fffbf7;
        --text-main: #4c3300;
        --text-muted: #7a5c3e;
        --card-bg: #ffffff;
        --input-bg: #faf0e6;
        --border-color: #f0dfc8;
        --accent-color: #f2a6a6;
        --header-bg: rgba(255, 251, 247, 0.9);
    }

    html[data-tema="predeterminado"] {
        --bg-color: #ead9c1;
        --text-main: #2b1a0b;
        --text-muted: #55402b;
        --card-bg: #f8eee0;
        --input-bg: #eedcc4;
        --border-color: #d2b793;
        --accent-color: #b8664a;
        --header-bg: rgba(234, 217, 193, 0.92);
        --lateral: #d2a574;
        --chip: #e4b48c;
    }

    html.dark {
        --bg-color: #0a0807;
        --text-main: #faf0e6;
        --text-muted: #c4b5a8;
        --card-bg: #1f1a17;
        --input-bg: #12100e;
        --border-color: #3a2e28;
        --accent-color: #e6a1a1;
        --header-bg: rgba(26, 22, 19, 0.9);
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-main);
        transition: background-color 0.3s, color 0.3s;
    }

    html:not(.dark) header {
        background-color: var(--header-bg) !important;
        border-bottom-color: var(--border-color) !important;
    }
    html:not(.dark) header .text-\[\#4c3300\],
    html:not(.dark) header .text-\[\#7a5c3e\] {
        color: var(--text-main) !important;
    }

    html.dark header {
        background-color: var(--header-bg) !important;
        border-bottom-color: var(--border-color) !important;
    }
    html.dark header .text-\[\#4c3300\],
    html.dark header .text-\[\#7a5c3e\],
    html.dark header .text-\[\#2C2520\] {
        color: var(--text-main) !important;
    }

    #fondo-marron { display: none; }
    html[data-tema="predeterminado"] #fondo-claro { display: none; }
    html[data-tema="predeterminado"] #fondo-marron { display: block; }
    html.dark #fondo-claro { display: none; }
    html:not(.dark) #fondo-oscuro { display: none; }

    html.dark .bg-white,
    html.dark .bg-white\/80,
    html.dark .bg-white\/90,
    html.dark .bg-\[\#fcfaf6\],
    html.dark .bg-\[\#F6F3EE\],
    html.dark .bg-\[\#f2ede4\],
    html.dark .bg-\[\#faebd7\] {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
    }

    html.dark .bg-\[\#faf0e6\],
    html.dark input,
    html.dark textarea,
    html.dark select {
        background-color: var(--input-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    html.dark .text-\[\#4c3300\],
    html.dark .text-\[\#2C2520\],
    html.dark .text-\[\#322A24\],
    html.dark .text-\[\#1a1512\],
    html.dark .text-gray-900,
    html.dark h1, html.dark h2, html.dark h3 {
        color: var(--text-main) !important;
    }

    html.dark .text-\[\#7a5c3e\],
    html.dark .text-\[\#5C534C\],
    html.dark .text-\[\#7A726C\],
    html.dark .text-\[\#4a3d33\],
    html.dark .text-\[\#5c524a\],
    html.dark .text-\[\#7a6f66\],
    html.dark .text-gray-600,
    html.dark .text-\[\#4c3300\]\/80 {
        color: var(--text-muted) !important;
    }

    html.dark .text-\[\#d98c8c\],
    html.dark .text-\[\#8C7A6B\] {
        color: var(--accent-color) !important;
    }

    html.dark .border-\[\#f0dfc8\],
    html.dark .border-\[\#F2EDE9\],
    html.dark .border-\[\#ede9e1\],
    html.dark .border-\[\#E8DFD8\] {
        border-color: var(--border-color) !important;
    }

    html.dark .border-\[\#1a1512\] {
        border-color: var(--accent-color) !important;
    }

    html.dark aside .bg-\[\#f2a6a6\] {
        background-color: #2c1f1f !important;
    }
    html.dark aside .bg-white {
        background-color: #4a3232 !important;
        color: var(--text-main) !important;
    }
    html.dark aside a:hover {
        background-color: #3a2a2a !important;
    }
    html.dark aside a:hover span,
    html.dark aside a:hover svg {
        color: var(--text-main) !important;
    }

    html.dark .bg-\[\#4c3300\],
    html.dark .bg-\[\#1a1512\] {
        background-color: var(--accent-color) !important;
        color: #12100e !important;
    }
    html.dark .bg-\[\#4c3300\]:hover,
    html.dark .bg-\[\#1a1512\]:hover {
        background-color: #f2a6a6 !important;
    }

    html[data-tema="predeterminado"] .bg-white,
    html[data-tema="predeterminado"] .bg-white\/80,
    html[data-tema="predeterminado"] .bg-white\/90,
    html[data-tema="predeterminado"] .bg-\[\#fcfaf6\],
    html[data-tema="predeterminado"] .bg-\[\#F6F3EE\],
    html[data-tema="predeterminado"] .bg-\[\#f2ede4\],
    html[data-tema="predeterminado"] .bg-\[\#faebd7\] {
        background-color: var(--card-bg) !important;
    }

    html[data-tema="predeterminado"] .bg-\[\#faf0e6\],
    html[data-tema="predeterminado"] input:not([type="radio"]):not([type="checkbox"]),
    html[data-tema="predeterminado"] textarea,
    html[data-tema="predeterminado"] select {
        background-color: var(--input-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    html[data-tema="predeterminado"] .text-\[\#4c3300\],
    html[data-tema="predeterminado"] .text-\[\#2C2520\],
    html[data-tema="predeterminado"] .text-\[\#322A24\],
    html[data-tema="predeterminado"] .text-\[\#1a1512\],
    html[data-tema="predeterminado"] .text-gray-900,
    html[data-tema="predeterminado"] h1,
    html[data-tema="predeterminado"] h2,
    html[data-tema="predeterminado"] h3 {
        color: var(--text-main) !important;
    }

    html[data-tema="predeterminado"] .text-\[\#7a5c3e\],
    html[data-tema="predeterminado"] .text-\[\#5C534C\],
    html[data-tema="predeterminado"] .text-\[\#7A726C\],
    html[data-tema="predeterminado"] .text-\[\#4a3d33\],
    html[data-tema="predeterminado"] .text-\[\#5c524a\],
    html[data-tema="predeterminado"] .text-\[\#7a6f66\],
    html[data-tema="predeterminado"] .text-\[\#8C7A6B\],
    html[data-tema="predeterminado"] .text-gray-600,
    html[data-tema="predeterminado"] .text-\[\#4c3300\]\/80 {
        color: var(--text-muted) !important;
    }

    html[data-tema="predeterminado"] .text-\[\#d98c8c\] {
        color: var(--accent-color) !important;
    }

    html[data-tema="predeterminado"] .border-\[\#f0dfc8\],
    html[data-tema="predeterminado"] .border-\[\#F2EDE9\],
    html[data-tema="predeterminado"] .border-\[\#ede9e1\],
    html[data-tema="predeterminado"] .border-\[\#E8DFD8\] {
        border-color: var(--border-color) !important;
    }

    html[data-tema="predeterminado"] .bg-\[\#f2a6a6\] {
        background-color: var(--chip) !important;
    }
    html[data-tema="predeterminado"] aside .bg-\[\#f2a6a6\] {
        background-color: var(--lateral) !important;
    }

    html[data-tema="predeterminado"] .hdr-badge {
        background: #b8664a;
        color: #faf0e6;
    }
    html[data-tema="predeterminado"] .hdr-menu {
        background: var(--card-bg);
        border-color: var(--border-color);
    }
    html[data-tema="predeterminado"] a.hdr-aviso:hover {
        background: var(--input-bg);
    }

    html[data-tema="predeterminado"] .shadow-xl {
        box-shadow: 0 20px 40px -12px rgba(76, 51, 0, 0.22);
    }
</style>
<?php endif; ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/tema.blade.php ENDPATH**/ ?>