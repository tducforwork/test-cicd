const fs = require('fs');
let css = fs.readFileSync('index.css', 'utf8');
css += `

/* ==========================================================================
   ABOUT PAGE SPECIFIC STYLES & EFFECTS
   ========================================================================== */

.reveal-up {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal-up.active {
    opacity: 1;
    transform: translateY(0);
}
.delay-100 { transition-delay: 100ms; }
.delay-200 { transition-delay: 200ms; }
.delay-300 { transition-delay: 300ms; }

.value-card {
    transition: transform 0.4s ease, box-shadow 0.4s ease !important;
}
.value-card:hover {
    transform: translateY(-10px) !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
}

.image-composition {
    position: relative;
    width: 100%;
    height: 600px;
}
.image-composition img {
    position: absolute;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}
.image-composition .img-main {
    width: 80%;
    height: 80%;
    top: 0;
    right: 0;
    z-index: 1;
}
.image-composition .img-overlap {
    width: 60%;
    height: 60%;
    bottom: 0;
    left: 0;
    z-index: 2;
    border: 10px solid white;
}
`;
fs.writeFileSync('index.css', css);
console.log('Appended styles to index.css');
