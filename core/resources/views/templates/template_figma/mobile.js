/**
 * QP-Logistic Mobile Interaction Handler
 * Handles Menu Toggle, Drawer, and Overlay
 */

document.addEventListener('DOMContentLoaded', () => {
    const openMobileMenu = document.getElementById('openMobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileOverlay = document.getElementById('mobileOverlay');

    const toggleMobileMenu = (isOpen) => {
        if (isOpen) {
            mobileDrawer.classList.add('active');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scroll
        } else {
            mobileDrawer.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if (openMobileMenu) openMobileMenu.addEventListener('click', () => toggleMobileMenu(true));
    if (closeMobileMenu) closeMobileMenu.addEventListener('click', () => toggleMobileMenu(false));
    if (mobileOverlay) mobileOverlay.addEventListener('click', () => toggleMobileMenu(false));

    // Handle Search on Mobile (Toggle new dedicated search bar)
    const mobileSearchTrigger = document.querySelector('.mobile-header .search-trigger');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    const mobileSearchInput = document.getElementById('mobileSearchInput');

    if (mobileSearchTrigger && mobileSearchBar) {
        mobileSearchTrigger.addEventListener('click', () => {
            mobileSearchBar.classList.toggle('active');
            if (mobileSearchBar.classList.contains('active')) {
                mobileSearchInput.focus();
            }
        });
    }

    // Handle Submenus in Mobile Drawer
    const submenuItems = document.querySelectorAll('.menu-item.has-submenu');
    
    submenuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = item.getAttribute('data-target');
            const submenu = document.getElementById(targetId);
            
            if (submenu) {
                const isOpen = item.classList.contains('open');
                
                // Close all other submenus if any
                document.querySelectorAll('.menu-item.has-submenu').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('open');
                        const otherSub = document.getElementById(otherItem.getAttribute('data-target'));
                        if (otherSub) otherSub.classList.remove('active');
                    }
                });

                // Toggle current
                item.classList.toggle('open');
                submenu.classList.toggle('active');
            }
        });
    });

    // Premium Scroll Header Effect
    const mobileHeader = document.querySelector('.mobile-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            mobileHeader.classList.add('scrolled');
        } else {
            mobileHeader.classList.remove('scrolled');
        }
    });

    // Bottom Nav Active State Based on URL
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    const navItems = document.querySelectorAll('.mobile-nav-item');
    navItems.forEach(item => {
        const itemHref = item.getAttribute('href');
        if (itemHref === currentPath) {
            navItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        }
    });

    // --- Floating Actions Toggle (FAB) ---
    const floatingActions = document.querySelector('.floating-actions');
    if (floatingActions) {
        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'action-btn fab-toggle-btn';
        toggleBtn.innerHTML = '<i class="fa-solid fa-plus"></i>';
        toggleBtn.setAttribute('title', 'Mở liên hệ');
        
        // Append to container
        floatingActions.appendChild(toggleBtn);

        // Click event
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            floatingActions.classList.toggle('active');
            
            // Toggle icon
            const icon = toggleBtn.querySelector('i');
            if (floatingActions.classList.contains('active')) {
                icon.classList.replace('fa-plus', 'fa-xmark');
                toggleBtn.style.background = '#64748b'; // Change color when open
            } else {
                icon.classList.replace('fa-xmark', 'fa-plus');
                toggleBtn.style.background = ''; // Reset
            }
        });
    }
});
