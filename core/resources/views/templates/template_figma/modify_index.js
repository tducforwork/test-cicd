const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, 'index.html');
let html = fs.readFileSync(filePath, 'utf8');

// Function to get random badges
function getRandomBadges() {
    const badges = [
        '<span class="badge-item badge-mall">Mall</span>',
        '<span class="badge-item badge-cheap">Rẻ vô đối</span>',
        '<span class="badge-item badge-freeship">Freeship</span>'
    ];
    // Pick 1 to 3 random badges
    const numBadges = Math.floor(Math.random() * 3) + 1;
    // Shuffle
    badges.sort(() => Math.random() - 0.5);
    return `<div class="badges-overlay">\n                  ${badges.slice(0, numBadges).join('\n                  ')}\n                </div>`;
}

// 1. Add badges to all <div class="p-image-box"> that don't have it yet
html = html.replace(/<div class="p-image-box">([\s\S]*?)<\/div>/g, (match, inner) => {
    if (inner.includes('badges-overlay')) return match; // already added
    return `<div class="p-image-box">
                ${getRandomBadges()}${inner}</div>`;
});

// 2. Add progress bar to flash sale items
// First, extract flash sale section
const flashSaleRegex = /(<section class="flash-sale-section[\s\S]*?<\/section>)/;
html = html.replace(flashSaleRegex, (match) => {
    // inside flash sale section, add progress bar before <div class="p-price"> if it's not there
    let flashHtml = match.replace(/(<div class="p-unit">.*?<\/div>)?[\s\n]*(<p class="p-price">|<div class="p-price">)/g, (m, p1, p2) => {
        if (m.includes('flash-progress')) return m;
        const percent = Math.floor(Math.random() * 50) + 40; // 40-90%
        const isHot = percent > 80;
        const progressHtml = `
                <div class="flash-progress">
                  <div class="flash-progress-bar" style="width: ${percent}%"></div>
                  <span class="flash-progress-text">Đã bán ${percent}%${isHot ? ' - Sắp cháy hàng' : ''}</span>
                </div>
                `;
        return (p1 ? p1 + '\n' : '') + progressHtml + p2;
    });
    return flashHtml;
});

fs.writeFileSync(filePath, html, 'utf8');
console.log('Modified index.html successfully.');
