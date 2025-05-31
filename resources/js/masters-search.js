export function initMastersSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    const cards = document.querySelectorAll('[name="name"]');

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase().trim();
        
        cards.forEach(card => {
            const cardTitle = card.textContent.toLowerCase();
            const cardContainer = card.closest('.flex.flex-col.justify-between');
            
            if (cardTitle.includes(searchValue)) {
                cardContainer.style.display = 'flex';
            } else {
                cardContainer.style.display = 'none';
            }
        });
    });
} 