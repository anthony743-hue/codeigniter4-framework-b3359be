/**
 * Script pour la page de détails d'un livre
 * Gère les interactions et les animations
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    setupScrollAnimations();
});

/**
 * Initialise tous les écouteurs d'événements
 */
function initializeEventListeners() {
    // Bouton emprunter
    const borrowBtn = document.querySelector('.btn-primary.btn-action');
    if (borrowBtn && !borrowBtn.disabled) {
        borrowBtn.addEventListener('click', handleBorrow);
    }

    // Bouton ajouter aux favoris
    const favoriteBtn = document.querySelector('.btn-outline-secondary.btn-action');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', handleAddToFavorites);
    }

    // Bouton notification (si emprunté)
    const notificationBtn = Array.from(document.querySelectorAll('.btn-outline-primary.btn-action'))
        .find(btn => btn.textContent.includes('notification'));
    
    if (notificationBtn) {
        notificationBtn.addEventListener('click', handleNotification);
    }

    // Bouton voir tous les avis
    const seeMoreBtn = document.querySelector('.btn-see-more');
    if (seeMoreBtn) {
        seeMoreBtn.addEventListener('click', handleSeeMoreReviews);
    }

    // Liens des livres connexes
    document.querySelectorAll('.related-book-card').forEach(card => {
        card.addEventListener('mouseenter', handleRelatedBookHover);
        card.addEventListener('mouseleave', handleRelatedBookHoverOut);
    });
}

/**
 * Gère l'emprunt d'un livre
 */
function handleBorrow(event) {
    event.preventDefault();
    
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    
    // Animation du clic
    btn.classList.add('loading');
    btn.innerHTML = '<span class="spinner">⏳</span> Emprunt en cours...';
    btn.disabled = true;

    // Simuler une requête API
    setTimeout(() => {
        showNotification('Livre emprunté avec succès! ✓', 'success');
        btn.classList.remove('loading');
        btn.innerHTML = '<span class="btn-icon">✓</span> Livre emprunté';
        btn.style.opacity = '0.7';
        btn.disabled = true;

        // Log pour débogage
        console.log('Livre emprunté');
    }, 1500);
}

/**
 * Gère l'ajout aux favoris
 */
function handleAddToFavorites(event) {
    event.preventDefault();
    
    const btn = event.currentTarget;
    const isFavorited = btn.classList.contains('favorited');

    if (isFavorited) {
        btn.classList.remove('favorited');
        btn.innerHTML = '<span class="btn-icon">❤️</span> Ajouter aux favoris';
        showNotification('Supprimé des favoris', 'info');
    } else {
        btn.classList.add('favorited');
        btn.innerHTML = '<span class="btn-icon">❤️</span> Enlevé des favoris';
        btn.style.color = '#e74c3c';
        showNotification('Ajouté aux favoris ✓', 'success');
    }

    console.log('État des favoris togglé');
}

/**
 * Gère la notification d'indisponibilité
 */
function handleNotification(event) {
    event.preventDefault();
    
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<span class="btn-icon">✓</span> Notification activée';
    btn.style.opacity = '0.7';
    btn.disabled = true;

    showNotification('Vous recevrez une notification dès la disponibilité', 'success');

    console.log('Notification de disponibilité activée');
}

/**
 * Gère le bouton "Voir tous les avis"
 */
function handleSeeMoreReviews(event) {
    event.preventDefault();
    
    const btn = event.currentTarget;
    const originalText = btn.textContent;
    
    btn.textContent = 'Chargement des avis...';
    btn.disabled = true;

    // Simuler le chargement d'avis supplémentaires
    setTimeout(() => {
        showNotification('Page des avis en cours de chargement...', 'info');
        // Redirection possible vers une page d'avis complète
        // window.location.href = '<?= base_url("livres/" . $livre["id"] . "/avis") ?>';
        btn.textContent = originalText;
        btn.disabled = false;
    }, 1000);
}

/**
 * Gère l'effet hover sur les cartes de livres connexes
 */
function handleRelatedBookHover(event) {
    const card = event.currentTarget;
    card.style.transform = 'translateY(-8px) scale(1.02)';
}

function handleRelatedBookHoverOut(event) {
    const card = event.currentTarget;
    card.style.transform = 'translateY(-4px) scale(1)';
}

/**
 * Affiche une notification toast
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type de notification (success, error, info)
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `toast-notification toast-${type}`;
    notification.textContent = message;

    // Ajouter les styles s'ils ne sont pas déjà présents
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            .toast-notification {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                padding: 1rem 1.5rem;
                border-radius: 0.8rem;
                font-weight: 600;
                z-index: 9999;
                animation: slideInUp 0.3s ease-out;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                max-width: 300px;
            }

            .toast-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .toast-error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            .toast-info {
                background-color: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }

            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutDown {
                from {
                    transform: translateY(0);
                    opacity: 1;
                }
                to {
                    transform: translateY(100px);
                    opacity: 0;
                }
            }

            @media (max-width: 768px) {
                .toast-notification {
                    bottom: 1rem;
                    right: 1rem;
                    left: 1rem;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Auto-remove après 4 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOutDown 0.3s ease-in';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

/**
 * Configuration des animations au scroll
 */
function setupScrollAnimations() {
    // Observer pour les animations au scroll (si désiré)
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        });

        // Appliquer l'observer aux sections
        document.querySelectorAll('.detail-card, .related-book-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(el);
        });
    }
}

/**
 * Utilitaire : Vérifier si un élément est en vue
 */
function isElementInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * Log d'information (peut être remplacé par un vrai système de logs)
 */
console.log('Script livre-detail.js chargé avec succès');